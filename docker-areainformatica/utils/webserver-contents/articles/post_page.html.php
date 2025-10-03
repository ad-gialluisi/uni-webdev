<!DOCTYPE html>

<html>
    <!--
    Questa serve a mostrare un singolo articolo.
    Questa pagina è stata sviluppata con lo scopo di mostrare ambedue i tipi di articoli:
        Lezioni
        News

    Per mostrare un articolo già esistente occorre
    digitare l'url di questa pagina con il parametro GET:
        article=?

    dove ? è l'id dell'articolo.
    -->
    
    
    <head>
        <title>Lettura articolo</title>
        <meta charset="utf-8">
    </head>

    <body>
    
        <?php
        
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
            
        $NOHEADER=1;
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");

        //Ottieni parametri get
        $get_pars = filter_input_array(INPUT_GET);

        /*
         * Con questa chiamata è possibile distruggere eventuali
         * dati temporanei dei form usati in precedenza.
         * NB: È bene fare questa cosa su tutte le pagine che non
         * hanno sole funzioni di form (tipo questa)
         * o che non ne abbiano affatto.
         */
        $sess = new FormSessionHandler();
        $sess->destroyFormData();

        //Solito redirect in caso di fallimento nel recupero dati
        if (!$get_pars || !isset($get_pars['article']) || !is_numeric($get_pars['article'])) {
            $sess->redirect("/");
        }

        //Ottieni le seguenti informazioni sull'articolo:
        //Titolo
        //Contenuto
        //Tipo (Lezione o News)
        //Nickname dell'autore
        //Id dell'autore
        //Data di pubblicazione
        $db = new DatabaseHandler();
        $db->open_db();
        $resultSet = $db->perform_query(
           "select title, content, Article.type as type, nickname, author, publishment_date as date " .
           "from Article, User where Article.id = ? and author = User.id",
           $get_pars['article']);


        //Se l'articolo non esiste, redireziona
        if (count($resultSet) == 0) {
            $sess->redirect("/");
        }
        $article = $resultSet[0];
            
 
        //Ottieni i dati relativi ai commenti sull'articolo
        //Id del commento
        //Contenuto
        //Nickname dell'autore
        //Id dell'autore
        //Avatar dell'autore
        $comments = $db->perform_query(
            "select Comment.id as id, nickname, content, author, avatar " .
            "from Comment, User where author = User.id and article = ?",
            $get_pars['article']);


        //Ottieni i dati relativi ai tag associati all'articolo
        $tags = $db->perform_query(
            "select name from ArticleTags, Tag where article = ? and tag = id",
            $get_pars['article']);


        //Ora le cose si fanno più difficili
        //Dobbiamo ottenere le informazioni relative ai link
        //"Pagina precedente" e "Pagina successiva"
        //Ergo, ci servono gli id dei suddetti articoli
        if ($article['type'] == "lesson") {

            //Ottenimento informazioni sulla particolare lezione
            $lessoninfo = $db->perform_query(
                "select nlesson, subject as subj_id, name as subj_name " .
                    "from Lessons, Subject where article = ? and Subject.id = subject",
                $get_pars['article']);
            $lessoninfo = $lessoninfo[0];


            $fulltitle = $article['title'] . " - Lezione " . $lessoninfo['nlesson'] .
                " - <a href='/subjects/lessons/lessons_page.html.php?subject=" .
                $lessoninfo['subj_id'] . "'>" . $lessoninfo['subj_name'] . "</a>";


            
            /*
             * Se l'articolo è una lezione, vogliamo
             * banalmente la lezione seguente
             */
            for ($i = 0; $i < 2; $i++) {

                //Prendi gli id dell'articoli
                //i = 0 è il precedente
                //i = 1 è il successivo
                $query = sprintf("select article as art_id from Lessons " .
                        "where nlesson %s ? and subject = ? order by nlesson %s limit 1",
                    (($i == 0) ? "<" : ">"),
                    (($i == 0) ? "DESC" : "ASC"));

                $pages[$i] = $db->perform_query(
                    $query,
                    $lessoninfo['nlesson'],
                    $lessoninfo['subj_id']);


                if (count($pages[$i]) == 0) {
                    //Non esistendo risultati
                    //L'eventuale successivo/precedente non esiste
                    //Imposta a NULL, verrà usato come valore particolare
                    $pages[$i] = NULL;
                } else {
                    //Altrimenti, imposta all'unico record ottenuto

                    $pages[$i] = $pages[$i][0];
                }
            }

                

        } else {
            /*
             * Se l'articolo è una news, al contrario,
             * vogliamo l'articolo precedente/successivo
             * in ordine temporale
             * NB: non memorizzando l'orario o millisecondi
             * di sorta è necessario sfruttare anche l'ordine
             * degli id per stabilire "chi è il più nuovo"
             */
             for ($i = 0; $i < 2; $i++) {
                $query = sprintf("select id as art_id from News " .
                    "where publishment_date %s ? and " .
                    "id %s ? order by art_id %s limit 1",
                    ($i == 0 ? "<=" : ">="),
                    ($i == 0 ? "<" : ">"),
                    ($i == 0 ? "DESC" : "ASC"));
                   

                $pages[$i] = $db->perform_query(
                    $query,
                    $article['date'],
                    $get_pars['article']);
                    

                if (count($pages[$i]) == 0) {
                    //Non esistendo risultati
                    //L'eventuale successivo/precedente non esiste
                    //Imposta a NULL, verrà usato come valore particolare
                    $pages[$i] = NULL;
                } else {
                   //Altrimenti, imposta all'unico record ottenuto
                   $pages[$i] = $pages[$i][0];
                }
            }
                
                
                
            $fulltitle = $article['title'];
        }
        

        //Inserimento post precedente/successivo
        for ($i = 0; $i < count($pages); $i++):
            if ($pages[$i] != NULL):
        
        ?>
        
        <a href = "/articles/post_page.html.php?article=<?php print $pages[$i]['art_id'] ?>"><?php print ($i == 0 ? "Articolo precedente" : "Articolo successivo");?></a>
        
        <?php
        
            else:
                print ($i == 0 ? "<b>Articolo precedente</b>" : "<b>Articolo successivo</b>");
            endif;
        endfor;
        
        ?>
        

        <h1><?php print $fulltitle; ?></h1>
        
            
        <article>
            <div>
                <?php print $article['content']; ?>
            </div>
        </article>

        <section>
            <h3>scritto da <a href = "<?php print "/users/profile_page.html.php?user=" . $article['author']; ?>"><?php print $article['nickname'];?></a></h3>
        </section>
            
            

        <section>
            <h3>Tag:</h3>
            <div>
                <?php
                
                $ntags = count($tags);
                for ($i = 0; $i < $ntags; $i++) {
                    print $tags[$i]['name'] . ($i < $ntags - 1 ? ", " : "");
                }
                
                ?>
            </div>
        </section>
            

        <section>
            <h3>Commenti:</h3>
            <div>
                <?php
                if (count($comments) == 0):
                ?>
                
                Nessun commento rilasciato
                
                <?php
                else:
                ?>
                
                <ul>
                
                    <?php
                    for ($i = 0; $i < count($comments); $i++):
                    ?>
                
                    <li>
                        <img src = "<?php print $comments[$i]['avatar']?>" width = 50 height = 50 alt = "commenter avatar" align = "absmiddle"/>
                        <a href = "/users/profile_page.html.php?user=<?php print $comments[$i]['author'] ?>"><?php print $comments[$i]['nickname']; ?></a>
                        <p><?php print $comments[$i]['content'];?></p>

                        <?php

                        if ($sess->userDataExist() &&
                           ($sess->getUserId() == $comments[$i]['author'] ||
                           $sess->getUserType() == "admin")):

                        ?>
                
                        <form action="/articles/action_comment.php" method="post">
                            <input type = "hidden" name="comment_id" value="<?php print $comments[$i]['id'] ?>">
                            <input type = "hidden" name="article_id" value ="<?php print $get_pars['article']; ?>"/>
                            <p><input type = "submit" name = "remove" value = "Elimina"/></p>
                        </form>

                        <?php
                        endif;
                        ?>
                    </li>
                    <br>
                    <?php endfor; ?>
                </ul>
                <?php endif; ?>
            </div>
        </section>
            
            
        <section>
        
            <?php
        
            /*
            * Se si è loggati mostrare il form per i commenti
            */
            if ($sess->userDataExist()):
        
            ?>
        
            <p>Condividi la tua opinione sull'articolo!</p>
                
            <form action="/articles/action_comment.php" method="post">
                <textarea name = "comment_editor" rows="10" cols="80"></textarea>
                <input type = "hidden" name= "article_id" value ="<?php print $get_pars['article']; ?>"/>
                <p><input type = "submit" name= "create" value = "Pubblica"/></p>
            </form>

            <?php
            endif;
            ?>

        </section>

        <?php
        
        /*
         * Mostra errori se ci sono
         */
        $sess->showError();
        
        ?>

    </body>
</html>

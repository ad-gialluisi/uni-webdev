<!DOCTYPE html>

<html>
    <head>
        <title>Blog</title>
        <meta charset="utf-8">
    </head>

    <body>

        <?php
    
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/other/utilities.php");
    
        $sess = new FormSessionHandler();
    
        $LOCATION="BLOG";
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");


        $get_pars = filter_input_array(INPUT_GET);
        if (!$get_pars) {
            $from = 0;
            $limit = 5;
        } else {
            if (isset($get_pars['from'])) {
                if (is_numeric($get_pars['from'])) {
                    $from = $get_pars['from'];
                
                } else {
                    $sess->redirect("/");
                }
            } else {
                $from = 0;
            }
        
            if (isset($get_pars['limit'])) {
                if (is_numeric($get_pars['limit'])) {
                    $limit = $get_pars['limit'];
                
                    if ($limit == 0) {
                        $limit = 5;
                    }
                } else {
                    $sess->redirect("/");
                }
            } else {
                $limit = 5;
            }
        }


        $db = new DatabaseHandler();
        $db->open_db();

        if (isset($get_pars['search'])) {
            $news = $db->perform_query("select distinct News.id as newsid, News.title as newstitle, News.content as newscont, News.author as authid from News " .
                "left join ArticleTags on ArticleTags.article = News.id " . 
                "left join Tag on Tag.id = ArticleTags.tag " .
                "where Tag.name like ? or News.title like ? or News.content like ? order by publishment_date, newsid DESC",
                "%" . $get_pars['search'] . "%", "%" . $get_pars['search'] . "%", "%" . $get_pars['search'] . "%");

        } else if (isset($get_pars['tag'])) {
            if (!is_numeric($get_pars['tag'])) {
                $sess->redirect("/");
            }
            $news = $db->perform_query("select News.id as newsid, News.title as newstitle, News.content as newscont, News.author as authid " .
                "from News, ArticleTags where ArticleTags.article = News.id and ArticleTags.tag = ? order by publishment_date, newsid DESC",
                $get_pars['tag']);

        } else {
            $news = $db->perform_query("select News.id as newsid, News.title as newstitle, News.content as newscont, News.author as authid " .
                "from News order by publishment_date, newsid DESC");
        }

        $newscnt = count($news);

        if ($from > $newscnt - 1) {
            $from = 0;
        }

        $tags = $db->perform_query("select distinct Tag.id as id, name from ArticleTags, News, Tag " .
            "where ArticleTags.article = News.id and Tag.id = ArticleTags.tag");
    
        $db->close_db();
    
        ?>

        <h2>Blog</h2>

        <article>

            <?php    
        
            if (isset($get_pars['search'])) {
                print "<h3>Risultati per \"" . $get_pars['search'] . "\":</h3>";
            }
    

            $forcount = ($from + $limit > $newscnt - 1 ? ($newscnt - $from) : $limit);

            for ($i = $from; $i < $from + $forcount; $i++):
            
            ?>

            <div>
                <h1><?php print $news[$i]['newstitle'];?></h1>

                <div>
                    <?php print substr($news[$i]['newscont'], 0, 300); ?>
                    <a href="/articles/post_page.html.php?article=<?php print $news[$i]['newsid']; ?>">Leggi l'articolo</a>
                </div>


                <?php

                /*
                 * Mostra la possibilità di modificare le notizie solo
                 * agli autori di tali notizie e agli amministratori
                 */
                if ($sess->userDataExist() &&
                    ($sess->getUserId() == $news[$i]['authid'] ||
                        $sess->getUserType() == "admin")):

                ?>

                <div>
            
                    <form action="/articles/action_post_content.php?action=edit_post&type=news" method="post">
                        <input type="hidden" name="article_id" value = "<?php print $news[$i]['newsid']; ?>"/>
                        <input type="hidden" name="article_title" value="<?php print $news[$i]['newstitle']; ?>"/>
                        <input type="submit" name="edit" value="Modifica" />

                        <?php
                        if ($sess->getUserType() == "admin"):
                        ?>
                            <input type="submit" name="remove" value="Elimina"/>
                        <?php
                        endif;
                        ?>
                    </form>
                        
                        
                </div>
            
                <?php
                endif;
                ?>

            </div>

            <?php
            endfor;
            ?>
        </article>


        <section>
            <h2>Tag:</h2>
            <ul>
                <?php
                for ($i = 0; $i < count($tags); $i++):
                ?>
            
                <li><a href = "/other/blogindex.html.php?tag=<?php print $tags[$i]['id']?>"><?php print $tags[$i]['name']; ?></a></li>

                <?php
                endfor;
                ?>
            </ul>
        </section>


        <section>
            <h2>Cerca:</h2>

            <form method="get" action="/other/blogindex.html.php">
                <input name="search" placeholder="Search..." type="text">
                <input value="Search" type="submit">
            </form>
        </section>


        <section>
            <h2>Archivio Blog:</h2>
    
            <ul>
            <?php

                $db->open_db();
                $years = $db->perform_query("select distinct year(publishment_date) as year from News");

                for ($i = 0; $i < count($years); $i++):

            ?>

                <li><?php print $years[$i]['year']; ?>
                    <ul>
                    <?php

                    for ($e = 12; $e >= 0; $e--):
                        $posts = $db->perform_query("select id, title from News where year(publishment_date) = ? and month(publishment_date) = ? order by publishment_date, id DESC",
                        $years[$i]['year'], $e);

                        if (count($posts) > 0):

                    ?>

                    <li><?php print toMonth($e); ?>
                        <ul>
                        
                        <?php
                        for ($f = 0; $f < count($posts); $f++):
                        ?>
                        
                        <li><a href = "/articles/post_page.html.php?article=<?php print $posts[$f]['id']; ?>"><?php print $posts[$f]['title']; ?></a></li>
                        
                        <?php
                        endfor;
                        ?>
                        
                        </ul>
                    </li>

                    <?php
                        endif;
                    endfor;
                    ?>
                    </ul>
                </li>
            <?php
                
            endfor;
            $db->close_db();
        
            ?>
            </ul>
    
        </section>


        <?php
        if ($from + $limit < $newscnt):
        ?>

        <a href = "/other/blogindex.html.php?from=<?php print $from + $limit; ?>&limit=<?php print $limit; ?>">Post Precedenti</a>

        <?php
        endif;
        ?>


        <?php
        if ($from - $limit >= 0):
        ?>

        <a href = "/other/blogindex.html.php?from=<?php print $from - $limit; ?>&limit=<?php print $limit; ?>">Post Successivi</a>

        <?php
        endif;
        ?>

    </body>
</html>


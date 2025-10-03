<!DOCTYPE html>

<html>
    <head>
        <title>Editor dei post</title>
        <meta charset="utf-8">
        <script src="/articles/ckeditor/ckeditor.js"></script>
    </head>

    <body>
        <?php
        
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/UploadHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/articles/PostSessionHandler.php");
            
        $NOHEADER=1;
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");

        /*
         * redireziona utente se non loggato
         */
        $sess = new PostSessionHandler();
        if (!$sess->userDataExist()) {
            $sess->redirect("/");
        }
            
        $db = new DatabaseHandler();
        $db->open_db();
            
        //Se l'articolo è nuovo, ed è una lezione,
        //aggiungi la possibilità di specificare una materia.
        if ($sess->getFormType() == 'lesson' && $sess->getFormMode() == "new") {
            $subject_list = $db->perform_query("select id, name from Subject");
        }


        //Ottieni la lista di tag disponibili
        $tags = $db->perform_query("select * from Tag");
        $db->close_db();

        ?>
        
        
        
        <form action="/articles/action_post_content.php?action=update_post&type=<?php print $sess->getFormType() ?>" method="post" enctype="multipart/form-data">
            <?php
            
            //Se è una lezione, aggiungi una <select>
            if (isset($subject_list)):
            
            ?>
            <section>
                <div>
                    <h3>Scegli una materia d'appartenenza</h3>

                    <select name='subject_id'>
                        <option value='None' <?php $sess->getFormSubject() === "" ? print "selected" : print ""; ?>>Scegli materia</option>

                        <?php
                        
                        for ($i = 0; $i < count($subject_list); $i++):
                            $id = $subject_list[$i]['id'];
                            $name = $subject_list[$i]['name'];
                        
                        ?>
                          
                            <option value = "<?php print $id; ?>" <?php $id === $sess->getFormSubject() ? print "selected" : print ""; ?>><?php print $name; ?></option>               

                        <?php
                        endfor;
                        ?>

                    </select>
                </div>
            </section>
            
            <?php
            endif;
            ?>


            <section>
                <div>
                    <h3>Titolo</h3>
                    <input type="text" name="title_editor" value='<?php print $sess->getFormTitle(); ?>'/>
                </div>
            </section>

            
            <section>
                <div>
                    <h3>Contenuto</h3>
                    <textarea name="content_editor" rows="10" cols="80"><?php print $sess->getFormContent(); ?></textarea>
                    <script>
                        CKEDITOR.replace('content_editor');
                    </script>
                </div>
            </section>
        
            
            <section>
                <h3>Etichette</h3>
                
                <div>
                    <h5>Associate all'articolo</h5>
                    <div>
                        <ul>
                        <?php
                        
                        //Mostra i tag associati all'articolo
                        for ($i = 0; $i < $sess->getTagNumber(); $i++):
                            $tagid = $sess->getTagIdAt($i);
                            $currtag = $sess->getTagName($tagid);
                        
                        ?>
                        
                            <li>
                                <?php print $currtag; ?>
                                <button name="remove_tag" type="submit" value="<?php print $tagid; ?>">Scarta</button>
                            </li>
                        
                        <?php
                        endfor;
                        ?>
                    </div>
                </div>


                <div>
                    <h5>Disponibili</h5>
                    <div>
                        <ul>
                            <?php
                        
                            //Mostra tutti i tag disponibili
                            for ($i = 0; $i < count($tags); $i++):
                                $id = $tags[$i]['id'];
                                $name = $tags[$i]['name'];

                            ?>
                
                            <li>
                                <?php print $name; ?>
                                <button name="add_tag" type="submit" value="<?php print $id; ?>">Associa</button>
                                <button name="delete_tag" type="submit" value="<?php print $id; ?>">Elimina</button>
                            </li>
                        
                            <?php
                            endfor;
                            ?>
                        </ul>
                    </div>
                </div>

            
                <div>
                    <h5>Creazione Etichetta</h5>
                    <input type = "text" name="tag_name"/>
                    <input type="submit" name="create_tag" value="Salva" />
                </div>
            </section>

            
            <section>
                <h3>Sezione upload dei file</h3>
                
                <b>File presenti sul server:</b>
                <ul>
                    <?php
                
                    $files = scandir($_SERVER['DOCUMENT_ROOT'] . "/uploads/");
                    for ($i = 2; $i < count($files); $i++):
                    
                    ?>
                    
                    <li>
                        <a href = "/uploads/<?php print $files[$i]; ?>"><?php print $files[$i]; ?></a>
                    </li>
                    
                    <?php
                    endfor;
                    ?>
                </ul>
                    
                <input type="file" name="content_file"/>
                <input type="submit" name="file_upload" value="Upload" />
            </section>
            
            
            <div>
                <p><input type="submit" name="post_upload" value="<?php ($sess->getFormMode() == "edit") ? print "Aggiorna articolo" : print "Pubblica articolo"; ?>" /></p>
            </div>


        </form>


        <?php
        $sess->showError();
        ?>

    </body>
</html>

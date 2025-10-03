<?php
// Copyright (C) 2015 Antonio Daniele Gialluisi

// This file is part of "Area informatica"

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program. If not, see <https://www.gnu.org/licenses/>.
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Lezioni disponibili</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php
        
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");

        $NOHEADER=1;
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");

        /*
         * Con questa chiamata è possibile distruggere eventuali
         * dati temporanei dei form usati in precedenza.
         * NB: È bene fare questa cosa su tutte le pagine che non
         * hanno sole funzioni di form (tipo questa)
         * o che non ne abbiano affatto.
         */
        $sess = new FormSessionHandler();
        $sess->destroyFormData();
            
            
        $get_pars = filter_input_array(INPUT_GET);
            
        //Redireziona in caso di problemi
        if (!$get_pars || !isset($get_pars['subject']) || !is_numeric($get_pars['subject'])) {
            $sess->redirect("/");
        }
            

        $db = new DatabaseHandler();
        $db->open_db();
            
        //Ottieni la materia
        $subjectResult = $db->perform_query(
            "select id, name from Subject where id = ?",
            $get_pars['subject']);
            
        //Redireziona se non presente
        if (count($subjectResult) == 0) {
            $sess->redirect("/");
        }
        $subjectResult = $subjectResult[0];


        //Ottieni le lezioni
        $lessons = $db->perform_query(
            "select nlesson, article, title, author from Lessons, Article where id = article and subject = ? order by nlesson ASC",
            $get_pars['subject']);
        $db->close_db();
        
        ?>
        
        <h1>Lezioni di <?php print $subjectResult['name']?></h1>
            

        <ul>

            <?php

            if (count($lessons) == 0) {
                print "<li>Nessuna lezione trovata</li>";
            }
            
            for ($i = 0; $i < count($lessons); $i++):
            
            ?>
                <li>
                    <h3>Lezione <?php print $lessons[$i]['nlesson']?>: <a href = "/articles/post_page.html.php?article=<?php print $lessons[$i]['article'] ?>"><?php print $lessons[$i]['title']?></a></h3>
                        

                    <?php
                    
                    /*
                     * Mostra la possibilità di modificare le lezioni solo
                     * agli autori di tali lezioni e agli amministratori
                     */
                    if ($sess->userDataExist() &&
                        ($sess->getUserId() == $lessons[$i]['author'] ||
                            $sess->getUserType() == "admin")):
                    
                    ?>

                    <form action="/articles/action_post_content.php?action=edit_post&type=lesson" method="post">
                        <input type="hidden" name="article_id" value = "<?php print $lessons[$i]['article']; ?>"/>
                        <input type="hidden" name="article_title" value="<?php print $lessons[$i]['title']; ?>"/>
                        <input type="hidden" name="subject_id" value="<?php print $get_pars['subject']; ?>"/>
                        <input type="submit" name="edit" value="Modifica" />
                        
                        <?php if ($sess->getUserType() == "admin"): ?>
                            <input type="submit" name="remove" value="Elimina"/>
                        <?php endif; ?>
                    </form>


                    <?php
                    
                        /*
                         * Mostra la possibilità ai soli amministratori
                         * di cancellare e di spostare le lezioni
                        */
                        if ($sess->getUserType() == "admin"):
                    
                    ?>

                    <form action="/subjects/lessons/action_move_lesson.php" method="post">
                        <input type="hidden" name="article_id" value="<?php print $lessons[$i]['article'] ?>"/>
                        <input type="hidden" name="subject_id" value="<?php print $get_pars['subject']; ?>"/>
                        <input type="hidden" name="nlesson" value="<?php print $lessons[$i]['nlesson']; ?>"/>
                                   
                        <?php if ($i > 0): ?>
                        <input type="submit" name="lesson_move_up" value="Sposta su" />
                        <?php
                        
                        endif;
                        
                        if ($i < count($lessons) - 1):
                        
                        ?>
                        <input type="submit" name="lesson_move_down" value="Sposta giù" />
                        <?php endif; ?>
                    </form>
                    
            <?php
                    
                    endif;
                endif;
            endfor;

            ?>
        </ul>
    </body>
</html>

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
        <title>Home</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php
        
        $LOCATION="HOME";
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/other/utilities.php");


        $form = new FormSessionHandler();
        $form->destroyFormData();

        $db = new DatabaseHandler();
        $db->open_db();


        //Ultime 5 lezioni
        /*
         * L'ordine per id è già stato spiegato in altra sede,
         * viene usato anche qui
         */
        $query = "select Lessons.article as article_id, title as article_title," .
                    "author as author_id, nickname as author_name," .
                    "publishment_date as date, subject as subject_id," .
                    "Subject.name as subject_name, nlesson " .
                    "from Article, Lessons, User, Subject " .
                    "where Article.id = Lessons.article and " .
                    "User.id = author and Lessons.subject = Subject.id " .
                    "order by publishment_date DESC, Article.id DESC limit 5";
        $lessons = $db->perform_query($query);


        //Ultime 5 news
        $query = "select News.id as article_id, title as article_title," .
                    "author as author_id, nickname as author_name," .
                    "publishment_date as date from News, User " .
                    "where author = User.id " .
                    "order by publishment_date DESC, News.id DESC limit 5";
        $news = $db->perform_query($query);


        $db->close_db();
        
        ?>
        
        
        <h2>Home</h2>


        <div>
            <h3>Ultime 5 News:</h3>

            <?php
            
            for ($i = 0; $i < count($news); $i++):
                $arttitle = $news[$i]['article_title'];
                $artid = $news[$i]['article_id'];
                $authname = $news[$i]['author_name'];
                $authid = $news[$i]['author_id'];

                $prevdate = "";
                if ($i > 0) {
                    $prevdate = $currdate;
                }
        
                $currdate = $news[$i]['date'];

            ?>

            <ul>
                <?php
                if ($i == 0 || $currdate != $prevdate):
                ?>
            
                <li><?php print showDate($currdate); ?>

                <?php
                endif;
                ?>


                    <ul>
                        <li>
                        <a href = "/articles/post_page.html.php?article=<?php print $artid; ?>"><?php print $arttitle ?></a>
                        <br>
                        Scritto da: <a href = "/users/profile_page.html.php?user=<?php print $authid; ?>"><?php print $authname; ?></a>
                        </li>
                    </ul>

                <?php
                if ($i == 0 || $currdate != $prevdate):
                ?>

                </li>

                <?php
                endif;
                ?>

            </ul>
                    
            <?php
            endfor;
            ?>
        </div>



        <div>
            <h3>Ultime 5 lezioni:</h3>

            <?php

            for ($i = 0; $i < count($lessons); $i++):
                $subjid = $lessons[$i]['subject_id'];
                $subjname = $lessons[$i]['subject_name'];
                $arttitle = $lessons[$i]['article_title'];
                $artid = $lessons[$i]['article_id'];
                $authname = $lessons[$i]['author_name'];
                $authid = $lessons[$i]['author_id'];
                $nlesson = $lessons[$i]['nlesson'];
                            
                $prevdate = "";
                if ($i > 0) {
                    $prevdate = $currdate;
                }

                $currdate = $lessons[$i]['date'];

            ?>

            <ul>
                <?php
                if ($i == 0 || $currdate != $prevdate):
                ?>
                        
                <li><?php print showDate($currdate); ?>
   
                <?php
                endif;
                ?>

                    <ul>
                        <li>Lezione <?php print $nlesson; ?>: <a href = "/articles/post_page.html.php?article=<?php print $artid ?>"><?php print $arttitle; ?></a>
                            <br>
                            Materia: <a href = "/subjects/lessons/lessons_page.html.php?subject=<?php print $subjid; ?>"><?php print $subjname;?></a>
                            <br>
                            Scritta da: <a href = "/users/profile_page.html.php?user=<?php print $authid; ?>"><?php print $authname; ?></a>
                        </li>
                    </ul>

                <?php
                if ($i == 0 || $currdate != $prevdate):
                ?>

                </li>
                    
                <?php
                endif;
                ?>

            </ul>
                    
                            
            <?php
            endfor;
            ?>
        </div>
    </body>
</html>

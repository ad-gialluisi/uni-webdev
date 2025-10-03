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
        <title>Rimozione articolo</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php

        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");

        $NOHEADER=1;
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");

        /*
         * redireziona utente se non loggato
         */
        $sess = new FormSessionHandler();
        if (!$sess->userDataExist()) {
            $sess->redirect("/");
        }

        //Stabilisci la pagina di ritorno in caso si scelga "No"
        //A seconda che essa sia una news o una lezione
        if ($sess->getFormType() == "lesson") {
            $pageref = "/subjects/lessons/lessons_page.html.php?subject=" . $sess->getFormSubject();
        } else {
            $pageref = "/other/blogindex.html.php";
        }

        ?>

        <h2>Sicuro di voler eliminare l'articolo "<?php print $sess->getFormTitle(); ?>"?</h2>

        <form action="/articles/action_post_content.php?action=update_post&type=<?php print $sess->getFormType()?>" method="post">
            <input type="hidden" name="article_id" value="<?php print $sess->getFormId() ?>"/>
            <input type="submit" name="post_upload" value="Sì" />
        </form>

        <a href="<?php print $pageref ?>"><button>No</button></a>

    </body>
</html>

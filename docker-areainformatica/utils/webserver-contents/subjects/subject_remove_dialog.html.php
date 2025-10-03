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
        <title>Rimozione materia</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php

        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        $NOHEADER=1;
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");
            
        //Se l'utente non è loggato, redireziona
        $sess = new FormSessionHandler();
        if (!$sess->userDataExist() || !$sess->getUserType() == 'admin') {
            $sess->redirect("/");
        }

        ?>

        
        <h2>Sicuro di voler eliminare la materia "<?php print $sess->getFormTitle() ?>" ?</h2>

        <form action="/subjects/action_subject_content.php?action=update_subject" method="post">
            <input type="submit" value="Sì" />
        </form>

        <a href='/subjects/subjects_list_page.html.php'><button>No</button></a>

    </body>
</html>

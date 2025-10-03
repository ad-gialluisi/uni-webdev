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

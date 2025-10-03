<!DOCTYPE html>

<html>
    <head>
        <title>Modifica materie</title>
        <meta charset="utf-8">
    </head>
    
    <body>    
        <div>

        <?php
            
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
            
        $NOHEADER=1;
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");
            
            
        //Redireziona se l'utente non è loggato
        $sess = new FormSessionHandler();

        if (!$sess->userDataExist() || !$sess->getUserType() == 'admin') {
            $sess->redirect("/");
        }
            
        if ($sess->getFormMode() == "edit"):
        
        ?>
        
        <h1>Modifica Materia</h1>
        
        <?php
        elseif ($sess->getFormMode() == "new"):        
        ?>

        <h1>Nuova Materia</h1>

        <?php
        endif;
        ?>

        <form action="/subjects/action_subject_content.php?action=update_subject" method="post">
            <div>
                <table>
                    <tbody>
                        <tr>
                            <td><label>Nome materia</label></td>
                            <td><input type="text" name="subject_name" value="<?php print $sess->getFormTitle(); ?>" /></td>
                        </tr>
                        
                        <tr>
                            <td><label>Descrizione</label></td>
                            <td><textarea name="subject_description" rows="8" cols="32"><?php print $sess->getFormDescription(); ?></textarea></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" align="center"><input type ="submit" value="<?php ($sess->getFormMode() == "edit") ? print "Modifca" : print "Crea"?>"/></td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
        </form>

        <?php
        $sess->showError();
        ?>
    </body>
</html>

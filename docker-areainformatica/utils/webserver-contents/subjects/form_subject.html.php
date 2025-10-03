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

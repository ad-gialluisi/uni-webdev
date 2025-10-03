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
        <title>Form d'iscrizione</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php

            require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
            require($_SERVER['DOCUMENT_ROOT'] . "/other/utilities.php");

            $sess = new FormSessionHandler();

            //Redireziona se i dati utente esistono
            if ($sess->userDataExist()) {
                $sess->redirect("/");
            }
            
            $NOACCOUNTINFO=1;
            require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");

        ?>


        <form action="/users/action_users.php?action=subscribe"  method="post" >

            <div>
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2" align="center"><h3>Registrati!</h3></td>
                        </tr>
                        
                        <tr>
                            <td><label>Nome</label></td>
                            <td><input type="text" name="name" value = "<?php print $sess->getFormName(); ?>" /></td>
                        </tr>
                        
                        <tr>
                            <td><label>Cognome</label></td>
                            <td><input type="text" name="surname" value = "<?php print $sess->getFormSurname(); ?>" /></td>
                        </tr>

                        <tr>
                            <td><label>E-Mail</label></td>
                            <td><input type="email" name="email" value = "<?php print $sess->getFormEmail(); ?>" /></td>
                        </tr>
                        
                        <tr>
                            <td><label>Data di nascita</label></td>
                            <td>
                                <select name = "date_month">
                                <?php
                                for ($i = 0; $i <= 12; $i++):
                                ?>

                                <option value = "<?php print $i ?>" <?php print ($i == $sess->getFormMonth() ? "selected" : ""); ?>><?php print ($i == 0 ? "::mese::" : toMonth($i)); ?></option>

                                <?php
                                endfor;
                                ?>
                                </select>


                                <select name = "date_day">
                                    <option value = "0" <?php print ($sess->getFormDay() == 0 ? "selected" : ""); ?>>::giorno::</option>

                                    <?php
                                    for ($i = 1; $i <= 31; $i++):
                                    ?>

                                    <option value = "<?php print $i; ?>" <?php print ($i == $sess->getFormDay() ? "selected" : ""); ?>><?php print $i; ?></option>

                                    <?php
                                    endfor;
                                    ?>
                                </select>


                                <select name = "date_year">
                                    <option value = "0" <?php print ($sess->getFormYear() == 0 ? "selected" : ""); ?>>::anno::</option>

                                    <?php
                                    for ($i = 1930; $i <= 1996; $i++):
                                    ?>
                                    
                                    <option value = "<?php print $i ?>" <?php print ($i == $sess->getFormYear() ? "selected" : ""); ?>><?php print $i; ?></option>

                                    <?php
                                    endfor;
                                    ?>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td><label>Nickname</label></td>
                            <td><input type="text" name="nickname" value="<?php print $sess->getFormNickname(); ?>" /></td>
                        </tr>
                        
                        
                        <tr>
                            <td><label>Password</label></td>
                            <td><input type="password" name="new_password"/></td>
                        </tr>
                        
                        <tr>
                            <td><label>Conferma password</label></td>
                            <td><input type="password" name="new_password_conf" /></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" align="center"><input type="submit" name="subscription" value="Iscriviti" /></td>
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

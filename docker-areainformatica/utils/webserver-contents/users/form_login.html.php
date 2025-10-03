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
        <title>Form di login</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");


            //Se i dati utente sono presenti redireziona
            $sess = new FormSessionHandler();
            if ($sess->userDataExist()) {
                $sess->redirect("/");
            }
            
            $NOACCOUNTINFO=1;
            require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");
        ?>


        <form action="/users/action_users.php?action=login"  method="post" >
            <div align = "center">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2" align="center"><h3>Login</h3></td>
                        </tr>
                        
                        
                        <tr>
                            <td><label>Nickname:</label></td>
                            <td><input type="text" name="nickname"/></td>
                        </tr>

                        <tr>
                            <td><label>Password:</label></td>
                            <td><input type="password" name="curr_password"/></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" align="center"><input type="submit" name="login" value="Login" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>


        <?php
            print $sess->showError();
        ?>
    </body>
</html>


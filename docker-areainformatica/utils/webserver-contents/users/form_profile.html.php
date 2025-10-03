<!DOCTYPE html>

<html>
    <head>
        <title>Modifica profilo</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
            require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
            require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");
            
            //Solito check, se i dati non sono presenti redireziona
            $sess = new FormSessionHandler();
            if (!$sess->userDataExist()) {
                $sess->redirect("/");
            }

            /*
             * Questo serve per caricare i dati in memoria la prima volta
             */
            if ($sess->isFormFirstTime()) {
                $db = new DatabaseHandler();

                $db->open_db();
                $resultSet = $db->perform_query("select description, avatar from User where id = ?", $sess->getUserId());
                $db->close_db();

                $sess->setFormDescription($resultSet[0]['description']);
                $sess->setFormAvatar($resultSet[0]['avatar']);
 
                $sess->setFormFirstTime(FALSE);
            }
        ?>
       

        <div>
            <form action="/users/action_users.php?action=update_profile" method="post" enctype="multipart/form-data">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2" align="center"><h3>Modifica account</h3></td>
                        </tr>
                        
                        <tr>
                            <td><img src = "<?php print $sess->getFormAvatar(); ?>" width="100" height="100" alt="user avatar" align="absmiddle"/></td>

                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><input type="file" name="content_file"/></td>
                                        </tr>
                                    
                                        <tr>
                                            <td><input type="submit" name="upload_avatar" value="Carica avatar"/></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                
                <table>
                    <tbody>
                        <tr>
                            <td><label>Descrizione</label>
                            <td><textarea name="description" rows="8" cols="32"><?php print $sess->getFormDescription(); ?></textarea></td>
                        </tr>

                        <tr>
                            <td><label>Password attuale:</label></td>
                            <td><input type="password" name="curr_password" /></td>
                        </tr>

                        <tr>
                            <td><label>Nuova password:</label></td>
                            <td><input type="password" name="new_password" /></td>
                        </tr>

                        <tr>
                            <td><label>Conferma nuova password:</label></td>
                            <td><input type="password" name="new_password_conf" /></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" align="center"><input type="submit" name="update_profile" value="Aggiorna informazioni" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>


        <?php
            $sess->showError();
        ?>

        </div>
    </body>
</html>

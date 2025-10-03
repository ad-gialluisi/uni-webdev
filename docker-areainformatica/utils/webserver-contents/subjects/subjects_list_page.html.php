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
        <title>Lista materie</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php
        
        $LOCATION="LESSONS";
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");
        
        ?>

        
        
        <section>
            <?php
            
            require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
            require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        
        
            //Cancella tutti i dati del form
            $form = new FormSessionHandler();
            $form->destroyFormData();


            $sess = new CustomSessionHandler();

            //Mostra tutte le materie disponibili
            $db = new DatabaseHandler();
            $db->open_db();
            $resultSet = $db->perform_query("select id, name, description from Subject");
            $db->close_db();
            
            ?>
                

            <h2>Lezioni - Scegli la materia</h2>
            <?php
            if (count($resultSet) == 0) {
                print "No subjects available at the moment.";
            }
            ?>
            
        
            <table>
                <tbody>
                    
                    <?php
                    for ($i = 0; $i < count($resultSet); $i++):
                        $id = $resultSet[$i]['id'];
                        $name = $resultSet[$i]['name'];
                        $description = $resultSet[$i]['description'];
                    ?>

                    <tr>
                        <td>
                            <a href = "/subjects/lessons/lessons_page.html.php?subject=<?php print $id ?>"><?php print $name ?></a>:
                            <br>
                            <?php print $description ?>
                        </td>

                        <?php
                        if ($sess->userDataExist() && $sess->getUserType() == "admin"):
                        ?>

                        <td>
                            <form action="/subjects/action_subject_content.php?action=edit_subject" method="post">
                                <input type="hidden" name="subject_id" value="<?php print $id ?>"/>
                                <input type="hidden" name="subject_name" value="<?php print $name ?>"/>
                                <input type="hidden" name="subject_description" value="<?php print $description ?>"/>
    
                                <input type="submit" name="edit" value="Modifica" />
                                <input type="submit" name="remove" value="Elimina" />
                            </form>
                        </td>
                                
                        <?php
                        endif;
                        ?>
                    </tr>
  
                    <?php
                    endfor;
                    ?>
                </tbody>
            </table>
        </section>

        <?php
        $sess->showError();
        ?>

    </body>
</html>

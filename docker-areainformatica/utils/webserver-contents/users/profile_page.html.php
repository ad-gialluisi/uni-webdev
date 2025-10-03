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
<!--
Questa pagina viene utilizzata per mostrare le informazioni di
profilo di un singolo utente.
-->
<!DOCTYPE html>

<html>
    <head>
        <title>Pagina del profilo</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php

        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/other/utilities.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/other/header.php");

        //Distruggi dati dei form
        $sess = new FormSessionHandler();
        $sess->destroyFormData();

        //Verifica dell'integrità dei dati,
        //In caso contrario, si redireziona
        $get_pars = filter_input_array(INPUT_GET);
        if (!$get_pars || !isset($get_pars['user']) || !is_numeric($get_pars['user'])) {
            $sess->redirect("/");
        }


        $db = new DatabaseHandler();
        $db->open_db();

        //Otteniamo dapprima le informazioni sull'utente
        $resultSet = $db->perform_query(
            "select * from User where id = ?",
            $get_pars['user']);


        //Se l'utente non esiste redireziona
        if (count($resultSet) == 0) {
            $sess->redirect("/");
        }


        $user = $resultSet[0];

        //Ultime 5 Lezioni fatte dall'utente
        /*
         * La query utilizzata ottiene le seguenti informazioni:
         * **Id dell'articolo
         * **Id della materia d'appartenenza
         * **Numero della lezione nella materia d'appartenenza
         * **Nome della materia d'appartenenza
         * **Data di pubblicazione della lezione
         * ------------------------------------------------
         * 
         * NB: Viene utilizzato l'ordine in caso in cui due date
         * coincidano, rendendo impossibile stabilire quale fosse
         * stato creato per primo. Gli id sono un metodo per stabilire
         * facilmente questa cosa.
         *
         */
        $lessons = $db->perform_query(
            "select Lessons.article as article_id, Article.title as article_title, Lessons.nlesson as nlesson, " .
            "Lessons.subject as subject_id, Subject.name as subject_name, " .
            "Article.publishment_date as date from Lessons " .
            "inner join Subject on Lessons.subject = Subject.id " .
            "inner join Article on Lessons.article = Article.id " .
            "where Article.author = ? " .
            "order by publishment_date DESC, Article.id DESC limit 5",
            $get_pars['user']
        );

        //Ultime 5 News fatte dall'utente
        $news = $db->perform_query(
            "select id as article_id, title as article_title, " .
            "publishment_date as date " .
            "from News where author = ? " .
            "order by publishment_date DESC, id DESC limit 5",
            $get_pars['user']);

        $db->close_db();

        ?>
    
        <table>
            <tbody>                
                <tr>
                    <td><img src = "<?php print $user['avatar']; ?>" width = "100" height = "100" alt="user avatar" align="absmiddle"/></td>
                
                
                    <td>
                        <table>
                            <tbody>                
                                <tr>
                                    <td><b>Nickname:</b></td>
                                    <td><?php print $user['nickname']; ?></td>
                                </tr>
                
                                <tr>
                                    <td><b>Nome:</b></td>
                                    <td><?php print $user['name']; ?></td>
                                </tr>
                
                                <tr>
                                    <td><b>Cognome:</b></td>
                                    <td><?php print $user['surname']; ?></td>
                                </tr>
                
                                <tr>
                                    <td><b>Utente:</b></td>
                                    <td><?php print $user['type']; ?></td>
                                </tr>
                
                                <tr>
                                    <td><b>Data di registrazione:</b></td>
                                    <td><?php print showDate($user['registration_date']); ?></td>
                                </tr>

                                <tr>
                                    <td><b>Data di nascita:</b></td>
                                    <td><?php print showDate($user['birth_date']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    
                    
                    <!--
                        Se l'utente è loggato e sta guardando il suo stesso
                        profilo, dagli la possibilità di modificarlo aggiungendo un tasto
                    -->
                    <?php
                    if ($sess->userDataExist() && $user['id'] == $sess->getUserId()):
                    ?>
                    <td>
			<a href="/users/form_profile.html.php"><button>Modifica profilo</button></a>
                    </td>
                        
                    <?php
                    if ($user['type'] === 'user'):
                    ?>
                    
                    <td>
			<form action="/users/action_users.php?action=promote"  method="post" >
                            <input type="hidden" name="userid" value='<?php print $user['id'];?>'/>
                            <input type="submit" value = "Promuovi"/>
                        </form>
                    </td>
                    
                    <?php
                    endif;
                    endif;
                    ?>
                </tr>
            </tbody>
        </table>

        <p>
            <b>Descrizione</b>
            <br>
            <?php print $user['description']; ?>
	</p>
     
                    

	<div>
            <h3>Ultime mie 5 News:</h3>

            <?php
            
            for ($i = 0; $i < count($news); $i++):
                $arttitle = $news[$i]['article_title'];
                $artid = $news[$i]['article_id'];        
                $artdate = $news[$i]['date'];

            ?>

            <ul>
                <li>
                    Pubblicata il <?php print showDate($artdate); ?>:
                    <br>
                    <a href = "/articles/post_page.html.php?article=<?php print $artid; ?>"><?php print $arttitle ?></a>
                </li>
            </ul>
            
            <?php
                endfor;
            ?>
	</div>


        <div>
            <h3>Ultime mie 5 lezioni:</h3>

            <?php

            for ($i = 0; $i < count($lessons); $i++):
                $subjid = $lessons[$i]['subject_id'];
                $subjname = $lessons[$i]['subject_name'];
                $arttitle = $lessons[$i]['article_title'];
                $artid = $lessons[$i]['article_id'];
                $nlesson = $lessons[$i]['nlesson'];
                $lessdate = $lessons[$i]['date'];

            ?>

            <ul>  
	        <li>
                    Pubblicata il <?php print showDate($lessdate); ?>:
                    <br>
                    Lezione <?php print $nlesson; ?>: <a href = "/articles/post_page.html.php?article=<?php print $artid ?>"><?php print $arttitle; ?></a>
                    <br>
                    Materia: <a href = "/subjects/lessons/lessons_page.html.php?subject=<?php print $subjid; ?>"><?php print $subjname;?></a>
                </li>
            </ul>                    
                            
            <?php
            endfor;
            ?>
        </div>
    </body>
</html>

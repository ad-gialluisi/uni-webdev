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





require_once($_SERVER['DOCUMENT_ROOT'] . "/base_classes/CustomSessionHandler.php");


$sess = new CustomSessionHandler();

/*
 * Questa prima parte controlla che ci siano i dati dell'utente
 * Se sì, vengono mostrate le opzioni riservate agli utenti registrati
 */
?>


<!-- Create this thing to the right -->
<section id = "accountopts">

    <?php

    if ($sess->userDataExist()):
        $userid = $sess->getUserId();
        $usernickname = $sess->getUserNickname();
        $useravatar = $sess->getUserAvatar();
        $usertype = $sess->getUserType();

    ?>

    <a href = "/users/profile_page.html.php?user=<?php print $userid; ?>"><img src = "<?php print $useravatar; ?>" align = "absmiddle" width = "30" height = "30" alt="user avatar"/></a>
    <a href = "/users/profile_page.html.php?user=<?php print $userid; ?>"><?php print $usernickname; ?></a>
    
    <a href = "/articles/action_post_content.php?action=new_post&type=lesson">Nuova lezione</a>
    <a href = "/articles/action_post_content.php?action=new_post&type=news">Nuova news</a>

    <!--
        Se l'utente è amministratore mostra l'opzione per creare una nuova materia
    -->
    <?php
    if ($usertype == "admin"):
    ?>
        <a href = "/subjects/action_subject_content.php?action=new_subject">Nuova materia</a>
    <?php
    endif;
    ?>

    <a href = "/users/action_users.php?action=logout">Logout</a>


    <!--
    Quest'altra parte serve in caso un utente non registrato visiti la pagina
    -->
    <?php else: ?>
    <a href = "/users/form_subscribe.html.php">Iscriviti</a>
    <a href = "/users/form_login.html.php">Login</a>


    <?php endif; ?>


</section>

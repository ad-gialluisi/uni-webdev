<?php

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

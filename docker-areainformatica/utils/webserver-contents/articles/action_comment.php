<?php

/*
 *  Questo file viene sfruttato per effettuare operazioni
 *  relative ai commenti, nello specifico:
 *
 *  Creazione commento
 *  Rimozione commento
 */


//Includi alcuni file utili
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/CustomSessionHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");


//Roba di routine
$sess = new CustomSessionHandler();
$db = new DatabaseHandler();

//Otteniamo i parametri passati per post
$post_pars = filter_input_array(INPUT_POST);


/*
 * Per accedere a questa pagina è necessario essere autenticati ed è necessario
 * avere i parametri di tipo post.
 * Se una di queste non viene verificata, redirezioniamo l'utente alla home.
 * 
 * NB: è possibile migliorare il controllo, lascio carta bianca.
 *
 */
if (!$sess->userDataExist() || !$post_pars) {
    $sess->redirect("/");
}

/*
 * Se abbiamo deciso di creare un commento
 */
if (isset($post_pars['create'])) {
    //Accertiamoci che il commento non sia vuoto
    if ($post_pars['comment_editor'] || $post_pars['comment_editor'] === "0") {
        
        $db->open_db();
        $db->perform_query(
            "insert into Comment (article, author, content) values (?, ?, ?)",
            $post_pars['article_id'],
            $sess->getUserId(),
            $post_pars['comment_editor']);
        $db->close_db();
        
    //Se sì, segnala, non possiamo pubblicare commenti vuoti
    } else {
        $sess->addError("Errore: non è possibile creare un commento vuoto!");
        
    }
    

/*
 * Se abbiamo deciso di eliminare un commento
 */
} else if (isset($post_pars['remove'])) {
    $db->open_db();
    $db->perform_query(
        "delete from Comment where id = ?",
        $post_pars['comment_id']);
    $db->close_db();

}

//Comunque sia, ritorna alla pagina dell'articolo
$sess->redirect("/articles/post_page.html.php?article=" . $post_pars['article_id']);



<!--
Questo file viene utilizzato per eseguire tutte le operazioni
riguardante l'autenticazione degli utenti, in particolare:
    Registrazione
    Login
    Logout
    Aggiornamento del profilo
-->

<?php

require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/UploadHandler.php");



$sess = new FormSessionHandler();


$get_pars = filter_input_array(INPUT_GET);


//Verifichiamo l'integrità del parametro action
//Se il parametro non è valido redireziona
if (!$get_pars || !isset($get_pars['action'])) {
    $sess->redirect("/");
}


if ($get_pars['action'] != 'login' &&
    $get_pars['action'] != 'logout' &&
    $get_pars['action'] != 'subscribe' &&
    $get_pars['action'] != 'update_profile' &&
    $get_pars['action'] != 'promote') {
    $sess->redirect("/");
}
    


$post_pars = filter_input_array(INPUT_POST);


    /*
     * In caso si scelga di fare login
     */
if ($get_pars['action'] == "login") {
    //Verifichiamo che i dati utente non esistano
    //Non ha molto senso effettuare il login quando
    //SIAMO LOGGATI no?
    //Solito controllo dei parametri POST
    if ($sess->userDataExist() || !$post_pars) {
        $sess->redirect("/");
    }


    //Impostiamo le informazioni nel form
    $sess->setFormNickname($post_pars['nickname']);
    $sess->setFormCurrPassword($post_pars['curr_password']);

    try {
        //Validiamo il form
        $sess->validateForm();

        //Preleviamo le informazioni dal database
        $db = new DatabaseHandler();
        $db->open_db();

        $resultSet = $db->perform_query(
            "select id, type, password, avatar from User where nickname = ?", $post_pars['nickname']);

        //Se l'utente specificato non esiste, segnala
        if (count($resultSet) == 0) {
            throw new InvalidInsertedDataException("Nickname o password non validi.");
        }
                    
        //Segnala se la password è sbagliata
        if (!FormSessionHandler::passwordMatches($post_pars['curr_password'], $resultSet[0]['password'])) {
            throw new InvalidInsertedDataException("Nickname o password non validi.");
        }

        //Effettua un re-hash della password e re-immagazzinalo
        $hash = FormSessionHandler::encodePassword($post_pars['curr_password']);
        $db->perform_query(
            "update User set password = ? where nickname = ?", $hash, $post_pars['nickname']);
        $db->close_db();

            
        //Effettua il login vero e proprio
        $sess->setUserId($resultSet[0]['id']);
        $sess->setUserType($resultSet[0]['type']);
        $sess->setUserNickname($post_pars['nickname']);
        $sess->setUserAvatar($resultSet[0]['avatar']);
        $sess->redirect("/");

    } catch (InvalidInsertedDataException $excp) {
        $sess->addError($excp->getErrors());

        $sess->redirect("/users/form_login.html.php");
    }

    /*
     * In caso si scelga di fare logout
     */
} else if ($get_pars['action'] == "logout") {
        
    //Al contrario qui serve che i dati
    //SIANO presenti. In caso contrario redireziona.
    if (!$sess->userDataExist()) {
        $sess->redirect("/");
    }

    /*
     * Con questa istruzione è possibile
     * eliminare tutte le variabili di sessione
     */
    $sess->destroy();
    $sess->redirect("/");

    /*
     * In caso si scelga di fare un'iscrizione
     */
} else if ($get_pars['action'] == "subscribe") {

    //Controlliamo che i dati non esistano, se no segnaliamo
    //Verifichiamo la presenza dei parametri POST
    if ($sess->userDataExist() || !$post_pars) {
        $sess->redirect("/");
    }


    //Impostiamo i valori inseriti in memoria
    $sess->setFormName($post_pars['name']);
    $sess->setFormSurname($post_pars['surname']);
    $sess->setFormEmail($post_pars['email']);
    $sess->setFormNickname($post_pars['nickname']);
    $sess->setFormYear($post_pars['date_year']);
    $sess->setFormMonth($post_pars['date_month']);
    $sess->setFormDay($post_pars['date_day']);
    $sess->setFormNewPassword($post_pars['new_password']);
    $sess->setFormConfPassword($post_pars['new_password_conf']);

        
    try {
        //Validiamo
        $sess->validateForm();

        $db = new DatabaseHandler();
        $db->open_db();

        $resultSet = $db->perform_query("select * from User where nickname = ?", $sess->getFormNickname());

        //Se il nickname esiste già segnala
        //Il nickname è univoco
        if (count($resultSet) > 0) {
            throw new InvalidInsertedDataException("Il nickname '" . $sess->getFormNickname() . "' è già in uso.");
        }

        //Se le due password inserite sono diverse, segnala
        if ($post_pars['new_password'] != $post_pars['new_password_conf']) {
            throw new InvalidInsertedDataException("Le due password non corrispondono.");
        }
        
        //Creiamo l'hash della password e registriamo l'utente
        $hash = FormSessionHandler::encodePassword($post_pars['new_password']);

        $db->perform_query("insert into User(name, surname, password, birth_date, nickname, email, avatar, type, registration_date) values (?, ?, ?, ?, ?, ?, ?, ?, CURDATE())",
            $sess->getFormName(),
            $sess->getFormSurname(),
            $hash,
            sprintf("%04d-%02d-%02d", $sess->getFormYear(),
                    $sess->getFormMonth(),
                    $sess->getFormDay()),
            $sess->getFormNickname(),
            $sess->getFormEmail(),
            "/avatars/default_avatar.png", "user");

        $db->close_db();

        $sess->destroyFormData();
        $sess->redirect("/");

    } catch (InvalidInsertedDataException $excp) {
        $sess->addError($excp->getErrors());
        $sess->redirect("/users/form_subscribe.html.php");

    }


    /*
     * Questa sezione è per quando si decida di fare
     * un aggiornamento del profilo personale
     */
} else if ($get_pars['action'] == "update_profile") {
        
    //Solito check di integrità
    if (!$sess->userDataExist() || !$post_pars) {
        $sess->redirect("/");
    }



    $db = new DatabaseHandler();


    if (isset($post_pars['upload_avatar'])) {
        //Se abbiamo scelto di aggiornare l'avatar

        $uploadhandler = new UploadHandler($_SERVER['DOCUMENT_ROOT'] . "/avatars/");
        $uploadhandler->addMime("image");
        $uploadhandler->setNewFileName($sess->getUserId());
        $uploadhandler->validateAndMove();

        if ($uploadhandler->isFileAvailable()) {
            //Impostiamo in memoria il nuovo avatar
            $sess->setFormAvatar("/avatars/" . $uploadhandler->getFileName());

            //Memorizziamolo
            $db->open_db();
            $db->perform_query("update User set avatar = ? where id = ?", $sess->getFormAvatar(), $sess->getUserId());
            $db->close_db();

            $sess->setUserAvatar($sess->getFormAvatar());
        }

        $sess->redirect("/users/form_profile.html.php");


    } else if (isset($post_pars['update_profile'])) {
        //Se abbiamo scelto di aggiornare il profilo in genere

        //Aggiorniamo la descrizione
        $sess->setFormDescription($post_pars['description']);
        $sess->validateForm();
        
        $db->open_db();
        
        $db->perform_query("update User set description = ? where id = ?", $post_pars['description'], $sess->getUserId());
        
        $db->close_db();


        //Verifichiamo le password
        $empty_password = FALSE;

        //Come convenzione ho impostato che se i campi password
        //rimangono vuoti significa che l'utente non vuole cambiare
        //password
        if ($post_pars['curr_password'] === $post_pars['new_password'] &&
            $post_pars['new_password'] === $post_pars['new_password_conf'] &&
            $post_pars['new_password_conf'] === "") {

            //Redireziona, se sono vuote non c'è più nulla da fare
            $sess->redirect("/users/profile_page.html.php?user=" . $sess->getUserId());
        }


        try {
            //Al contrario invece, si inseriscono le password,
            //Le si validano e si aggiornano se tutto va bene

            $message = "";
                
            $sess->setFormCurrPassword($post_pars['curr_password']);
            $sess->setFormNewPassword($post_pars['new_password']);
            $sess->setFormConfPassword($post_pars['new_password_conf']);
                
            $sess->validateForm();
                
            //Ottieni password dell'utente memorizzata nel database
            $db->open_db();
            $resultSet = $db->perform_query(
                "select password from User where id = ?",
                $sess->getUserId());
            $db->close_db();


            //Confrontala, se non è la stessa, segnala
            if (!FormSessionHandler::passwordMatches($post_pars['curr_password'], $resultSet[0]['password'])) {
                throw new InvalidInsertedDataException("La password specificata non è la tua password.");
            }

            //Se i due campi password sono uguali effettua l'hash della password e memorizzala
            if ($post_pars['new_password'] === $post_pars['new_password_conf']) {
                $hash = FormSessionHandler::encodePassword($post_pars['new_password']);
                $db->open_db();
                $db->perform_query("update User set password = ? where id = ?", $hash, $sess->getUserId());
                $db->close_db();
                
            //In caso contrario segnala l'errore
            } else {
                throw new InvalidInsertedDataException("Le nuove password non corrispondono.");
            }

            //Finito, redireziona
            $sess->redirect("/users/profile_page.html.php?user=" . $sess->getUserId());

        } catch (InvalidInsertedDataException $excp) {
            $message = $excp->getMessage();

        } catch (UploadFailureException $excp) {
            $message = $excp->getMessage();

        }
                    

        if ($message) {
            $sess->addError($message);
            $sess->redirect("/users/form_profile.html.php");
        }
    }
} else if ($get_pars['action'] === 'promote') {
    $db = new DatabaseHandler();
    $db->open_db();
    
    $db->perform_query("update User set type = ? where id = ?", 'admin', $post_pars['userid']);
    $db->close_db();

    $sess->setUserType("admin");
    
    $sess->redirect("/users/profile_page.html.php?user=" . $post_pars['userid']);
}


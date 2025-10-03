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





/*
 * Questo file è utilizzato per eseguire tutte le operazioni
 * riguardanti le materie
 */


require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");


/*
 * Faccio notare che è necessario redirezionare se accade una
 * delle seguenti cose:
 * -Se un utente non è loggato
 * -Se è loggato ma è un utente NON amministratore
 */
$sess = new FormSessionHandler();

if (!$sess->userDataExist() || !$sess->getUserType() == "admin") {
    $sess->redirect("/");
}


$get_pars = filter_input_array(INPUT_GET);
$post_pars = filter_input_array(INPUT_POST);


/*
 * Redireziona in caso di dati non validi
 */
if (!$get_pars || !isset($get_pars['action'])) {
    $sess->redirect("/");
}

if ($get_pars['action'] != 'new_subject' &&
        $get_pars['action'] != 'edit_subject' &&
        $get_pars['action'] != 'update_subject') {
    $sess->redirect("/");
}



/*
 * In caso si sceglie di creare una nuova
 * materia
 */
if ($get_pars['action'] == "new_subject") {
    $sess->setFormMode("new");
    
    $sess->setFormTitle("");
    $sess->setFormDescription("");
    
    $sess->redirect("/subjects/form_subject.html.php");
    
    
/*
 * In caso si sceglie di modificare una materia
 * modifica = modifica o cancellazione
 */
} else if ($get_pars['action'] == "edit_subject") {
    /*
     * Controlla i dati mandati via POST
     */
    if (!$post_pars) {
        $sess->redirect("/");
    }

    /*
     * Imposta i dati passati via POST
     */
    $sess->setFormId($post_pars['subject_id']);
    $sess->setFormTitle($post_pars['subject_name']);
    $sess->setFormDescription($post_pars['subject_description']);

    //Qui in caso si voglia modificare
    if (isset($post_pars['edit'])) {
        $sess->setFormMode("edit");
        $sess->redirect("/subjects/form_subject.html.php");

    //Qui in caso si voglia cancellare
    } else if (isset($post_pars['remove'])) {
        $sess->setFormMode("remove");
        $sess->redirect("/subjects/subject_remove_dialog.html.php");

    }

/*
 * Questo avviene quando si conferma la modifica di una materia
 * modifica = creazione o modifica o rimozione
 */
} else if ($get_pars['action'] == "update_subject") {
    $db = new DatabaseHandler();


    try {
        //Inserimento di una nuova materia
        if ($sess->getFormMode() == "new") {
            $sess->setFormTitle($post_pars['subject_name']);
            $sess->setFormDescription($post_pars['subject_description']);
            $sess->validateForm();

            $db->open_db();
            $db->perform_query("insert into Subject(name, description) values (?, ?)",
                $sess->getFormTitle(),
                $sess->getFormDescription());
            $db->close_db();
        
        //Modifica
        } else if ($sess->getFormMode() == "edit") {
            $sess->setFormTitle($post_pars['subject_name']);
            $sess->setFormDescription($post_pars['subject_description']);

            $sess->validateForm();

            $db->open_db();
            $db->perform_query("update Subject set name = ?, description = ? where id = ?",
                $sess->getFormTitle(),
                $sess->getFormDescription(),
                $sess->getFormId());
            $db->close_db();

        //Rimozione
        } else if ($sess->getFormMode() == "remove") {
            $db->open_db();

            //Prima di tutto cancella tutti gli articoli appartenenti
            //alla materia...
            //Article è scritto subito dopo delete, perché altrimenti
            //delete non capisce DA DOVE deve cancellare...
            $db->start_transaction();
            
            $db->perform_query("delete Article from Article, Lessons where article = id and subject = ?",
                    $sess->getFormId());

            //...cancella la materia
            $db->perform_query("delete from Subject where id = ?",
                $sess->getFormId());

            $db->commit_transaction();
            
            $db->close_db();
        }

        $sess->destroyFormData();
        $sess->redirect("/subjects/subjects_list_page.html.php");


    } catch (InvalidInsertedDataException $excp) {
        $sess->addError($excp->getErrors());
        $sess->redirect("/subjects/form_subject.html.php");

    }
}

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
 * riguardanti i post
 */

require($_SERVER['DOCUMENT_ROOT'] . "/articles/PostSessionHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/UploadHandler.php");

/*
 * Redireziona in caso di 
 * utente non loggato
 */
$sess = new PostSessionHandler();
if (!$sess->userDataExist()) {
    $sess->redirect("/");
}


$get_pars = filter_input_array(INPUT_GET);
$post_pars = filter_input_array(INPUT_POST);
$db = new DatabaseHandler();


/*
 * Verifica integrità parametro action
 */
if (!$get_pars || !isset($get_pars['action'])) {
    $sess->redirect("/");
}

if ($get_pars['action'] != 'new_post' &&
        $get_pars['action'] != 'edit_post' &&
        $get_pars['action'] != 'update_post') {
    $sess->redirect("/");
}

/*
 * Verifica integrità parametro type
 */
if ($get_pars['type'] != 'lesson' &&
        $get_pars['type'] != 'news') {
    $sess->redirect("/");
}



/*
 * Questa sezione è per quanto scegliamo
 * "Crea nuovo post"
 */
if ($get_pars['action'] == "new_post") {
    $sess->setFormMode("new");
    $sess->setFormType($get_pars['type']);

    //Refresh
    $sess->setFormTitle("");
    $sess->setFormContent("");
    $sess->destroyTagData();
    
    $sess->redirect("/articles/form_post_editor.html.php");



/*
 * Questa sezione è per quando scegliamo
 * di modificare un post.
 * NB: modificare = modificare o cancellare
 */
} else if ($get_pars['action'] == "edit_post") {
    /*
     * Controlla la presenza dei parametri POST
     */
    if (!$post_pars) {
        $sess->redirect("/");
    }
    
    
    /*
     * Qui, scegliamo di modificare il post
     */
    if (isset($post_pars['edit'])) {
        $sess->setFormMode("edit");
        $sess->setFormType($get_pars['type']);
        $sess->setFormId($post_pars['article_id']);
        
        //Se il post è una lezione, aggiungi l'informazione
        //sull'id della materia
        if ($sess->getFormType() == "lesson") {
            $sess->setFormSubject($post_pars['subject_id']);
        }

        //Immagazzina i dati dell'articolo in memoria
        $db->open_db();
        $resultSet = $db->perform_query(
            "select title, content from Article where id = ?",
                $sess->getFormId());

        $sess->setFormTitle($resultSet[0]['title']);
        $sess->setFormContent($resultSet[0]['content']);


        //Immagazzina i dati relativi ai tag dell'articolo in memoria
        $resultSet = $db->perform_query(
            "select tag, name from ArticleTags, Tag where id = tag and article = ?",
                $sess->getFormId());
        $db->close_db();

        for($i = 0; $i < count($resultSet); $i++) {
            $sess->addTag($resultSet[$i]['tag'], $resultSet[$i]['name']);
        }

        
        //Fatto ciò, vai all'editor
        $sess->redirect("/articles/form_post_editor.html.php");
        
    /*
     * Qui, scegliamo di rimuovere il post
     */
    } else if (isset($post_pars['remove'])) {
        $sess->setFormMode("remove");
        $sess->setFormType($get_pars['type']);
        $sess->setFormId($post_pars['article_id']);
        $sess->setFormTitle($post_pars['article_title']);

        //Se il post è una lezione, aggiungi l'informazione
        //sull'id della materia
        if ($sess->getFormType() == "lesson") {
            $sess->setFormSubject($post_pars['subject_id']);
        }

        //Fatto ciò, vai al dialog di rimozione
        $sess->redirect("/articles/post_remove_dialog.html.php");
    }
    
    

/*
 * Questa sezione è per quando confermiamo
 * le modifiche al post.
 * NB: modifiche = creazione o modifica o cancellazione
 * (e anche altro, continua a leggere il codice)
 */
} else if ($get_pars['action'] == "update_post") {
    /*
     * Verifica i parametri POST
     */
    if (!$post_pars) {
        $sess->redirect("/");
    }
    
    //$sess->dump();

    /*
     * Salva i contenuti modificati:
     * NB: Questo non può essere fatto quando la modalità
     * è "remove" poiché "title_editor" e "content_editor" non
     * sono disponibili.
     */
    if ($sess->getFormMode() != "remove") {
        $sess->setFormTitle($post_pars['title_editor']);
        $sess->setFormContent($post_pars['content_editor']);
    }

    /*
     * Se il post è una lezione, e questa è nuova
     * dobbiamo passare anche le informazioni sulla materia...
     */
    if ($sess->getFormType() == "lesson" && $sess->getFormMode() == "new") {
        $sess->setFormSubject($post_pars['subject_id']);
    }


    /*
     * Qui, confermiamo le modifiche al post
     * NB: modifiche = creazione o modifica o cancellazione
     */
    if (isset($post_pars['post_upload'])) {
        try {
            //Valida il form
            $sess->validateForm();

            
            if ($sess->getFormMode() == "new") {

                //Se l'articolo è nuovo
                $db->open_db();
                $db->start_transaction();
                $db->perform_query("insert into Article(author, type, publishment_date, title, content) values (?, ?, CURDATE(), ?, ?)",
                    $sess->getUserId(),
                    $sess->getFormType(),
                    $sess->getFormTitle(),
                    $sess->getFormContent());

                //Salva i tag
                $resultSet = $db->perform_query("select LAST_INSERT_ID()");
                $sess->setFormId($resultSet[0]['LAST_INSERT_ID()']);

                for($i = 0; $i < $sess->getTagNumber(); $i++) {
                    $db->perform_query("insert into ArticleTags (article, tag) values (?, ?)",
                        $sess->getFormId(),
                        $sess->getTagIdAt($i));
                }


                //Se è una lezione aggiungi un record al riguardo
                if ($sess->getFormType() == "lesson") {
                    /**
                     * In questa query chiedo di inserire nlesson in base
                     * al numero di record presenti + 1
                     * Questo è un modo per ottenere un numero ordinale
                     */
                    $db->perform_query(
                        "insert into Lessons(subject, article, nlesson) select ?, ?, count(nlesson) + 1 from Lessons where subject = ?",
                        $sess->getFormSubject(),
                        $sess->getFormId(),
                        $sess->getFormSubject());

                    $ref_page = "/subjects/lessons/lessons_page.html.php?subject=" . $sess->getFormSubject();
                } else {
                    $ref_page = "/"; //CHANGE TO A BLOG PAGE, SET TO ROOT AS TMP
                }

                $db->commit_transaction();
                $db->close_db();
                //Destroy the form data and redirect to $ref_page
                $sess->destroyFormData();
                $sess->redirect($ref_page);



            } else if ($sess->getFormMode() == "edit") {
                //Se l'articolo è una modifica

                $db->open_db();
                
                $db->start_transaction();
                
                $db->perform_query("update Article set title = ?, content = ? where id = ?",
                    $sess->getFormTitle(),
                    $sess->getFormContent(),
                    $sess->getFormId());

                /*
                 * Salva i tag:
                 * Dapprima eliminando tutti i tag attuali
                 * per questo post...
                 */
                $resultSet = $db->perform_query("delete from ArticleTags where article = ?",
                        $sess->getFormId());

                /*
                 * ...e inserendo i nuovi
                 */
                for($i = 0; $i < $sess->getTagNumber(); $i++) {
                    $db->perform_query("insert into ArticleTags (article, tag) values (?, ?)",
                        $sess->getFormId(),
                        $sess->getTagIdAt($i));
                }


                if ($sess->getFormType() == "lesson") {
                    $ref_page = "/subjects/lessons/lessons_page.html.php?subject=" . $sess->getFormSubject();

                } else {
                    $ref_page = "/other/blogindex.html.php";
                }

                $db->commit_transaction();
                $db->close_db();

                $sess->destroyFormData();
                $sess->redirect($ref_page);


            } else if ($sess->getFormMode() == "remove") {
                //Se l'articolo è una cancellazione

                $db->open_db();

                //Se l'articolo è una lezione, ottieni le informazioni
                //sul numero di lezione
                if ($sess->getFormType() == "lesson") {
                    $resultSet = $db->perform_query(
                        "select nlesson, subject from Lessons, Article where id = article and article = ?",
                        $sess->getFormId());
                }


                $db->start_transaction();
                
                //Cancella l'articolo...
                $db->perform_query("delete from Article where id = ?",
                    $sess->getFormId());

                //...Ora che la lezione non esiste più, bisogna
                //aggiustare gli indici
                if ($sess->getFormType() == "lesson") {
                    $db->perform_query(
                        "update Lessons set nlesson = nlesson - 1 where subject = ? and nlesson > ?",
                        $resultSet[0]['subject'],
                        $resultSet[0]['nlesson']);
                    
                    $ref_page = "/subjects/lessons/lessons_page.html.php?subject=" . $sess->getFormSubject();

                } else {
                    $ref_page = "/other/blogindex.html.php";
                }
                
                $db->commit_transaction();
                $db->close_db();
                $sess->destroyFormData();
                $sess->redirect($ref_page);
            }
        } catch (InvalidInsertedDataException $excp) {
            $sess->addError($excp->getErrors());
            $sess->redirect("/articles/form_post_editor.html.php");
        }
        
        
        
    /**
     * Qui si effettua l'upload di un file
     * (Leggere le informazioni sulla classe UploadHandler
     * per saperne di più).
     */
    } else if (isset($post_pars['file_upload'])) {
        try {
            $uploadhandler = new UploadHandler($_SERVER['DOCUMENT_ROOT'] . "/uploads/");
            $uploadhandler->validateAndMove();

        } catch (UploadFailureException $excp) {
            $sess->addError($excp->getMessage());

        }

        $sess->redirect("/articles/form_post_editor.html.php");

        
        
   /*
    * QUESTA SEZIONE RIGUARDA TUTTE LE AZIONI SUI TAG
    * CREAZIONE, DISTRUZIONE, AGGIUNTA, RIMOZIONE
    */
        
    //Qui, è dove viene creato il tag (e inserito nel database)
    } else if (isset($post_pars['create_tag'])) {
        try {
            PostSessionHandler::validateTag($post_pars['tag_name']);

            $db->open_db();
            $db->perform_query("insert into Tag(name) values (?)", $post_pars['tag_name']);
            $db->close_db();

        } catch (InvalidInsertedDataException $excp) {
            $sess->addError($excp->getMessage());

        }

        $sess->redirect("/articles/form_post_editor.html.php");

    //Qui, è dove viene eliminato il tag (dal database)
    } else if (isset($post_pars['delete_tag'])) {
        $db->open_db();
        $db->perform_query("delete from Tag where id = ?", $post_pars['delete_tag']);
        $db->close_db();

        //Distruggi eventuali tag cancellati dal database ma presenti in memoria
        $sess->removeTag($post_pars['delete_tag']);

        $sess->redirect("/articles/form_post_editor.html.php");

    //Qui, è dove viene associato un tag all'articolo
    } else if (isset($post_pars['add_tag'])) {
        $db->open_db();
        $result = $db->perform_query("select name from Tag where id = ?", $post_pars['add_tag']);
        $result = $result[0];
        $db->close_db();

        $sess->addTag($post_pars['add_tag'], $result['name']);

        $sess->redirect("/articles/form_post_editor.html.php");

    //Qui, è dove viene disassociato un tag dall'articolo
    }  else if (isset($post_pars['remove_tag'])) {
        $sess->removeTag($post_pars['remove_tag']);

        $sess->redirect("/articles/form_post_editor.html.php");

    } else if (isset($post_pars['savingcontent'])) {
        $sess->setFormTitle($post_pars['title_editor']);
        $sess->setFormContent($post_pars['content_editor']);

        $sess->redirect("/articles/form_post_editor.html.php");
    }


        
        
}

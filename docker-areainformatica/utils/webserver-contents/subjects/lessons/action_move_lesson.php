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
 * riguardanti lo spostamento delle lezioni
 */

require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/CustomSessionHandler.php");
require($_SERVER['DOCUMENT_ROOT'] . "/base_classes/DatabaseHandler.php");

/*
 * Redireziona se l'utente non è loggato e non è amministratore
 */
$sess = new CustomSessionHandler();
if (!$sess->userDataExist() || !$sess->getUserType() == "admin") {
    $sess->redirect("/");
}

$post_pars = filter_input_array(INPUT_POST);


//Controllo integrità parametro "article_id"
if (!$post_pars || !isset($post_pars['article_id'])) {
    $sess->redirect("/");
}



/*
 * L'obiettivo qui è cercare di "spostare"
 * le lezioni (a livello di indice) di un unità alla volta.
 * 
 * 
 * Ad esempio, abbiamo la materia Programmazione con
 * le seguenti lezioni:
 * 
 * 1) Hello world
 * 2) Variabili
 * 3) Strutture di controllo
 * ----------------------------------------
 * 
 * Queste operazioni permette di cambiare l'indice di una lezione.
 * Ad esempio, spostando Hello World dalla Lezione 1 alla Lezione 2
 * e facendo diventare Variabili la prima lezione.
 * 
 * 1) Variabili
 * 2) Hello world
 * 3) Strutture di controllo
 * 
 */

$db = new DatabaseHandler();
$db->open_db();

$db->start_transaction();

/*
 * Purtroppo, l'unica soluzione che ho riscontrato e che ho potuto effettuare
 * è descritta in questo post del noto sito StackOverflow:
 * http://stackoverflow.com/questions/644/swap-unique-indexed-column-values-in-database
 * 
 * Sposta la lezione ad un indice superiore,
 * "down" perché le lezioni vengono mostrate
 * in ordine crescente, quindi:
 * 
 * su = valore inferiore
 * giù = valore superiore.
 */
if (isset($post_pars['lesson_move_down'])) {
    //Imposta il numero di lezione superiore a -1...
    $db->perform_query(
        "update Lessons set nlesson = -1 where subject = ? and nlesson = ? + 1",
        $post_pars['subject_id'],
        $post_pars['nlesson']);

    //...aggiungi 1 al numero di lezione scelta...
    $db->perform_query(
        "update Lessons set nlesson = ? + 1 where subject = ? and nlesson = ?",
        $post_pars['nlesson'],
        $post_pars['subject_id'],
        $post_pars['nlesson']);

    //...e alla fine, aggiorna con il numero di lezione scelta il numero che
    //prima era più grande
    $db->perform_query(
        "update Lessons set nlesson = ? where subject = ? and nlesson = -1",
        $post_pars['nlesson'],
        $post_pars['subject_id']);


/*
 * Stessa idea di prima, ma sposto la lezione ad un indice inferiore
 */
} else if (isset($post_pars['lesson_move_up'])) {
    $db->perform_query(
        "update Lessons set nlesson = -1 where subject = ? and nlesson = ? - 1",
        $post_pars['subject_id'],
        $post_pars['nlesson']);

    $db->perform_query(
        "update Lessons set nlesson = ? - 1 where subject = ? and nlesson = ?",
        $post_pars['nlesson'],
        $post_pars['subject_id'],
        $post_pars['nlesson']);

    $db->perform_query(
        "update Lessons set nlesson = ? where subject = ? and nlesson = -1",
        $post_pars['nlesson'],
        $post_pars['subject_id']);
}

$db->commit_transaction();

$db->close_db();


$sess->redirect("/subjects/lessons/lessons_page.html.php?subject=" . $post_pars['subject_id']);

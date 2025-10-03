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





require_once($_SERVER['DOCUMENT_ROOT'] . "/base_classes/FormSessionHandler.php");



/**
 * Questa classe è usata per gestire le variabili di sessione.
 * In particolare questa figlia di FormSessionHandler gestisce in maniera
 * semplice i parametri dei tag che si intende inserire in un post.
 */
class PostSessionHandler extends FormSessionHandler {
    /**
     * Lunghezza minima di un tag
     */
    const TAG_MIN_LENGTH = 1;
    

    /**
     * Purtroppo è necessario richiamare il costruttore parent
     * per poter aprire le sessioni all'istanziazione.
     * PHP non effettua la chiamata dei
     * costruttori delle superclassi.
     */
    public function __construct() {
        parent::__construct();
    }

    
    /**
     * Questo metodo effettua l'operazione
     * di validazione di un tag.
     * Da notare che il metodo è statico
     * @throws InvalidInsertedDataException
     */
    public static function validateTag($name) {
        if (strlen($name) < self::TAG_MIN_LENGTH) {
            throw new InvalidInsertedDataException("'$name' non è un tag valido.");
        }
    }
    
    
    /**
     * Restituisce l'id del tag memorizzato nell'indice $index
     */
    public function getTagIdAt($index) {
        if (isset($_SESSION['FORM']['tags']['ids'][$index])) {
            return $_SESSION['FORM']['tags']['ids'][$index];
        }
        
        return FALSE;
    }

    
    /**
     * Restituisce il nome del tag, specificato il suo id
     */
    public function getTagName($id) {
        if (isset($_SESSION['FORM']['tags'][$id])) {
            return $_SESSION['FORM']['tags'][$id];
        }

        return FALSE;
    }


    /**
     * Restituisce il numero di tag presenti in memoria
     */
    public function getTagNumber() {
        if (isset($_SESSION['FORM']['tags']['ids'])) {
            return count($_SESSION['FORM']['tags']['ids']);
        }
        
        return 0;
    }


    public function addTag($id, $name) {
        if (!isset($_SESSION['FORM']['tags'][$id])) {
            $_SESSION['FORM']['tags']['ids'][] = $id;
            $_SESSION['FORM']['tags'][$id] = $name;
        }
    }

    public function removeTag($id) {
        $key = array_search($id, $_SESSION['FORM']['tags']['ids']);

        if (is_numeric($key)) {
            array_splice($_SESSION['FORM']['tags']['ids'], $key, 1);
            unset($_SESSION['FORM']['tags'][$id]);
        }
    }
    
    public function destroyTagData() {
        unset($_SESSION['FORM']['tags']);
    }

}

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





/**
 * Classe che rappresenta l'eccezione sollevata
 * in caso di problemi con i form
 */
class InvalidInsertedDataException extends Exception {
    private $errors;
    
    
    /**
     * Costruttore:
     * è possibile costruire un messaggio di errore con un array di stringhe
     */
    public function __construct($arrayParams) {
        if (is_array($arrayParams)) {
            $this->errors = $arrayParams;
        } else {
            $this->errors = array();
            $this->errors[] = $arrayParams;
        }
    }
    
    
    public function getErrors() {
        return $this->errors;
    }


}

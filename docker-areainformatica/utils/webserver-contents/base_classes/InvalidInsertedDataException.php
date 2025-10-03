<?php

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

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
 * in caso di problemi di connessione con il database
 */
class DBConnect_Exception extends Exception {

}

/**
 * Classe che rappresenta l'eccezione sollevata
 * in caso di problemi relativi alle query
 */
class DBQuery_Exception extends Exception {

}

/**
 * Generico gestore di database
 */
class DatabaseHandler {
    /**
     * Nome del DB
     */
    const DB_NAME = 'StudyPlatform';

    /**
     * Host name
     */
    const HOST = 'localhost';

    /**
     * Utente
     */
    const USER = 'student';
    
    /**
     * Password
     */
    const PASSWORD = 'studentpassword';

    
    /**
     * Riferimento all'oggetto PDO
     * usato per effettuare operazioni
     */
    private $db_ref;
    
    
    /**
     * Variabile utile a verificare se il database ha delle transazioni
     * in corso. Se transaction_mode = true, allora siamo in una transazione.
     */
    private $transaction_mode;
    


    /**
     * Costruttore
     */
    public function __construct() {
        $this->db_ref = NULL;

    }
    
    
    /**
     * "Apre" il database
     * @throws DBConnect_Exception
     */
    public function open_db() {
        try {
            $this->db_ref = new PDO(
                    sprintf("mysql:host=%s;dbname=%s", self::HOST, self::DB_NAME),
                    self::USER, self::PASSWORD);

        } catch (PDOException $excp) {
            $this->db_ref = NULL;
            throw new DBConnect_Exception("Fallimento nell'apertura del database, la ragione è: " . $excp->getMessage());

        }
    }


    /**
     * "Chiude" il database
     * @throws DBConnect_Exception
     */
    public function close_db() {
        if ($this->db_ref == NULL) {
            throw new DBConnect_Exception("Il database non è mai stato aperto!");
        }

        $this->db_ref = null;
    }


    /**
     * Questo metodo effettua una query
     * @throws DBConnect_Exception
     * @throws DBQuery_Exception
     */
    public function perform_query() {
        if ($this->db_ref == NULL) {
            throw new DBConnect_Exception("Il database non è stato aperto!");
        }

        
        /**
         * Ecco come funziona:
         *  -Il primo parametro è la query (stringa)
         *  -I parametri successivi sono considerati parametri a cui
         *  applicare il binding (qualsiasi cosa)
         */
        for ($i = 0; $i < func_num_args(); $i++) {
            $val = func_get_arg($i);
            if ($i == 0) {
                $stmt = $this->db_ref->prepare($val);
            } else {
                $stmt->bindValue($i, $val);
            }
        }

        //Effettua la query, se c'è un errore segnala
        $result = $stmt->execute();
        if (!$result) {
            $error = $stmt->errorInfo();
            throw new DBQuery_Exception($error[2]);
        }

        //Ottieni tutti i record dell'operazione, se ce ne sono
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultSet;
    }
    
    
    /**
     * Questo metodo segnala l'inizio di una transazione
     * @throws DBConnect_Exception
     */
    public function start_transaction() {
        if ($this->db_ref == NULL) {
            throw new DBConnect_Exception("Il database non è stato aperto!");
        }
        
        
        $this->db_ref->beginTransaction();
        $this->transaction_mode = true;
    }
    
    
    /**
     * Questo metodo effettua le operazioni dettate dalla transazione
     * @throws DBQuery_Exception
     */
    public function commit_transaction() {
        if (!$this->transaction_mode) {
            throw new DBQuery_Exception("Non è stata iniziata alcuna transazione!");
        }

        $this->db_ref->commit();
        $this->transaction_mode = false;
    }
    
    
    /**
     * Questo metodo va usato in caso di errore, per tornare
     * ad un punto precedente del database, e quindi annullare
     * la transazione
     * @throws DBQuery_Exception
     */
    public function rollback_transaction() {
        if (!$this->transaction_running) {
            throw new DBQuery_Exception("Non è stata iniziata alcuna transazione!");
        }


        $this->db_ref->rollBack();
        $this->transaction_mode = false;
    }
    

}
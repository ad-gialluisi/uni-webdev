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
 * Questa classe è usata per gestire le variabili di sessione.
 * In particolare:
 *  -Le variabili relative all'utente loggato
 *  -Gli errori
 */
class CustomSessionHandler {
    /**
     * Quante chiamate attendere prima di rigenerare
     * l'id della session
     */
    const SESSION_REFRESH = 5;



    /**
     * Costruttore
     */
    public function __construct() {
        $this->session_open();
    }

    /**
     * Metodo privato usato nel costruttore
     */
    private function session_open() {
        //Se una sessione non è iniziata affatto,
        //iniziala
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }

        //Incrementa il contatore di REFRESH
        if (!isset($_SESSION['REFRESH'])) {
            $_SESSION['REFRESH'] = 0;
        }

        //Se il contatore raggiunge il massimo segnato,
        //Resetta e rigenera il l'id della sessione
        if (++$_SESSION['REFRESH'] >= self::SESSION_REFRESH) {
            $_SESSION['REFRESH'] = 0;
            session_regenerate_id(true);
        }
    }


    /**
     * *********************** *
     * *********************** *
     * *ERROR RELATED METHODS* *
     * *********************** *
     * *********************** *
     */
    /**
     * Con questo metodo è possibile aggiungere uno
     * o più errori alla variabile $_SESSION['ERROR']
     */
    public function addError($param) {
        if (is_array($param)) {
            for ($i = 0; $i < count($param); $i++) {
                $_SESSION['ERROR'][] = $param[$i];
            }
        } else {
            $_SESSION['ERROR'][] = $param;
        }
    }

    
    /**
     * Con questo invece otteniamo l'array di errori,
     * e svuotiamo la variabile $_SESSION['ERROR']
     */
    public function getError() {
        $errors = $_SESSION['ERROR'];
        $this->destroyErrorData();

        return $errors;
    }


    /**
     * Questo metodo dovrebbe servire a mostrare gli errori in una pagina
     * Cambiare l'implementazione se necessario
     */
    public function showError() {
        if (isset($_SESSION['ERROR'])) {
            print "<h3>Sono stati trovati degli errori:</h3>";
            
            print "<ul>";
            
            $list = $this->getError();
            for ($i = 0; $i < count($list); $i++) {
                print "<li>" . $list[$i] . "</li>";
            }
            print "</ul>";
        }
    }


    /**
     * Con questo metodo è possibile distruggere
     * i dati relativi agli errori
     */
    public function destroyErrorData() {
        unset($_SESSION['ERROR']);
    }


    /**
     * *********************** *
     * *********************** *
     * *USERS RELATED METHODS* *
     * *********************** *
     * *********************** *
     */
    /*
     * Metodi setters e getters,
     * c'è davvero bisogno di spiegarli?
     */
    
    
    public function setUserId($id) {
        $_SESSION['USER']['id'] = $id;
    }

    public function getUserId() {
        return $_SESSION['USER']['id'];
    }

    public function setUserNickname($nickname) {
        $_SESSION['USER']['nickname'] = $nickname;
    }

    public function getUserNickname() {
        return $_SESSION['USER']['nickname'];
    }

    public function setUserType($type) {
        $_SESSION['USER']['type'] = $type;
    }

    public function getUserType() {
        return $_SESSION['USER']['type'];
    }
    
    public function setUserAvatar($avatar) {
        $_SESSION['USER']['avatar'] = $avatar;
    }
    
    public function getUserAvatar() {
        return $_SESSION['USER']['avatar'];
    }


    /**
     * Con questo metodo è possibile distruggere
     * tutti i dati relativi all'utente
     */
    public function destroyUserData() {
        unset($_SESSION['USER']);
    }
    
    
    /**
     * Questo metodo restituisce TRUE
     * se esistono i dati dell'utente
     * (usato per verificare se un utente è loggato)
     */
    public function userDataExist() {
        return isset($_SESSION['USER']);
    }
    
    
    /**
     * Con questo metodo è possibile distruggere tutti i dati,
     * relativi agli errori e all'utente
     */
    public function destroy() {
        $this->destroyErrorData();
        $this->destroyUserData();
    }


    /**
     * Questo è uno short-hand per redirezionare l'utente
     * ad altre pagine
     */
    public function redirect($url) {
        header("location: " . $url);
        exit();
    }
    
    
    
    public function dump() {
        var_dump($_SESSION);
    }
}

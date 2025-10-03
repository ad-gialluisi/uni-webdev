<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/base_classes/InvalidInsertedDataException.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/base_classes/CustomSessionHandler.php");


/**
 * Questa classe è usata per gestire le variabili di sessione.
 * In particolare questa figlia di CustomSessionHandler gestisce in maniera
 * generica i parametri dei form.
 * Da notare che ho potuto stabilire tutti i campi necessari solo una volta
 * finite le varie sezioni.
 * Comprenderete che non è il modo ideale sfruttare una cosa
 * per tutte, ma, notavo che differenziando, il discorso diventava peggiore,
 * con codice più incasinato che mai.
 */
class FormSessionHandler extends CustomSessionHandler {
    /**
     * Lunghezza minima di un nome e cognome (altri campi hanno la stessa regola)
     */
    const NAME_SURNAME_MIN_LENGTH = 3;
    
    /**
     * Lunghezza minima di una password
     */
    const PASSWORD_MIN_LENGTH = 8;
    
    /**
     * Lunghezza massima per una descrizione
     */
    const DESCRIPTION_MAX_LENGTH = 255;




    /**
     * ********************** *
     * ********************** *
     * *FORM RELATED METHODS* *
     * ********************** *
     * ********************** *
     */
    
    /**
     * Purtroppo è necessario richiamare il costruttore parent
     * per poter aprire le sessioni all'istanziazione.
     * PHP non effettua la chiamata dei
     * costruttori delle superclassi.
     */
    public function __construct() {
        parent::__construct();
    }
    
    
    /*
     * Metodo privato generico per impostare i valori
     */
    private function setFormProperty($property, $value) {
        $_SESSION['FORM'][$property] = $value;
    }
    
    /*
     * Metodo privato generico per ottenere i valori
     * Da notare che è necessario fornire un valore di default
     */
    private function getFormProperty($property, $default="") {
        return (isset($_SESSION['FORM'][$property]) ? $_SESSION['FORM'][$property]
                                                    : $default);
    }
    
    /*
     * Questi sono i veri setter-getter per i form
     */

    public function setFormId($id) {
        $this->setFormProperty('id', $id);
    }
    
    public function getFormId() {
        return $this->getFormProperty('id');
    }
    
    public function setFormTitle($title) {
        $this->setFormProperty('title', $title);
    }
    
    public function getFormTitle() {
        return $this->getFormProperty('title');
    }
    
    public function setFormName($name) {
        $this->setFormProperty('name', $name);
    }

    public function getFormName() {
        return $this->getFormProperty('name');
    }
    
    public function setFormSurname($surname) {
        $this->setFormProperty('surname', $surname);
    }

    public function getFormSurname() {
        return $this->getFormProperty('surname');
    }

    public function setFormNickname($nickname) {
        $this->setFormProperty('nickname', $nickname);
    }

    public function getFormNickname() {
        return $this->getFormProperty('nickname');
    }

    public function setFormEmail($email) {
        $this->setFormProperty('email', $email);
    }

    public function getFormEmail() {
        return $this->getFormProperty('email');
    }
    
    public function setFormDescription($description) {
        $this->setFormProperty('description', $description);
    }

    public function getFormDescription() {
        return $this->getFormProperty('description');
    }
    
    public function setFormAvatar($avatar) {
        $this->setFormProperty('avatar', $avatar);
    }
    
    public function getFormAvatar() {
        return $this->getFormProperty('avatar');
    }
    
    public function setFormCurrPassword($password) {
        $this->setFormProperty('curr_password', $password);
    }

    public function getFormCurrPassword() {
        return $this->getFormProperty('curr_password');
    }

    public function setFormNewPassword($password) {
        $this->setFormProperty('new_password', $password);
    }

    public function getFormNewPassword() {
        return $this->getFormProperty('new_password');
    }
    
    public function setFormConfPassword($password) {
        $this->setFormProperty('conf_password', $password);
    }

    public function getFormConfPassword() {
        return $this->getFormProperty('conf_password');
    }
    
    public function setFormMode($mode) {
        $this->setFormProperty('mode', $mode);
    }
    
    public function getFormMode() {
        return $this->getFormProperty('mode');
    }
    
    public function setFormType($type) {
        $this->setFormProperty('type', $type);
    }
    
    public function getFormType() {
        return $this->getFormProperty('type');
    }
    
    public function setFormContent($content) {
        $this->setFormProperty('content', $content);
    }

    public function getFormContent() {
        return $this->getFormProperty('content');
    }
    
    public function setFormDay($day) {
        $this->setFormProperty('day', $day);
    }
    
    public function getFormDay() {
        return $this->getFormProperty('day');
    }
    
    public function setFormMonth($month) {
        $this->setFormProperty('month', $month);
    }
    
    public function getFormMonth() {
        return $this->getFormProperty('month');
    }
    
    public function setFormYear($year) {
        $this->setFormProperty('year', $year);
    }
    
    public function getFormYear() {
        return $this->getFormProperty('year');
    }

    public function setFormSubject($subject) {
        $this->setFormProperty('subject', $subject);
    }
    
    public function getFormSubject() {
        return $this->getFormProperty('subject');
    }
    
    public function setFormFirstTime($first_time) {
        $this->setFormProperty('first', $first_time);
    }

    public function isFormFirstTime() {
        return $this->getFormProperty('first', TRUE);
    }
    
    
    /**
     * Con questo metodo è possibile distruggere tutti i dati
     * del form
     */
    public function destroyFormData() {
        unset($_SESSION['FORM']);
    }


    /**
     * Con questo metodo è possibile distruggere tutti i dati
     * possibili
     */
    public function destroy() {
        parent::destroy();
        $this->destroyFormData();
    }
    
    
    /**
     * Con questo metodo è possibile verificare se sono presenti
     * dati di form in memoria
     */
    public function isFormDataAvailable() {
        return isset($_SESSION['FORM']);
    }


    /**
     * Con questo metodo effettuiamo una validazione
     * di ciò che abbiamo inserito
     * @throws InvalidInsertedDataException
     */
    public function validateForm() {
        $message = NULL;
        
        foreach ($_SESSION['FORM'] as $key => $value) {
            switch($key) {
                case 'id':
                case 'subject':
                    if (!is_numeric($value)) {
                        $message[] = ($key === 'subject' ? "La materia specificata non è valida!" : "L'id specificato non è valido!");
                    }
                break;
                
                case 'name':
                case 'surname':
                case 'nickname':
                    if ($key === 'name') {
                        $show = "Il nome";
                    } else if ($key === 'surname') {
                        $show = "Il cognome";
                    } else if ($key === 'nickname') {
                        $show = "Il nickname";
                    }


                    if (strlen($value) < self::NAME_SURNAME_MIN_LENGTH) {
                        $message[] = sprintf("%s deve contenere almeno %d caratteri.", $show, self::NAME_SURNAME_MIN_LENGTH);
                    }

                    if ($key == 'name' || $key == 'surname') {
                        if (!preg_match("/^[a-z]+$/i", $value)) {
                            $message[] = sprintf("%s deve contenere solo lettere!", $show);
                        }
                    }
                break;

                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $message[] = "L'e-mail specificata '" . $value . "' non è valida.";
                    }
                break;

                case 'description':
                    if (strlen($value) > self::DESCRIPTION_MAX_LENGTH) {
                        $message[] = sprintf("La descrizione non può eccedere i %d caratteri.", self::DESCRIPTION_MAX_LENGTH);
                    }
                break;
                
                case 'curr_password':
                case 'new_password':
                case 'conf_password':
                    if (strlen($value) < self::PASSWORD_MIN_LENGTH) {
                        $message[] = sprintf("Le password devono avere almeno %d caratteri.", self::PASSWORD_MIN_LENGTH);
                    }
                break;
            }
        }

        if (isset($_SESSION['FORM']['month']) && isset($_SESSION['FORM']['day']) && isset($_SESSION['FORM']['year'])) {
            if (!checkdate($this->getFormMonth(), $this->getFormDay(), $this->getFormYear())) {
                $message[] = "La data inserita non è valida.";
            }
        }

        if ($message != NULL) {
            throw new InvalidInsertedDataException($message);
        }
    }

    
    /**
     * Metodo statico per codificare una password
     */
    public static function encodePassword($password) {
        $cost = 10;
        $salt = substr(strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.'), 0, 22);
        $salt = sprintf("$2a$%02d$", $cost) . $salt;
        $hash = crypt($password, $salt);

        return $hash;
    }

    
    /**
     * Metodo statico per verificare se una password coincide con una
     * codificata
     */
    public static function passwordMatches($password, $hash) {
        return $hash === crypt($password, $hash);
    }
}

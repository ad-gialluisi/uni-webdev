<?php

/**
 * Classe che rappresenta l'eccezione sollevata
 * in caso di problemi con l'upload di file
 */
class UploadFailureException extends Exception {
    
}


/**
 * Generico gestore per effettuare upload dei file
 */
class UploadHandler {
    /**
     * Questa variabile conterrà la path dove inserire i file
     */
    private $uploadPath;
    
    /**
     * Questa variabile conterrà i mimes da verificare
     */
    private $mimes;
    
    /**
     * Questa variabile conterrà il nome del file caricato
     */
    private $filename;
    
    /**
     * flag indicante se un file è disponibile
     */
    private $file_available;
    
    
    private $newfilename;
    
    
    
    /**
     * Costruttore: si imposta in questa maniera
     * un percorso su dove caricare i file
     */
    public function __construct($uploadPath) {
        $this->uploadPath = $uploadPath;
        $this->mimes = array();
        $this->file_available = FALSE;
        $this->newfilename = NULL;
    }
    
    /*
     * Non è necessario spiegare questi... giusto?
     */
    public function addMime($mime) {
        $this->mimes[] = $mime;
    }
    
    public function removeMime($index) {
        unset($this->mimes[$index]);
    }
    
    public function getMime($index) {
        return ($this->mimes[$index]);
    }

    public function resetMimes() {
        $this->mimes = array();
    }

    public function isFileAvailable() {
        return $this->file_available;
    }
    
    public function getFileName() {
        return $this->filename;
    }
    
    public function setNewFileName($newfilename) {
        $this->newfilename = $newfilename;
    }
    
    
    /**
     * Questa funzione viene usata per validare il file
     * caricato e verificare se è possibile spostarlo
     * nel percorso impstato.
     * @throws UploadFailureException
     */
    public function validateAndMove() {
        /*
         * NOTA: è necessario chiamare il form "content_file"
         * per usare questa classe 
         */
        if (isset($_FILES['content_file'])) {
            $file = $_FILES['content_file'];

            
            if ($file['error'] == UPLOAD_ERR_OK) {
                if (is_uploaded_file($file['tmp_name'])) {
                    //Se non ci sono errori

                    //Controlla il mime del file
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimetype = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    /*
                     * A questo punto, verificare che il mime corrisponda
                     * ad uno di quelli specificati così da evitare
                     * upload di file indesiderati.
                     * NOTA: la lista di mime agisce da whitelist, in caso
                     * di nessuna preferenza però, TUTTI I TIPI DI FILE
                     * potranno essere
                     * caricati.
                     */
                    for ($i = 0; $i < count($this->mimes); $i++) {
                        $length = strlen($this->mimes[$i]);

                        if (substr($mimetype, 0, $length) != $this->mimes[$i]) {
                            throw new UploadFailureException("Dato di tipo " . $this->mimes[$i] . " inatteso.");
                        }
                    }

                    //Aggiorna alcune variabili
                    if ($this->newfilename == NULL) {
                        $this->filename = $file['name'];
                    } else {
                        $this->filename = $this->newfilename;
                    }

                    
                    $path = $this->uploadPath . $this->filename;
                    


                    //sposta il file
                    $res = move_uploaded_file($file['tmp_name'], $path);

                    //Se si sono verificati degli errori, riporta
                    if (!$res) {
                        throw new UploadFailureException("Problema durante lo spostamento del file.");
                    }
                    
                    //Segnala che il file è disponibile
                    $this->file_available = TRUE;
                }

            //Ora, UPLOAD_ERR_NO_FILE non è un vero e proprio errore
            //poiché significa banalmente che non è stato scelto alcun file.
            //Infatti verifichiamo che l'errore NON SIA quello.
            } else if ($file['error'] != UPLOAD_ERR_NO_FILE) {
                /*
                 * Se l'errore non è UPLOAD_ERR_NO_FILE, significa che c'è
                 * qualche errore
                 */
                
                $message = "";
                
                //Verifica qual'è e riporta un messaggio.
                if ($file['error'] == UPLOAD_ERR_INI_SIZE) {
                    $message = "File troppo grande.";

                } else if ($file['error'] == UPLOAD_ERR_INI_SIZE) {
                    $message = "Superata la dimensione massima in upload.";

                } else if ($file['error'] == UPLOAD_ERR_PARTIAL) {
                    $message = "Il file è stato caricato parzialmente";

                } else if ($file['error'] == UPLOAD_ERR_NO_TMP_DIR) {
                    $message = "Directory temporanea non disponibile.";

                }

                throw new UploadFailureException($message);
            }
        }
    }
}

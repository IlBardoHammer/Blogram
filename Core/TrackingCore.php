<?php

/**
 * Classe per effettuare il tracking degli utenti in particolare per tracciare le visualizzazioni dei post dei blog
 *
 */
class TrackingCore
{
    /**
     * Funzione che inizializza una sessione con le informazioni del client
     *
     * @return void
     */
    public static function initSession()
    {
        # Se non esiste già una sessione
        if (empty($_SESSION["tracking"])) {

            try {

                # Recupero l'ip del client cercandolo in tutte le varie possibili variabili inizializzate dal web server
                $ip = getenv('HTTP_CLIENT_IP')?:
                    getenv('HTTP_X_FORWARDED_FOR')?:
                        getenv('HTTP_X_FORWARDED')?:
                            getenv('HTTP_FORWARDED_FOR')?:
                                getenv('HTTP_FORWARDED')?:
                                    getenv('REMOTE_ADDR')?:
                                        "UNKNOWN";

                # Recupero lo user_agent del client
                $useragent = $_SERVER['HTTP_USER_AGENT'];

                # Aggiungo il visitatore al db
                $r_id = VisitorModel::addVisitor($ip, $useragent);

                # Inserisco le informazioni in sessione per non fare ulteriormente questa operazione al cambio di pagina
                $_SESSION["tracking"] = array(
                    "id" => $r_id,
                    "useragent" => $useragent,
                    "ip" => $ip
                );

            } catch (PDOException $error) {
                echo $error->getMessage();
            }
        }
    }

    /**
     * Funzione che ritorna l'array con le informazioni di tracking scritte al primo atterraggio
     *
     * @return mixed
     */
    public static function getSession()
    {
        return $_SESSION["tracking"]; # tracking è una chiave che contiene un array($id (id del visitatore), $ip, $useragent)
    }

    /**
     * Funzione che inserisce la visita di un utente su un post nel db per avere un tracking dei post (e di conseguenza i blog)
     * più visitati.
     *
     * Se l'utente è loggato viene inserito l'id dell'utente registrato.
     * Altrimenti viene inserito l'id dell'utente Guest ricavato dal visitatore creato con il tracking
     *
     * @param $id_post
     * @return void
     */
    public static function postView($id_post)
    {
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
            VisitorModel::addVisit($id_post, $_SESSION["id"]); # id dell'autore registrato e loggato nel sito
        } else {
            VisitorModel::addVisit($id_post, null, self::getSession()["id"]); # id che hai messo in sessione quando hai aggiunto la visita nel db di un visitatore
        }
    }
}
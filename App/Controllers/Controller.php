<?php

/**
 * Controller dai quali tutte le classi Controller estendono
 */
abstract class Controller
{
    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public function __construct()
    {
        # Inizializzo il tracking per ogni pagina vista dell'applicazione
        TrackingCore::initSession();
    }

    /**
     * Semplice funzione per effettuare un redirect verso un'altra pagina.
     *
     * @param $url
     * @return void
     */
    public function redirect($url='/')
    {
        header("Location: $url");
        header("Connection: close");
        exit;
    }

    /**
     * Funzione che controlla se l'utente è loggato.
     * Se non lo è lo rimanda alla pagina di login.
     *
     * Da inserire all'interno dei controller delle aree riservate come: self::is_logged();
     *
     * @param $redirect
     * @return void
     */
    public function is_logged(){
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            self::redirect("/login");
        }
    }
}
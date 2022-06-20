<?php

/**
 * Classe per la gestione della connessione al db
 */
class DbCore
{
    protected static $instance;

    # Variabili per inizializzazione connessione
    private static $default_options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    # Inializzo la connessione al db come singleton
    public static function getInstance()
    {

        # Se non ho fatto già la connessione
        if (empty(self::$instance)) {

            # Mi connetto
            self::$instance = new PDO("mysql:host=".Config::DB_HOST.";dbname=".Config::DB_NAME,
                Config::DB_USER,
                Config::DB_PASSWORD,
                self::$default_options);

            # Imposto il charset
            self::$instance->query('SET NAMES utf8');
            self::$instance->query('SET CHARACTER SET utf8');

        }

        # Ritorno l'istanza della connessione
        return self::$instance;
    }

}
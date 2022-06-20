<?php

# Avvio la sessione
session_start();

# Previene il duplice invio dei post in caso di back del browser
header("Cache-Control: no cache");

/**
 * Funzione registrata con spl_autoload_register che in automatico aggiunge il require della classe istanziata.
 * Per evitare ogni volta di dover fare il require delle classi utilizzate nei vari punti del progetto, questa funzione
 * in automatico controlla il nome della classe e la include dal rispettivo path.
 *
 * Se volessimo utilizzare la classe PostController sarebbe necessario farne il require all'inizio del file
 * dove vogliamo utilizzarla.
 *
 * Questa funzione invece deduce dal nome (tramite una regex) che è una classe di tipo "Controller" e di conseguenza ne effettua
 * il require dall'apposita sezione.
 *
 *
 * @param $class
 * @return void
 */
function autoloadFunction($class)
{
    if (preg_match('/Controller$/', $class)){
        require("App/Controllers/" . $class . ".php");
    } else if (preg_match('/Model$/', $class)){
        require("App/Models/" . $class . ".php");
    } else if (preg_match('/Core$/', $class)){
        require("Core/" . $class . ".php");
    } else {
        require("App/Utils/" . $class . ".php");
    }
}

# Registrazione della funzione callback di autoload passando come parametro il nome della classe chiamata ($class)
spl_autoload_register("autoloadFunction");

# Imposto che tutti i tipi di errore vengano notificati da PHP
error_reporting(E_ALL);

# Imposto come handler degli errori e delle eccezioni le funzioni della mia classe ErrorCore
set_error_handler('ErrorCore::errorHandler');
set_exception_handler('ErrorCore::exceptionHandler');

# Inizializzo l'oggetto per effettuare il routing delle chiamate
$router = new RouterCore();

# Effettuo il routing
$router->route();
<?php

/**
 * ErrorCore and exception handler
 *
 */
class ErrorCore
{

    /**
     * ErrorCore handler. Converte tutti gli errori in eccezioni così da poterni catturare dall'Exception handler sotto.
     *
     * @param int $level  ErrorCore level
     * @param string $message  ErrorCore message
     * @param string $file  Filename the error was raised in
     * @param int $line  Line number in the file
     *
     * @return void
     */
    # Per non far morire il programma (die()) in caso di errore solleviamo un eccezione per non farlo stoppare
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler. Cattura tutte le eccezioni e le mostra a video.
     * Per semplicità ogni eccezione diversa da 404 viene trattata come 500 (general error)
     *
     * La funzione utilizza la variabile SHOW_ERRORS di Config per differenziare la modalità di visualizzazione dell'errore.
     *
     *
     * @param Exception $exception  The exception
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        # Prendo il codice di errore generato
        $code = $exception->getCode();

        # Se il codice di errore è diverso da 404 (pagina non trovata) generalizzo a errore 500 (errore sul server generico)
        if ($code != 404) {
            $code = 500;
        }

        # Imposto il codice della risposta al client
        http_response_code($code);

        if (Config::SHOW_ERRORS) {
            # Se SHOW_ERRORS è true allora mostro l'errore a schermo con il suo stacktrace (modalità sviluppo)

            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre style='color: black;width: 100%'>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";

        } else {
            # Altrimenti presento l'errore con una pagina con UI/UX migliore

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            # Scrivo comunque l'errore nei log
            error_log($message);

            # Se è un errore 404 renderizzo il template 404
            if ($code == 404) {
                TemplateCore::view('error_404.html');
                return;
            }

            # Se è un errore 500 renderizzo il template 500
            TemplateCore::view('error_500.html');
        }
    }
}

<?php


/**
 * Semplice classe di routing.
 *
 * La classe recupera il path della url chiamata. Accoda al path la stringa Controller e chiama la funzione main della classe
 * identificata come {path}Controller.php
 *
 * Per fare un esempio:
 *
 * chiamando /blog verrà chiamata la funzione "main" della classe BlogController.php
 *
 * L'unica eccezione è "/" che viene diretto verso HomeController.php
 *
 */
class RouterCore
{

    /**
     * Funzione che effettua il routing delle chiamate
     *
     * @return void
     * @throws Exception
     */
    public function route(){

        # Prendo la url chiamata e con parse_url ricevo l'array degli elementi dalla url (es. protocol, dominio, porta, path, querystring)
        #
        # http://localhost/home?a=1&b=2
        # {
        #    "protocol" => "http",
        #    "domain" => "localhost",
        #    "path" => "/home"
        #    "query" => "a=1&b=2"
        # }
        #
        #

        $arr_path = explode('/', $_SERVER["REQUEST_URI"]);
        $parsed_url = parse_url(end($arr_path)); # divide i componenti della url in un array associativo

        # prendo il path
        $request_path = $parsed_url["path"];



        # se sono in home (solo \) inizializzo a home
        if ($request_path == ''){
            $request_path = "home";
        }

        $params = [];
        # se c'è un query string lo metto in un array
        # La funzione parse_str di php interpreta la string passata del query string e avvalora l'array ($params)
        # inserendo all'interno l'accoppiate chiave/valore del querystring es. a=1&b=2 ->  array("a" => 1, "b" => 2)
        if (array_key_exists("query", $parsed_url)){
            parse_str($parsed_url["query"], $params);
        }

        # Costruisco il controller da chiamare come path+"Controller" es. homeController per /
        $controller = ucfirst($request_path) . "Controller";

        # Controllo se la classe esiste, se non esiste ritorno 404
        if (file_exists("App/Controllers/".$controller.".php")){
            # istanzio la classe
            $controller_object = new $controller();

            # chiamo la funzione main passando l'array del query string come parametro

            # es.
            # nel caso di /post?id=1
            # PostController->main(["id" => 1])

            call_user_func_array(array($controller_object, "main"), array_values(array("params" => $params))); # funzione che prevede una funzione di callback (x es. function main di BlogController, passando un array di parametri ($params[])
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }

}
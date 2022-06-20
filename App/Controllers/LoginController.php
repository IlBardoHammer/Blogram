<?php

/**
 * Controller per la gestione di /login
 */
class LoginController extends Controller
{
    public function main()
    {
        $err_message = '';

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            $users = UserModel::getUserByUsername($_POST["username"]);

            if (count($users) > 0 && $users[0]["password"] == $_POST["password"]){ # se esiste un autore con quell'username
                $user = $users[0];                                                   # e password è uguale a quella della request, imposta l'autore in sessione (loggalo).
                $id = $user["id"];
                $username = $user["username"];

                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;

                self::redirect($_SERVER["REQUEST_URI"]."/../"); # ti reindirizza alla homepage
            } else {

                $err_message .= "L'utente non è registrato!";
            }
        }

        TemplateCore::view('login.html', array("error" => $err_message));
    }
}
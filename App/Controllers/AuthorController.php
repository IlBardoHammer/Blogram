<?php

/**
 * Controller per la gestione di /edit_account
 */
class AuthorController extends Controller
{
    public function main()
    {
        $err_message = null;
        $author_by_id = AuthorModel::getAuthorById($_SESSION["id"]); # prende l'autore a partire dall'id che è in sessione

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            $field_exist = array(
                0 => array(
                    "author_exist" => 0
                )
            );
            if($_POST["email"] != $author_by_id[0]['email']){ # se la mail che viene passata in $_POST != dalla mail dell'autore loggato,
                $field_exist = RegisterModel::checkEditEmailExists($_POST["email"]); # conta quanti autori esistono nel db con quella email (aggiorna $field_exists)
            }

            if($_POST["num_document"] != $author_by_id[0]['num_document']){ # "" per num document
                $field_exist = RegisterModel::checkEditDocExists($_POST["num_document"], $_POST["type_document"]);
            }

//            if(($field_exist[0]["author_exist"] == 0) && $_POST["num_document"] != $author_by_id[0]['num_document']){ # ""
//                $field_exist = RegisterModel::checkEditDocExists($_POST["num_document"], $_POST["type_document"]);
//            }

           if ($field_exist[0]["author_exist"] == 0) {

                $author = RegisterModel::UpdateAuthor(
                    $_POST["password"],
                    $_POST["email"],
                    $_POST["num_document"],
                    $_POST["type_document"],
                    $_POST["telephone_number"]
                );

                if($_POST["password"] != "") { # se la password è vuota non è stata cambiata
                    session_destroy(); # elimina tutto dalla $_SESSION
                    header("location:login"); # se hai cambiato la password, ti reindirizzo alla login.php
                } else {
                    header("location:"); # ricarica la stessa pagina con dati aggiornati
                }

           } else {
               $err_message = "L'autore è gia registrato!";
           }
        }


        TemplateCore::view('edit_account.html', array("error" => $err_message, "author" => $author_by_id[0]));
    }

}
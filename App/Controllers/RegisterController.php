<?php

/**
 * Controller per la gestione di /register
 */
class RegisterController extends Controller
{
    public function main()
    {
        $err_message = null;

        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $num_author = RegisterModel::checkAuthorExists($_POST["email"], $_POST["username"], $_POST["num_document"], $_POST["type_document"]);

           if ($num_author[0]["author_exist"] == 0) {

                $new_author = RegisterModel::InsertAuthor(

                    $_POST["name"],
                    $_POST["surname"],
                    $_POST ["username"],
                    $_POST["password"],
                    $_POST["email"],
                    $_POST["num_document"],
                    $_POST["type_document"],
                    $_POST["telephone_number"]
                );

               header("location:login");

           } else {
               $err_message = "L'autore è gia registrato!";
           }
        }


        TemplateCore::view('register.html', array("error" => $err_message));
    }

}
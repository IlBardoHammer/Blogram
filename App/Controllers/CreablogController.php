<?php

class CreablogController extends Controller
{
    public function main($params)
    {
        self::is_logged();
        $err_message = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST"){

            $blogname = $_POST["blogname"];
            $id_argument = $_POST["blogargument"];
            $id_theme = $_POST["blogtheme"];
            $id_coauthor = array_key_exists("blogcoauthor", $_POST)?$_POST["blogcoauthor"]:null;
            $cover = $_FILES["cover"]; #corrispettivo di $_POST (invia i campi del form) però per i file con name="cover"

            if (sizeof(BlogModel::getBlogByName($blogname)) == 0) { # se non esistono altri blog con quel nome allora salvo

                # 1. SALVO LA COVER IMAGE

                try {
                    $target_file = null;
                    if ($cover["name"] != null) { # se è stato passato un file
                        # C'è una cover image
                        # cosa viene passato in $_FILES: [name] => logo_elisendra.png [type] => image/png [tmp_name] => /tmp/phpEDZgkW [error] => 0 [size] => 33830

                        $target_dir = "public/static/contents/blog_images/"; # path dove viene salvata l'immagine
                        $file_name = self::random_string(32) . "." . pathinfo($cover["name"], PATHINFO_EXTENSION); # calcolo un nome univoco per ogni file

                        $target_file = $target_dir . $file_name;

                        move_uploaded_file($cover["tmp_name"], $target_file); # muove il file dalla cartella temporanea, alla cartella /blog_images con nome random
                    }

                    # 2. AGGIUNGO IL NUOVO BLOG E IL COAUTORE
                    BlogModel::insertBlog($blogname, $id_argument, $id_theme, $id_coauthor, $file_name);

                    header("location:blogs");
                } catch (Exception $e){
                    # QUALCOSA E' ANDATO STORTO
                    if (file_exists($target_file)){
                        unlink($target_file);
                    }

                    print($e->getMessage());

                    $err_message = "Something went wrong.";
                }

            } else {
                $err_message = "Blog with name \"" . $blogname . "\" already exists.";
            }
        }

        # servono per selezionare i campi al momento della creazione
        $arguments = ArgumentModel::getAllArguments();
        $themes = BlogModel::getAllThemes();
        $authors = AuthorModel::getAllAuthor();


        TemplateCore::view('creablog.html', array(
            "authors" => $authors,
            "themes" => $themes,
            "arguments" => $arguments,
            "error" => $err_message)
        );

    }

}
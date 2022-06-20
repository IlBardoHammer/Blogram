<?php

class EditblogController extends Controller
{

    public function main($params)
    {
        self::is_logged();

        $blog = BlogModel::getBlogById(($_SERVER["REQUEST_METHOD"] == "POST")? $_POST["id"]: $_GET["id"]);

        $err_message = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $blogname = $_POST["blogname"];
            $id_theme = $_POST["blogtheme"];
            $id_coauthor = array_key_exists("blogcoauthor", $_POST)?$_POST["blogcoauthor"]:null;
            $cover = $_FILES["cover"];

            if (sizeof(BlogModel::checkBlogByName($blogname, $blog[0]["id"])) == 0) { # verifichi che non esita un blog con quel nome tranne se stesso
                # 1. SALVO LA COVER IMAGE

                try {
                    $target_file = null;
                    if ($cover["name"] != null) {
                        $target_dir = "public/static/contents/blog_images/";
                        $file_name = self::random_string(32) . "." . pathinfo($cover["name"], PATHINFO_EXTENSION);

                        $target_file = $target_dir . $file_name;

                        move_uploaded_file($cover["tmp_name"], $target_file);

                        //elimino il vecchio file
                        if ($blog[0]["path_copertina"] != "" && file_exists($target_dir . $blog[0]["path_copertina"])){
                            unlink($target_dir . $blog[0]["path_copertina"]);
                        }
                    } else {
                        $file_name = $blog[0]["path_copertina"]; # non è stata cambiata l'immagine
                    }

                    # 2. AGGIUNGO IL NUOVO BLOG E IL COAUTORE
                    BlogModel::UpdateBlog($blog[0]["id"], $blogname, $id_theme, $id_coauthor, $file_name);

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

        $arguments = ArgumentModel::getAllArguments();
        $themes = BlogModel::getAllThemes();
        $authors = AuthorModel::getAllAuthor();

        TemplateCore::view('edit_blog.html', array(
            "blog" => $blog[0],
            "authors" => $authors,
            "themes" => $themes,
            "arguments" => $arguments,
            "error" => $err_message)
        );
    }
}
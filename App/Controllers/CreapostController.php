<?php

class CreapostController extends Controller
{
    public function main($params)
    {
        self::is_logged();
        $err_message = null;
        $id_blog = $params["id_blog"];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $post_title = $_POST["posttitle"];
            $post_text = $_POST["posttext"];
            $postimages = $_FILES["postimages"];

            if (sizeof(PostModel::getPostByTitle($post_title)) == 0) { #controllo che il numero di post con questo titolo sia 0
                try {
                    $tmp_target_filename = [];
                    $target_dir = "public/static/contents/post_images/";

                    if ($postimages["name"][0] != null) {
                        # C'è una o più immagini


                        for($i = 0; $i < sizeof($postimages["name"]); $i++){
                            $tmp_target_filename[] = $file_name = self::random_string(32) . "." . pathinfo($postimages["name"][$i], PATHINFO_EXTENSION);
                            $target_file = $target_dir . $file_name;

                            move_uploaded_file($postimages["tmp_name"][$i], $target_file);
                        }
                    }

                    # 2. AGGIUNGO IL NUOVO POST
                    PostModel::insertPost($id_blog, $post_title, $post_text, $tmp_target_filename);

                    header("location:blog?id=".$id_blog);
                } catch (Exception $e) {
                    # QUALCOSA E' ANDATO STORTO
                    foreach($tmp_target_filename as $target_file){
                        $full_target_file = $target_dir . $target_file;
                        if (file_exists($full_target_file)) {
                            unlink($full_target_file);
                        }
                    }

                    print($e->getMessage());

                    $err_message = "Something went wrong.";
                }

            } else {
                $err_message = "Post with title \"" . $post_title . "\" alreary exists.";
            }
        }

        $arguments = ArgumentModel::getAllArguments();
        $themes = BlogModel::getAllThemes();
        $authors = AuthorModel::getAllAuthor();


        TemplateCore::view('creapost.html', array(
                "id_blog" => $id_blog,
                "authors" => $authors,
                "themes" => $themes,
                "arguments" => $arguments,
                "error" => $err_message)
        );
    }
}
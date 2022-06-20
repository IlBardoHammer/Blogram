<?php

class DeletepostController extends Controller
{
    public function main($params)
    {
        self::is_logged();

        $post_id = $params["id"];

        $post = PostModel::getPostById($post_id);
        $post_images = PostModel::getPostImagesById($post_id);

        $deleted_rows = 0;
        if (isset($post[0]) && $_SESSION["id"] == $post[0]['id_author']) { # controllo se id in sessione è uguale a id_author del post richiesto
            $deleted_rows = PostModel::deletePostById($post_id); # cancella post
        }

        if ($deleted_rows) {
            try {
                $target_dir = "public/static/contents/post_images/";

                foreach($post_images as $file){
                    $target_file = $target_dir . $file["file_name"];

                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }


            } catch (Exception $e) {
                # leave the image
            }
        }

        header("location:blog?id=" . $post[0]["id_blog"]);
    }
}
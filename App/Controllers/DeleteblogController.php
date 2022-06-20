<?php

class DeleteblogController extends Controller
{
    public function main($params)
    {
        self::is_logged();

        $blog_id = $params["id"];

        $blog = BlogModel::getBlogById($blog_id);

        $deleted_rows = 0;
        if (isset($blog[0]) && $_SESSION["id"] == $blog[0]['id_author']) { # se id in sessione è uguale a quello dell'autore del blog da eliminare:
            $deleted_rows = BlogModel::deleteBlogById($blog_id); # --> elimino
        }

        if ($deleted_rows) {
            try {
                $target_dir = "public/static/contents/blog_images/";
                $file_name = $blog[0]['path_copertina'];

                $target_file = $target_dir . $file_name;

                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            } catch (Exception $e) {
                # leave the image
            }
        }

        header("location:blogs");
    }
}
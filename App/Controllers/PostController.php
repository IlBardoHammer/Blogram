<?php

/**
 * Controller per la gestione di /post
 */
class PostController
{
    public function main($params)
    {
        $id_post = $params["id"]; # salvo in una variabile l'id del post corrente


        if($_SERVER["REQUEST_METHOD"] == "POST") { # se c'è una request POST ho aggiunto un commento
            # verifico se esiste author id, altrimenti imposto id dal tracking del visitatore anonimo
            $id_author = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
            $id_visitor = isset($_SESSION["tracking"]["id"]) && $id_author == null ? $_SESSION["tracking"]["id"] : null;

            PostModel::addNewComment($id_post, $id_author, $id_visitor, $_POST["comment"]); # aggiungo commento al db

        }

        # Traccio la visita sul post
        TrackingCore::postView($id_post);

        # query per mostrare il post
        $post = PostModel::getPostById($id_post);
        $comments = PostModel::getCommentsByPostId($id_post);

        $blog = BlogModel::getBlogById($post[0]['id_blog'])[0];
        $theme = null;
        if($blog["id_theme"]){
            $theme = ThemeModel::getThemeById($blog["id_theme"])[0];
        }

        TemplateCore::view('post.html', array(
            "post" => $post[0],
            "theme" => $theme,
            "comments" => $comments
        ));
    }
}
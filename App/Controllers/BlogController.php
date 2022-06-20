<?php

/**
 * Controller per la gestione di /blog
 */
class BlogController
{
    public function main($params)
    {
        $blog_id = $params["id"];

        # Quanti elementi voglio per pagina
        $element_per_page = 8;

        # Calcolo da quale elemento devo partire secondo la regola:
        # offset = (PAGINA - 1) * ELEMENTS_PER_PAGE
        $offset = array_key_exists("page", $params) ? ($params["page"] - 1) * $element_per_page : 0;

        $blog_info = BlogModel::getBlogById($blog_id)[0];
        list($posts, $n_posts) = PostModel::getAllPostsByBlog($blog_id, $element_per_page, $offset);

        $theme = null;
        if($blog_info["id_theme"]){
            $theme = ThemeModel::getThemeById($blog_info["id_theme"])[0]; # va a prendersi il tema presente per quel determinato blog_id
        }

        TemplateCore::view('blog.html', array(
            "blog" => $blog_info, "posts" => $posts,
            "theme" => $theme,
            "pagination" => ceil($n_posts / $element_per_page) != 0 ? ceil($n_posts / $element_per_page) : 1,
            "actual_page" => array_key_exists("page", $params) ? $params["page"] : 1,
        ));
    }

}
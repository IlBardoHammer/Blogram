<?php

/**
 * Controller per la gestione di /blogs
 */
class BlogsController
{
    public function main($params)
    {
        # Quanti elementi voglio per pagina
        $element_per_page = 4;
/**
 * formula per calcolare offset e limit ad ogni pagina:
 *
 *  -- page 1				(page - 1)  4 = (1 - 1)  4 = 0  --> (0,4) se page è vuoto metto '0', quindi riceverò 4 blog, a partire dalla riga 0
    -- page 2				(page - 1)  4 = (2 - 1)  4 = 4  --> (4,4)
    -- page 3				(page - 1)  4 = (3 - 1)  4 = 8  --> (8,4)
    -- page 4				(page - 1)  4 = (4 - 1)  4 = 12 --> (12,4)
 */

        # Calcolo da quale elemento devo partire secondo la regola:
        # offset = (PAGINA - 1) * ELEMENTS_PER_PAGE
        $offset = array_key_exists("page", $params) ? ($params["page"] - 1) * $element_per_page : 0;

        # pagina richiamabile sia in GET (va nell'else, e l'utente vedrà tutti i blog) sia in POST (ci saranno dei parametri di ricerca)

        if ($_POST) {
            # RICERCA PER:
            # ARGOMENTO
            # AUTORE
            # TITOLO BLOG
            # TITOLO POST

            #print_r($_POST);
            $search_array = [];

            # verifico che ogni input text esista e non sia vuoto, se è così lo aggiungo a search_array[]
            if (array_key_exists("search_blog_name", $_POST) && $_POST["search_blog_name"] != "")
                $search_array["name"] = $_POST["search_blog_name"];

            if (array_key_exists("search_author_username", $_POST) && $_POST["search_author_username"] != "")
                $search_array["author_username"] = $_POST["search_author_username"];

            if (array_key_exists("search_title_post", $_POST) && $_POST["search_title_post"] != "")
                $search_array["title_post"] = $_POST["search_title_post"];


            if (array_key_exists("search_arguments", $_POST) && count($_POST["search_arguments"]) > 0)
                $search_array["argument"] = implode(',', $_POST["search_arguments"]); # trasformo l'array degli id_argument selezionati in stringha separati da ,


            list($blogs, $n_blogs) = BlogModel::getAllBlogs($element_per_page, $offset, $search_array);
        } else {
            # Recupero $element_per_page elementi dal database partendo dall'elemento $offset
            list($blogs, $n_blogs) = BlogModel::getAllBlogs($element_per_page, $offset);
        }
        /**
         *  calcolo la divisione di paginazione facendo --> (righe blogs ritornate) / 4 (elementi per pagina) = numero di pagine (per eccesso)
         */

        TemplateCore::view('blogs.html', array(
            "pagination" => ceil($n_blogs / $element_per_page) != 0 ? ceil($n_blogs / $element_per_page) : 1,
            "actual_page" => array_key_exists("page", $params) ? $params["page"] : 1,
            "blogs" => $blogs, "n_blogs" => $n_blogs
        ));
    }

}
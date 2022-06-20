<?php

class MyblogsController extends \Controller
{
    public function main($params){
        self::is_logged();

        $element_per_page = 5;
        $offset = array_key_exists("page", $params) ? ($params["page"] - 1) * $element_per_page : 0;

        $search_array["author_username"] = $_SESSION["username"]; # il campo username prende username che è in sessione (loggato)

        list($blogs, $n_blogs) = BlogModel::getAllBlogs($element_per_page, $offset, $search_array);

        TemplateCore::view('blogs.html', array(
            "pagination" => ceil($n_blogs / $element_per_page) != 0 ? ceil($n_blogs / $element_per_page) : 1,
            "actual_page" => array_key_exists("page", $params) ? $params["page"] : 1,
            "blogs" => $blogs, "n_blogs" => $n_blogs
        ));
    }
}
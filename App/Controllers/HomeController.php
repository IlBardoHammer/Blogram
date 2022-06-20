<?php

/**
 * Controller per la gestione di / e /home
 */
class HomeController extends Controller
{
    public function main($params)
    {
        list($blogs, $n_blogs) = BlogModel::getAllBlogs(4);
        list($posts, $n_posts) = PostModel::getAllPosts(2);
        list($arguments, $n_arguments) = ArgumentModel::getAllTopArguments(4);
        list($authors, $n_authors) = AuthorModel::getTopAuthor(4);


        TemplateCore::view('homepage.html', array(
            "blogs" => $blogs, "n_blogs" => $n_blogs,
            "posts" => $posts, "n_posts" => $n_posts,
            "arguments" => $arguments, "n_arguments" => $n_arguments,
            "authors" => $authors, "n_authors" => $n_authors
        ));
    }
}
<?php

class PostModel extends BaseModel
{
    /**
     * Restituisce tutti i post ordinati per numero di visite
     *
     * @param $limit
     * @param $offset
     * @return array
     */
    public static function getPostByTitle($title)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
        SELECT
            *
        FROM
            post
        WHERE
            title = :title

        ");

        $result->execute(array("title" => $title));

        return $result->fetchAll();

    }


    public static function getPostImagesById($id_post)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
        SELECT
            file_name
        FROM
            multimedia_file
        WHERE
            id_post = :id_post

        ");

        $result->execute(array("id_post" => $id_post));

        return $result->fetchAll();

    }

    public static function insertPost($id_blog, $post_title, $post_text, $tmp_target_filename){
        $db = DbCore::getInstance();

        $db->beginTransaction();

        try {
            $new_post = $db->prepare("
                INSERT INTO post
                
                VALUES (null, :id_author, :id_blog, :post_title, CURRENT_TIME, :post_text)                                          
            ");

            $arr_values = array(
                "id_author" => $_SESSION["id"],
                "id_blog" => $id_blog,
                "post_title" => $post_title,
                "post_text" => $post_text
            );

            $new_post->execute($arr_values);
            $new_post_id = $db->lastInsertId();

            if (sizeof($tmp_target_filename) > 0){

                foreach($tmp_target_filename as $filename){
                    $new_multimedia = $db->prepare("
                        INSERT INTO multimedia_file
                        
                        VALUES (null, :id_post, :filename)                                          
                    ");

                    $arr_values = array(
                        "id_post" => $new_post_id,
                        "filename" => $filename
                    );

                    $new_multimedia->execute($arr_values);
                }
            }

            #########################
//            throw new Exception("CI FERMIAMO DA SOLI");
            #########################

        } catch (Exception $e) {
            $db->rollBack();

            throw new Exception($e->getMessage());
        }

        $db->commit();

        return True;
    }

    public static function deletePostById($id){
        $db = DbCore::getInstance();

        $result = $db->prepare("
            DELETE
            
            FROM
                post
            WHERE
                post.id = :id
                
            ");
        $result->execute(array("id" => $id));


        return $result->rowCount() > 0;
    }

    public static function addNewComment($id_post, $id_author, $id_visitor, $text_comment){
        $db = DbCore::getInstance();

        $new_blog = $db->prepare("
                INSERT INTO comment
                
                VALUES (null, :id_post, :id_author, :id_visitor, :text_comment, CURRENT_TIMESTAMP)                                          
            ");

        $arr_values = array(
            "id_post" => $id_post,
            "id_author" => $id_author,
            "id_visitor" => $id_visitor,
            "text_comment" => $text_comment
        );

        $new_blog->execute($arr_values);
    }

    public static function getCommentsByPostId($id_post){
        $db = DbCore::getInstance();

        $result = $db->prepare("
        SELECT
            comment.*,
            author.username AS author_username
        FROM
            comment LEFT JOIN author ON (author.id = comment.id_author)
        WHERE 
            comment.id_post = :id_post  
        ORDER BY
            comment.date_hours DESC
        ");

        $result->execute(array("id_post" => $id_post));

        return $result->fetchAll();
    }

    public static function getPostById($id)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
        SELECT
            post.*,
           GROUP_CONCAT(multimedia_file.file_name) AS images, -- nel caso in cui ci fossero più immagini crea una stringa con tutti i valori separati da ',' 
           (
                SELECT 
                    count(*) 
                FROM 
                     visit  
                WHERE 
                      visit.id_post = post.id
            ) as visite,                
            blog.name AS blog_name,
            blog.id AS blog_id,   
            author.username AS author_username
        FROM
            post LEFT JOIN 
            multimedia_file ON (post.id = multimedia_file.id_post) LEFT JOIN
            author ON (post.id_author = author.id) LEFT JOIN
            blog ON (post.id_blog = blog.id)
        WHERE 
            post.id = :id
        GROUP BY 
            post.id
        ");

        $result->execute(array("id" => $id));

        return $result->fetchAll();

    }

    public static function getAllPostsByBlog($blog_id, $limit = null, $offset = 0)
    {
        $db = DbCore::getInstance();

        $query = "
            SELECT 
                SQL_CALC_FOUND_ROWS 
                post.*, 
                GROUP_CONCAT(multimedia_file.file_name) AS images,                
                (
                    SELECT 
                        count(*) 
                    FROM 
                         visit  
                    WHERE 
                          visit.id_post = post.id
                ) as visite,                
                blog.name AS blog_name,
                blog.id AS blog_id,   
                author.username AS author_username
            FROM 
                post LEFT JOIN 
                multimedia_file ON (post.id = multimedia_file.id_post) LEFT JOIN
                author ON (post.id_author = author.id) LEFT JOIN
                blog ON (post.id_blog = blog.id)  
            WHERE
                post.id_blog = :blog_id
            GROUP BY 
                post.id
            ORDER BY 
                visite DESC";

        $arr_values = array("blog_id" => $blog_id);

        self::applyLimits($query, $arr_values, $limit, $offset);

        $result = $db->prepare($query);
        $result->execute($arr_values);

        $n_rows = $db->query('SELECT FOUND_ROWS()');

        return array($result->fetchAll(), $n_rows->fetchColumn());
    }

    public static function getAllPosts($limit = null, $offset = 0)
    {
        $db = DbCore::getInstance();

        $query = "
            SELECT 
                SQL_CALC_FOUND_ROWS 
                post.*, 
                GROUP_CONCAT(multimedia_file.file_name) AS images,                
                (
                    SELECT 
                        count(*) 
                    FROM 
                         visit  
                    WHERE 
                          visit.id_post = post.id
                ) as visite,
                blog.name AS blog_name,
                blog.id AS blog_id,
                author.username AS author_username
            FROM 
                post LEFT JOIN 
                multimedia_file ON (post.id = multimedia_file.id_post) LEFT JOIN
                author ON (post.id_author = author.id) LEFT JOIN
                blog ON (post.id_blog = blog.id)
            GROUP BY 
                post.id
            ORDER BY 
                visite DESC";

        self::applyLimits($query, $arr_values, $limit, $offset);

        $result = $db->prepare($query);
        $result->execute($arr_values);

        $n_rows = $db->query('SELECT FOUND_ROWS()');

        return array($result->fetchAll(), $n_rows->fetchColumn());
    }
}
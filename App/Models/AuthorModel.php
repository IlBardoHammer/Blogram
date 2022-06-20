<?php

class AuthorModel extends BaseModel
{
    public static function getTopAuthor($limit = null, $offset = 0){

        $db = DbCore::getInstance();
        $query = "SELECT 
                SQL_CALC_FOUND_ROWS 
                author.id,
                author.username,
                COUNT(blog.id_author) AS blogs_count
            FROM 
                author JOIN blog ON (blog.id_author = author.id)
            GROUP BY 
                id, username
            ORDER BY 
                blogs_count DESC 
            ";

        self::applyLimits($query, $arr_values, $limit, $offset);

        $result = $db->prepare($query);
        $result->execute($arr_values);

        $n_rows = $db->query('SELECT FOUND_ROWS()');


        return array($result->fetchAll(), $n_rows->fetchColumn());
    }

    public static function getAllAuthor()
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("SELECT       
            
            *
        FROM
            author
         ");

        $result->execute();

        return $result->fetchAll();
    }
    public static function getAuthorById($id)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("SELECT       
            
            *
        FROM
            author
        WHERE
            id = :id
         ");

        $arr_values = array(
            "id" => $id
        );

        $result->execute($arr_values);

        return $result->fetchAll();
    }
}
            
                
        







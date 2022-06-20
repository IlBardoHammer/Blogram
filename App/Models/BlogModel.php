<?php

class BlogModel extends BaseModel
{

    public static function getBlogByName($name){
        $db = DbCore::getInstance();

        $result = $db->prepare("
            SELECT
                *
            FROM
                blog
            WHERE
                blog.name = :blog_name
                
            ");
        $result->execute(array("blog_name" => $name));


        return $result->fetchAll();
    }

    public static function deleteBlogById($id){
        $db = DbCore::getInstance();

        $result = $db->prepare("
            DELETE
            
            FROM
                blog
            WHERE
                blog.id = :id
                
            ");
        $result->execute(array("id" => $id));


        return $result->rowCount() > 0;
    }

    public static function checkBlogByName($name, $id){
        $db = DbCore::getInstance();

        $result = $db->prepare("
            SELECT
                *
            FROM
                blog
            WHERE
                blog.name = :blog_name and id != :id
                
            ");
        $result->execute(array("blog_name" => $name, "id" => $id));


        return $result->fetchAll();
    }

    public static function getBlogById($id){
        $db = DbCore::getInstance();

        $result = $db->prepare("
            SELECT
                blog.*,
               (SELECT aa.id FROM author AS aa WHERE aa.id = co_author.id_author) AS id_coauthor
            FROM
                blog LEFT JOIN
                co_author ON (co_author.id_blog = blog.id)
            WHERE
                blog.id = :blog_id
                
            ");
        $result->execute(array("blog_id" => $id));


        return $result->fetchAll();
    }

    /**
     * Restituisce l'elenco dei blog ordinati per numero di visite decrescente
     *
     * @param $limit Il numero di blog da ritornare
     * @param $offset L'offset dal quale partire
     *
     * @return array
     */
    public static function getAllBlogs($limit = null, $offset = 0, $search = [])
    {
        # Prendo la lista di tutti gli argomenti, perchè se non ci sono parametri di ricerca restituisco tutti i blog, ricercando per tutti gli id_arguments
        $base_arguments = [];
        foreach (ArgumentModel::getAllArguments() AS $argument){
            $base_arguments[] = $argument["id"];
        }

        # Creo l'array delle ricerche di default (tasto 'cerca' senza parametri)
        $default_search = ['name' => '%', 'author_username' => '%', 'title_post' => '%', 'argument' => implode(',',$base_arguments)];

        # Faccio il merge con quello ricevuto per inizializzare a default i valori non passati e sovrascrivere quelli passati
        $search = array_merge($default_search, $search);

        $db = DbCore::getInstance();

        $query = "
            SELECT 
                SQL_CALC_FOUND_ROWS 
                blog.*,                 
                (
                    SELECT 
                        count(*) 
                    FROM 
                        visit JOIN post ON (visit.id_post = post.id)  
                    WHERE 
                        post.id_blog = blog.id                        
                ) as visite, -- subquery che conta le visite dei post e di conseguenza dei blog
                argument.name AS argument_name, -- nome argomento
                (SELECT name from argument AS sa WHERE sa.id = argument.id_parent_argument) parent_argument_name, -- nome subargument dove id.argument = id.subargment
                author.username AS author_username,
                (SELECT aa.id FROM author AS aa WHERE aa.id = co_author.id_author) coauthor_id, -- id dell'eventuale coautore
                (SELECT aa.username FROM author AS aa WHERE aa.id = co_author.id_author) coauthor_username -- username dell'eventuale 
            FROM 
                blog LEFT JOIN -- left perchè se non ha argomento deve comunque darmi il risultato (prima si poteva fare blog senza argument)
                argument ON (blog.id_argument = argument.id) JOIN -- JOIN stretta perchè author deve esistere
                author ON (blog.id_author = author.id) LEFT JOIN 
                post ON (post.id_blog = blog.id) LEFT JOIN
                co_author ON (co_author.id_blog = blog.id)
            WHERE
                blog.name LIKE :search_blog_name AND
                (author.username LIKE :search_author_username OR (SELECT aa.username FROM author AS aa WHERE aa.id = co_author.id_author) LIKE :search_coauthor_username) AND
                COALESCE(post.title, '') LIKE :search_title_post AND -- COALESCE perchè se il post non c'è darebbe --> null LIKE '%' o 'abc'
                (find_in_set(cast(argument.id as char), :search_arguments) OR find_in_set(cast(argument.id_parent_argument as char), :search_parent_arguments)) -- cerca sia i blog con argument sia quelli con parent_argument
            GROUP BY 
                 blog.id,
                 co_author.id_author -- da capire coautore
            ORDER BY 
                     visite DESC";

        $arr_values = array(
            "search_blog_name" => "%".$search["name"]."%",
            "search_author_username" => "%".$search["author_username"]."%",
            "search_coauthor_username" => "%".$search["author_username"]."%",
            "search_title_post" => "%".$search["title_post"]."%",
            "search_arguments" => $search["argument"],
            "search_parent_arguments" => $search["argument"]
        );

        self::applyLimits($query, $arr_values, $limit, $offset); # query per applicare $limit e $offset
        $result = $db->prepare($query);
        $result->execute($arr_values); # va a sostituire i valori dell' $arr_values dentro la query

        $n_rows = $db->query('SELECT FOUND_ROWS()');

        return array($result->fetchAll(), $n_rows->fetchColumn());
    }

    public static function getAllThemes()
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("SELECT       
            *
        FROM
            theme
         ");

        $result->execute();

        return $result->fetchAll();
    }


    public static function insertBlog($name, $id_argument, $id_theme, $id_coauthor, $cover_name)
    {
        $db = DbCore::getInstance();

        $db->beginTransaction(); # se succede qualcosa durante la transaction, non scrivo nulla nel db

        try {
            $new_blog = $db->prepare("
                INSERT INTO blog
                
                VALUES (null, :id_author, :id_argument, :name, :id_theme, :path_copertina)                                          
            ");

            $arr_values = array(
                "id_author" => $_SESSION["id"],
                "id_argument" => $id_argument,
                "name" => $name,
                "id_theme" => $id_theme,
                "path_copertina" => $cover_name
            );

            $new_blog->execute($arr_values);
            $new_blog_id = $db->lastInsertId();

            if ($id_coauthor != null){
                $new_coauthor = $db->prepare("
                    INSERT INTO co_author
                    
                    VALUES (:id_blog, :id_author)                                          
                ");

                $arr_values = array(
                    "id_blog" => $new_blog_id,
                    "id_author" => $id_coauthor
                );

                $new_coauthor->execute($arr_values);
            }


        } catch (Exception $e) {
            $db->rollBack();

            throw new Exception($e->getMessage());
        }

        $db->commit();

        return True;
    }

    public static function UpdateBlog($id_blog, $name, $id_theme, $id_coauthor, $cover_name)
    {
        $db = DbCore::getInstance();

        $db->beginTransaction();

        try {
            $blog = $db->prepare("
                UPDATE blog
                
                SET name = :name, id_theme = :id_theme, path_copertina = :path_copertina   

                WHERE id = :id_blog
            ");

            $arr_values = array(
                "name" => $name,
                "id_theme" => $id_theme,
                "path_copertina" => $cover_name,
                "id_blog" => $id_blog
            );

            $blog->execute($arr_values);

            if ($id_coauthor == 0){
                $coauthor = $db->prepare("
                  DELETE FROM co_author 
                  
                  WHERE id_blog = :id_blog 
                    
                                                              
                ");

                $arr_values = array(
                    "id_blog" => $id_blog
                );

                $coauthor->execute($arr_values);
            } else {
                $coauthor = $db->prepare("
                  INSERT INTO co_author (id_blog, id_author) VALUES (:id_blog, :id_author) ON DUPLICATE KEY UPDATE id_author = :id_author_where
                                                          
                ");

                $arr_values = array(
                    "id_blog" => $id_blog,
                    "id_author" => $id_coauthor,
                    "id_author_where" => $id_coauthor
                );

                $coauthor->execute($arr_values);
            }
        } catch (Exception $e) {
            $db->rollBack();

            throw new Exception($e->getMessage());
        }

        $db->commit();

        return True;
    }
}
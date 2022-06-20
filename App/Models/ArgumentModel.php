<?php

class ArgumentModel extends BaseModel
{
    public static function getAllArguments(){
        $db = DbCore::getInstance();

        $result = $db->prepare("SELECT       
            pp.*,
            (SELECT name from argument WHERE id = pp.id_parent_argument) parent_argument_name -- prendo tutta la lista degli argomenti
        FROM
            argument as pp
        ORDER BY 
            COALESCE(id_parent_argument, id)");

        $result->execute();

        return $result->fetchAll();
    }

    /**
     * @param $limit
     * @param $offset
     * @return array
     */
    public static function getAllTopArguments($limit = null, $offset = 0) # solo per argomenti principali
    {
        $db = DbCore::getInstance();
        $query = "SELECT       
            SQL_CALC_FOUND_ROWS
            COALESCE(argument.id_parent_argument, argument.id) id, -- ritorno sempre l'id della categoria principale
            (SELECT subargument.name FROM argument AS subargument WHERE subargument.id = COALESCE(argument.id_parent_argument, argument.id)) AS name, -- ritorno sempre il name della categoria principale
            COUNT(visit.id_post) AS visite
        FROM
            argument LEFT JOIN blog ON (blog.id_argument = argument.id)
            LEFT JOIN post ON (blog.id = post.id_blog)
            LEFT JOIN visit ON (visit.id_post = post.id)
        GROUP BY
            id, name
        ORDER BY
            visite DESC";

        self::applyLimits($query, $arr_values, $limit, $offset);

        $result = $db->prepare($query);
        $result->execute($arr_values);

        $n_rows = $db->query('SELECT FOUND_ROWS()');

        return array($result->fetchAll(), $n_rows->fetchColumn());
    }
}
<?php

class ThemeModel extends BaseModel
{
    public static function getThemeById($id)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("SELECT
            *
        FROM
            theme
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
            
                
        







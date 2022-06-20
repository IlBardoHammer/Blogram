<?php

class BaseModel
{
    public static function applyLimits(&$query, &$arr_values, $limit, $offset){
        if ($limit != null){
            $query .= " LIMIT :offset, :limit";
            $arr_values = array_merge($arr_values?:array(), array("offset" => $offset, "limit" => $limit));
        }
    }
}
            
                
        







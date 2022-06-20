<?php


class UserModel extends BaseModel
{
    public static function getUserByUsername($username)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("SELECT       
            
                *
                FROM
                    author
                WHERE
                    username = :username
                ");

        $result->execute(array("username" => $username));

        return $result->fetchAll();
    }
}
<?php

class RegisterModel extends BaseModel
{


    public static function checkAuthorExists($email, $username, $num_document, $type_document)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
                                SELECT       
                                    count(*) as author_exist
                                FROM
                                    author
                                WHERE 
                                      author.email = :email OR 
                                      author.username = :username OR
                                      (author.num_document = :num_document AND author.type_document = :type_document)
         ");

        $arr_values = array(
            "email" => $email,
            "username" => $username,
            "num_document" => $num_document,
            "type_document" => $type_document
        );

        $result->execute($arr_values);

        return $result->fetchAll();
    }

    public static function checkEditEmailExists($email)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
                                SELECT       
                                    count(*) as author_exist
                                FROM
                                    author
                                WHERE 
                                      author.email = :email
         ");

        $arr_values = array(
            "email" => $email
        );

        $result->execute($arr_values);

        return $result->fetchAll();
    }

    public static function checkEditDocExists($num_document, $type_document)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
                                SELECT       
                                    count(*) as author_exist
                                FROM
                                    author
                                WHERE 
                                      (author.num_document = :num_document AND author.type_document = :type_document)
         ");

        $arr_values = array(
            "num_document" => $num_document,
            "type_document" => $type_document
        );

        $result->execute($arr_values);

        return $result->fetchAll();
    }

    public static function InsertAuthor($name, $surname, $username, $password, $email, $num_document, $type_document, $telephone_number)
    {
        $db = DbCore::getInstance();

        $result = $db->prepare("
                                INSERT INTO author
                                
                                VALUES (null, :name, :surname, :username, :password, :email, :num_document, :type_document, :telephone_number)
                                      
        ");

        $arr_values = array(
            "name" => $name,
            "surname" => $surname,
            "email" => $email,
            "username" => $username,
            "password" => $password,
            "telephone_number" => $telephone_number,
            "num_document" => $num_document,
            "type_document" => $type_document
        );

            $result->execute($arr_values);

        return $result->fetchAll();
    }

    public static function UpdateAuthor($password, $email, $num_document, $type_document, $telephone_number)
    {
        $db = DbCore::getInstance();

        if ($password != "") {
            $arr_values = array(
                "email" => $email,
                "password" => $password,
                "num_document" => $num_document,
                "type_document" => $type_document,
                "telephone_number" => $telephone_number,
                "id" => $_SESSION["id"]
            );
            $result = $db->prepare("
                                UPDATE author SET password=:password, email=:email, num_document=:num_document, type_document=:type_document, telephone_num=:telephone_number WHERE id=:id
                                      
        ");
        } else {
            $arr_values = array(
                "email" => $email,
                "num_document" => $num_document,
                "type_document" => $type_document,
                "telephone_number" => $telephone_number,
                "id" => $_SESSION["id"]
            );
            $result = $db->prepare("
                                UPDATE author SET email=:email, num_document=:num_document, type_document=:type_document, telephone_num=:telephone_number WHERE id=:id
                                      
        ");
        }

        $res = $result->execute($arr_values);

        return $res;
    }
}
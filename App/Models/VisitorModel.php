<?php

class VisitorModel
{
    public static function addVisitor($ip, $useragent)
    {
        $db = DbCore::getInstance();

        $db->prepare("
            INSERT IGNORE INTO visitor (ip, useragent) VALUES (:ip, :useragent)
        ")->execute(array("ip" => $ip, "useragent" => $useragent));

        $last_id = $db->lastInsertId();

        if ($last_id == 0) {
            # If already exists
            $result = $db->prepare("SELECT id FROM visitor WHERE ip = :ip AND useragent = :useragent");

            $result->execute(array("ip" => $ip, "useragent" => $useragent));
            $last_id = $result->fetchColumn();
        }

        return $last_id;
    }

    public static function addVisit($id_post, $id_author = null, $id_visitor = null){
        $db = DbCore::getInstance();

        $db->prepare("
            INSERT INTO visit (id_post, id_author, id_visitor) VALUES (:id_post, :id_author, :id_visitor)
        ")->execute(array("id_post" => $id_post, "id_author" => $id_author, "id_visitor" => $id_visitor));
    }
}
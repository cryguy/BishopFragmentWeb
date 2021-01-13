<?php


class Capsule extends genericDataObject
{

    public function __construct($id)
    {
        parent::__construct("capsule", $id);
    }

    public function getData(){
        return self::getFromDB()["data"];
    }

    public static function insert($data) {
        return (new Capsule(null))->insert_db($data);
    }
}
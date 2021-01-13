<?php


class kFrag extends genericDataObject
{
    public function __construct($id)
    {
        parent::__construct("kFrag", $id);
    }

    public function getData(){
        return self::getFromDB()["data"];
    }

    public static function insert($data) {
        return (new kFrag(null))->insert_db($data);
    }
}
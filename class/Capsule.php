<?php


class Capsule extends genericDataObject
{

    public function __construct($id)
    {
        parent::__construct("capsule", $id);
    }

    /**
     * @return string json of Capsule
     */
    public function getData(){
        $data = self::getFromDB();
        return (isset($data["data"]) ? $data["data"] : '');
    }

    /**
     * @param $data string containing Capsule json data
     * @return int id of inserted row
     */
    public static function insert($data) {
        return (new Capsule(null))->insert_db($data);
    }
}
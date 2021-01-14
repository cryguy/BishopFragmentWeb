<?php


class kFrag extends genericDataObject
{
    public function __construct($id)
    {
        parent::__construct("kFrag", $id);
    }

    /**
     * @param $data string containing kFrag json data
     * @return int id of inserted row
     */
    public static function insert($data)
    {
        return (new kFrag(null))->insert_db($data);
    }

    /**
     * @return string json of kFrag
     */
    public function getData()
    {
        $data = self::getFromDB();
        return (isset($data["data"]) ? $data["data"] : '');
    }
}
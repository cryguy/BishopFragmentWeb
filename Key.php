<?php


class Key extends genericDataObject
{
    public function __construct($id)
    {
        parent::__construct("pubKey", $id);
    }

    public function getData(){
        return self::getFromDB()["data"];
    }

    /** @noinspection SqlNoDataSourceInspection
     * @noinspection SqlResolve
     * @param $data
     * @param $type
     * @return string id of inserted row
     */
    public function insert_db($data, $type = 0)
    {
        $sql = $this->db->prepare("INSERT INTO pubKey (data, type) VALUES (:data, :type)");
        $sql->bindParam(':data', $data);
        $sql->bindParam(':type', $type, PDO::PARAM_INT);
        $sql->execute();
        return $this->db->lastInsertId("key");
    }

    public static function insert($data, $type = 0) {
        return (new Key(null))->insert_db($data, $type);
    }

}
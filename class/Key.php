<?php


class Key extends genericDataObject
{
    public function __construct($id)
    {
        parent::__construct("pubKey", $id);
    }

    /**
     * @param $data string of key data
     * @param $type int
     * @return string id of inserted row
     */
    public static function insert($data, $type = 0)
    {
        return (new Key(null))->insert_db($data, $type);
    }

    /**
     * @param $data string of key data
     * @param $type int
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

    /**
     * @return string of publicKey
     */
    public function getData()
    {
        $data = self::getFromDB();
        return (isset($data["data"]) ? $data["data"] : '');
    }

}
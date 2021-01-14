<?php


class genericDataObject
{
    protected $db;
    protected $field;
    private $id;

    /**
     * genericDataObject constructor.
     * allow child to get/insert data to/from db
     * @param $field string name of table
     * @param $id int id of row
     */
    public function __construct($field, $id)
    {
        $this->db = ConnectionDB::getInstance();
        $this->field = $field;
        $this->id = $id;
    }


    /**
     * @noinspection SqlResolve
     * @noinspection SqlNoDataSourceInspection
     * get data from db where id = $id
     * @return mixed
     */
    public function getFromDB(){
        $sql = 'SELECT * FROM ' . $this->field . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $data string to insert to database
     * @return bool result
     * @noinspection SqlResolve - cant use this inspection as we are using this as our generic...
     */
    public function insert_db($data){
        $sql = $this->db->prepare("INSERT IGNORE INTO ". $this->field ." (data) VALUES (:data)");
        $sql->bindParam(':data', $data);
        $sql->execute();
        return $this->db->lastInsertId($this->field);
    }
}
<?php


class genericDataObject
{
    protected $db;
    protected $field;
    private $id;

    public function __construct($field, $id)
    {
        $this->db = ConnectionDB::getInstance();
        $this->field = $field;
        $this->id = $id;
    }


    /** @noinspection SqlResolve
     * @noinspection SqlNoDataSourceInspection
     */
    public function getFromDB(){
        $sql = 'SELECT * FROM ' . $this->field . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $arr_json = array('status' => 200, 'client' => $row);
        } else {
            return $arr_json = array('status' => 404);
        }
    }

    /** @noinspection SqlResolve
     * @noinspection SqlNoDataSourceInspection
     * @param $data string to insert to database
     * @return bool result
     */
    public function insert_db($data){
        $sql = $this->db->prepare("INSERT IGNORE INTO ". $this->field ." (data) VALUES (:data)");
        $sql->bindParam(':data', $data);
        $sql->execute();
        return $this->db->lastInsertId($this->field);
    }
}
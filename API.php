<?php
include ('ConnectionDB.php');
include ('Capsule.php');
include ('Key.php');
include ('kFrag.php');

class API
{
    //Attributes
    private $capsule;
    private $kFrag;
    private $delegating;
    private $receiving;
    private $verifying;
    private $db;

    function __construct($data)
    {
        $data = json_decode($data);

        $this->db = ConnectionDB::getInstance();

        // this will bite me in the ass, i swear to god...
        $this->capsule = (isset($data->capsule) ? $data->capsule : '');
        $this->kFrag = (isset($data->kFrag) ? $data->kFrag : '');
        $this->delegating = (isset($data->delegating) ? $data->delegating : '');
        $this->receiving = (isset($data->receiving) ? $data->receiving : '');
        $this->verifying = (isset($data->verifying) ? $data->verifying : '');
    }

    function verifyMethod($method, $route)
    {
        //Verifies what is the method sent.
        switch ($method) {
            case 'GET':
                # When the method is GET, returns the client
                return self::doGet();
            case 'POST':
                # When the method is POST, includes a new client
                if (empty($route[1])) {
                    return self::doPost();
                } else {
                    return $arr_json = array('status' => 404);
                }
            default:
                # When the method is different of the previous methods, return an error message.
                return array('status' => 405);
        }
    }

    function doGet()
    {
        $capsule = (new Capsule($this->capsule))->getData();
        $kFrag = (new kFrag($this->kFrag))->getData();
        $delegating = (new Key($this->delegating))->getData();
        $receiving = (new Key($this->receiving))->getData();
        $verifying = (new Key($this->verifying))->getData();

        try {
            return (new cFrag($capsule, $kFrag, $delegating, $receiving, $verifying))->reEncrypt();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function doPost()
    {
        if (empty($this->capsule) || empty($this->kFrag) || empty($this->delegating) || empty($this->receiving) || empty($this->verifying)) {
            return "[ERROR] [cFrag] -> null values in constructor";
        }
        //POST method
        $id = Capsule::insert($this->capsule);
        $kFid = kFrag::insert($this->kFrag);
        $del_id = Key::insert($this->delegating);
        $recv_id = Key::insert($this->receiving);
        $ver_id = Key::insert($this->verifying, 1);

        /*
         *  ToDo: stupid encryption here or something that allows us to reverse the lookup...
         */

        return "ENCODED_STRING";
    }

}


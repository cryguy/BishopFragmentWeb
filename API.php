<?php
include ('ConnectionDB.php');
include('class/Capsule.php');
include('class/Key.php');
include('class/kFrag.php');

class API
{
    // todo : documentation for API and index.php

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

    function reroute($method, $route)
    {
        switch ($method) {
            case 'GET':
                return self::doGet();
            case 'POST':
                if (empty($route[1])) {
                    return self::doPost();
                } else {
                    return $arr_json = array('status' => 404);
                }
            default:
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

        if (empty($this->capsule) || empty($this->kFrag) || empty($this->delegating) || empty($this->receiving) || empty($this->verifying)) {
            return json_encode(array('status' => 400, 'data' => "[ERROR] -> data missing from database, did you mess up the data sent?"));
        }

        try {
            return array('status' => 200 , 'data' => (new cFrag($capsule, $kFrag, $delegating, $receiving, $verifying))->reEncrypt());
        } catch (Exception $e) {
            return array('status' => 500 , 'data' => $e->getMessage());
        }
    }

    public function doPost()
    {
        if (empty($this->capsule) || empty($this->kFrag) || empty($this->delegating) || empty($this->receiving) || empty($this->verifying)) {
            return json_encode(array('status' => 400, 'data' => "[ERROR] -> null values in constructor"));
        }

        $id = Capsule::insert($this->capsule);
        $kFid = kFrag::insert($this->kFrag);
        $del_id = Key::insert($this->delegating);
        $recv_id = Key::insert($this->receiving);
        $ver_id = Key::insert($this->verifying, 1);

        return json_encode(array('status' => 200, 'data' => base64_encode(json_encode(array("capsule" => $id, "kFrag" => $kFid, "delegating" => $del_id, "receiving" => $recv_id, "verifying" => $ver_id)))));
    }
}


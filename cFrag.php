<?php
include('ConnectionDB.php');


class cFrag
{
    private $capsule;
    private $kFrag;
    private $delegating;
    private $receiving;
    private $verifying;
    private $db;
    /**
     * kFrag constructor.
     * @param $capsule string of capsule in base64
     * @param $kFrag string of kFrag in base64
     * @param $delegating string of delegating key's "X" Coordinate - compressed weierstrass form
     * @param $receiving string of receiving key's "X" Coordinate - compressed weierstrass form
     * @param $verifying string of verifying ed25519 pub key in X509 form
     */
    public function __construct($capsule, $kFrag, $delegating, $receiving, $verifying)
    {
        $this->db = ConnectionDB::getInstance();
        $this->capsule = $capsule;
        $this->kFrag = $kFrag;
        $this->delegating = $delegating;
        $this->receiving = $receiving;
        $this->verifying = $verifying;
    }


    /**
     * reEncrypts kFrag into cFrag
     * @return string
     * @throws Exception
     */
    public function reEncrypt()
    {
        // do some sanity checks
        // 1 1 1 1 1 = 1 = some or all is empty
        // 0 0 0 1 0 = 1 = some or all is empty
        // 0 0 0 0 0 = 0 = all filled = continue exec
        if (empty($this->capsule) || empty($this->kFrag) || empty($this->delegating) || empty($this->receiving) || empty($this->verifying))
            throw new Exception("[ERROR] [cFrag] -> null values in constructor");

        $output = null;
        $result = null;
        exec("java -jar Bishop.jar " . $this->delegating . " " . $this->receiving . " " . $this->verifying . " " . $this->capsule . " " . $this->kFrag,$output, $result);
        if ($result == 0)
            return $output;
        throw new Exception("[ERROR] [cFrag] -> java exited with non 0 exit code");
    }

    public function __toString()
    {
        try {
            return $this->reEncrypt();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
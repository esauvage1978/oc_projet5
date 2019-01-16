<?php

namespace ES\Core\Toolbox;

class Flash{

    private $_flash;

    const ERREUR="alert-danger";
    const SUCCES="alert-success";
    const INFO="alert-info";
    const WARNING="alert-warning";

    public function __construct(){

    }

    public function writeError($message)
    {
        $this->write(self::ERREUR,$message);
    }
    public function writeInfo($message)
    {
        $this->write(self::INFO,$message);
    }
    public function writeSucces($message)
    {
        $this->write(self::SUCCES,$message);
    }
    public function writeWarning($message)
    {
        $this->write(self::WARNING,$message);
    }
    private function write($key, $message){
        $_SESSION[$this->_flash][$key] = $message;
    }

    public function isPresent(){
        return isset($_SESSION[$this->_flash]);
    }

    public function read(){
        $retour = $_SESSION[$this->_flash];
        unset( $_SESSION[$this->_flash]);
        return $retour;
    }


}
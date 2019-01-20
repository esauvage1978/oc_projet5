<?php

namespace ES\Core\Toolbox;
use ES\Core\Toolbox\Request;

class Flash{

    private $_flash;
    private $_request;
    const ERROR="alert-danger";
    const SUCCES="alert-success";
    const INFO="alert-info";
    const WARNING="alert-warning";

    public function __construct()
    {
        $this->_flash='esflash';
        $this->_request=new Request();
    }

    public function writeError($message)
    {
        $this->write(self::ERROR,$message);
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
    private function write($key, $message)
    {
        $datas[$key]=$message;
        if($this->hasFlash())
        {
            $datas=array_merge($datas,$this->read());
        }

        $this->_request->setSessionValue($this->_flash,$datas);
    }

    public function hasFlash(){
        return $this->_request->hasSessionValue($this->_flash);
    }

    public function read()
    {
        $retour = $this->_request->getSessionValue($this->_flash,Request::TYPE_ARRAY);
        $this->_request->unsetSessionValue ($this->_flash);
        return $retour;
    }


}
<?php

namespace ES\Core\Toolbox;

class Request
{
    private $_get;
    public $_post;
    private $_session;
    private $_cookie;

    const TYPE_STRING ='0';
    const TYPE_MAIL ='1';
    const TYPE_INT ='2';
    const TYPE_BOOLEAN='3';
    const TYPE_ARRAY='4';

    public function __construct($datas=[])
    {
        $this->_get = isset($datas['get'])?$datas['get']:null;
        $this->_post = isset($datas['post'])?$datas['post']:null;
        $this->_session = isset($datas['session'])?$datas['session']:null;
        $this->_cookie = isset($datas['cookie'])?$datas['cookie']:null;
    }

    public function getGetValue($key,$type=self::TYPE_STRING)
    {
        return isset($this->_get[$key])?$this->securise($this->_get[$key],$type):null;
    }
    #region POST
    public function getPostValue($key,$type=self::TYPE_STRING)
    {
        return isset($this->_post[$key])?$this->securise($this->_post[$key],$type):null;
    }
    public function hasPost()
    {
        return (isset($this->_post) && !empty($this->_post));
    }
    public function hasPostValue($key) :bool
    {
        return isset($this->_post[$key]);
    }
    #endregion

    #region session
    public function getSessionValue($key,$type=self::TYPE_STRING)
    {
        return isset($this->_session[$key])?$this->securise($this->_session[$key],$type):null;
    }
    public function hasSessionValue($key) :bool
    {
        return isset($this->_session[$key]);
    }
    public function unsetSessionValue($key) 
    {
        unset($this->_session[$key]);
        unset($_SESSION[$key]);
    }
    #endregion

    private function securise($data, $type=self::TYPE_STRING)
    {
        $retour=null;
        switch($type)
        {
            case self::TYPE_STRING:
                $retour=(string)filter_var($data);
                break;
            case self::TYPE_ARRAY:
                $retour=$data;
                break;
            case self::TYPE_INT:
                $retour=filter_var($data,FILTER_VALIDATE_INT );
                break;
            case self::TYPE_MAIL:
                $retour=filter_var($data,FILTER_VALIDATE_EMAIL );
                break;
            case self::TYPE_BOOLEAN:
                $retour=filter_var($data,FILTER_VALIDATE_BOOLEAN );
                break;
            default:
                $retour=htmlentities($data);
                break;
        }
        return $retour;
    }
}

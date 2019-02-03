<?php

namespace ES\Core\Toolbox;

class Request
{
    private $_get;
    private $_post;
    private $_cookie;

    const TYPE_STRING ='0';
    const TYPE_MAIL ='1';
    const TYPE_INT ='2';
    const TYPE_BOOLEAN='3';
    const TYPE_ARRAY='4';
    const TYPE_HTMLENTITY='5';

    public function __construct( $get=null, $post=null, $cookie=null)
    {
        $this->_get = isset($get)?$get:null;
        $this->_post = isset($post)?$post:null;
        $this->_cookie = isset($cookie)?$cookie:null;
    }
    #region GET
    public function getGetValue($key, $type=self::TYPE_STRING)
    {
        return isset($this->_get[$key])?$this->securise($this->_get[$key],$type):null;
    }
    public function hasGetValue($key) :bool
    {
        return isset($this->_get[$key]);
    }

    #endregion

    #region POST
    public function getPostValue($key, $type=self::TYPE_STRING)
    {
        return isset($this->_post[$key])?$this->securise($this->_post[$key],$type):null;
    }
    public function hasPost()
    {
        return !empty($this->_post);
    }
    public function hasPostValue($key) :bool
    {
        return isset($this->_post[$key]);
    }
    public function getPost()
    {
        return $this->_post;
    }
    public function unsetPost($key)
    {
        unset($this->_post[$key]);
    }
    #endregion

    #region session
    public function getSessionValue($key, $type=self::TYPE_STRING)
    {
        return isset($_SESSION[$key])?$this->securise($_SESSION[$key],$type):null;
    }
    public function hasSession() :bool
    {
        return isset($_SESSION);
    }
    public function hasSessionValue($key) :bool
    {
        return isset($_SESSION[$key]);
    }
    public function unsetSessionValue($key)
    {
        unset($_SESSION[$key]);
    }
    public function setSessionValue($key, $value)
    {
        $_SESSION[$key]=$value;
    }
    #endregion

    private function securise($data, $type=self::TYPE_STRING)
    {
        $retour=null;
        $erreur=false;
        switch($type)
        {
            case self::TYPE_STRING:
                $retour=(string)filter_var($data);
                break;
            case self::TYPE_ARRAY:
                $retour=is_array($data)?$data:$erreur;
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

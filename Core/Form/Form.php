<?php

namespace ES\Core\Form;

use ES\Core\Form\WebControlsStandard\InputToken;

/**
 * Class Form
 * @package Core\Form
 */
abstract class Form implements \ArrayAccess
{
    private $_controls=[];
    public $methode=self::METHODE_POST;

    const METHODE_POST='POST';
    const METHODE_GET='GET';

    private $_data;
    private $_byName;


    abstract public function render();
    abstract public function check();

    protected $_formName;

    public function __toString()
    {
        return static::render();
    }

    public function renderControl($key,$surround=true)
    {
        if(!$surround) {
            return $this->_controls[$key]->render();
        }
        return $this->surroundControl( $this->_controls[$key]->render());
    }

    public function renderButton($key)
    {
        return $this->renderControl($key,false);
    }

    protected function surroundControl($control) :string
    {
        return '<div class="form-group">' . $control . '</div>';
    }

    protected function getAction($url,$fileUpload=false):string
    {
        return '<form id="' . $this->getFormName() .
            '" method="'. $this->methode .
            '" action="'. $url .'" '.
            ($fileUpload?'enctype="multipart/form-data':''). 
            '">';
    }
    protected function createUrl():string
    {
        return '##INDEX##' . implode('/',func_get_args());
    }
    protected function getFormName()
    {
        $elements=explode('\\', get_class($this));
        return array_pop($elements);
    }

    public function getText($key)
    {
        return $this->_controls[$key]->getText();
    }

    protected function setText($datas=[],$byName=true)
    {
        foreach($datas as $key=>$value) {
            if(!$byName) {
                if ($this->_controls[$key]->isWritable ) {
                    $this->_controls[$key]->setText($value);
                }
            } else {
                foreach ($this->_controls as $control) {
                    if($control->getName()==$key && $control->isWritable) {
                        $control->setText($value);
                    }
                }
            }
        }
    }

    #region ArrayAccess
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_controls[] = $value;
        } else {
            $this->_controls[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->_controls[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->_controls[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->_controls[$offset]) ? $this->_controls[$offset] : null;
    }
    #endregion
}
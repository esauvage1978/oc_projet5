<?php

namespace ES\Core\Form;

use ES\Core\Form\WebControlsStandard\InputToken;

/**
 * Class Form
 * @package Core\Form
 */
abstract class Form implements \ArrayAccess
{
    /**
     * Summary of TOKEN
     */
    const TOKEN=0;

    private $_controls=[];
    public $methode=self::METHODE_POST;

    const METHODE_POST='POST';
    const METHODE_GET='GET';

    private $_data;
    private $_byName;

    abstract public function render();
    abstract public function check();

    /**
     * Fonction permettant l'ajout du contrôle token à la collection
     * initialisation des prefixes de contrôle
     * initialisation des valeurs text par défaut
     * @param mixed $data
     */
    public function postConstruct($datas,$byName=true)
    {
        //initialisation de la variable de session de formulaire pour CSRF
        $formName=$this->getFormName ();
        if( isset($_SESSION['formulaire']['CSRF'][$formName]) ){
            $_SESSION['formulaire']['CSRF'][$formName]['old'.ES_TOKEN ]=
                $_SESSION['formulaire']['CSRF'][$formName][ES_TOKEN ];
        }
        $_SESSION['formulaire']['CSRF'][$formName][ES_TOKEN ]=bin2hex(random_bytes(32));


        $this->_controls[self::TOKEN]=new InputToken();
        $this->_controls[self::TOKEN]->text =
            $_SESSION['formulaire']['CSRF'][$formName][ES_TOKEN];

        $this->setPrefixeToControl();
        $this->setText($datas,$byName);

    }

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

    protected function getAction($url):string
    {
        return '<form id="' . $this->getFormName() . '" method="'. $this->methode .'" action="##INDEX##'. $url .'">';
    }

    public function checkToken():bool
    {
        $checkOK=true;

        if(!$this->_controls[self::TOKEN]->check()) {
            $checkOK=false;
        }

        return $checkOK;
    }
    public function renderToken()
    {
        return $this->renderControl(self::TOKEN,false);
    }

    private function setPrefixeToControl()
    {
        $prefix=$this->getFormName();
        foreach ($this->_controls as $control) {
            $control->prefixeFormName=$prefix ;
        }
    }

    protected function getFormName()
    {
        $elements=explode('\\', get_class($this));
        return array_pop($elements);
    }

    public function text($key)
    {
        return $this->_controls[$key]->text;
    }

    protected function setText($datas=[],$byName=true)
    {
        foreach($datas as $key=>$value) {
            if(!$byName) {
                $this->_controls[$key]->text =$value;
            } else {
                foreach ($this->_controls as $control) {
                    if($control->getName()==$key) {
                        $control->text=$value;
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
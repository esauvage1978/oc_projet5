<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 27/12/2018
 * Time: 09:21
 */

namespace ES\Core\Form;

use ES\Core\Toolbox\Request;

/**
 * Class Form
 * @package Core\Form
 */
class Form
{
    const OPTIONS_REQUIRED=0;
    const OPTIONS_TYPE=1;
    const OPTIONS_CSSCLASS=2;
    const OPTIONS_ID=3;
    const OPTIONS_ROWS=4;
    const OPTIONS_MAXLENGHT=5;
    const OPTIONS_PLACEHOLDER=6;
    const OPTIONS_LABEL=7;

    const OPTIONS_TYPE_TEXT='text';
    const OPTIONS_TYPE_EMAIL='email';
    const OPTIONS_TYPE_ETOILE='password';
    const OPTIONS_TYPE_HIDDEN='hidden';
    const OPTIONS_TYPE_TEXTAREA='textarea';

    private $_type;
    private $_required;
    private $_cssClass;
    private $_id;
    private $_maxlenght;
    private $_placeholder;
    private $_label;

    /**
     * Summary of $data
     * @var mixed
     */
    private  $_data;

    /**
     * Form constructor.
     * @param $data
     */
    public function __construct($data)
    {
        //Passage dans $data d'un tableau ou de la classe Request
        $this->_data = $data;
    }


    protected function getValue($index)
    {
        $data=null;

        if(is_array ( $this->_data))
        {
            $data=array_key_exists($index,$this->_data)?$this->_data[$index]:null;
        }
        else
        {
            $data=$this->_data->getPostValue($index);
        }

        return isset($data)? $data:null;
    }

    #region Traitement des options
    private function makeOptions($options,$name)
    {
        $this->_type = self::getType($options);
        $this->_required=self::getRequired ($options);
        $this->_cssClass= self::getCssClass($options);
        $this->_id = self::getId($options,$name);
        $this->_maxlenght=self::getMaxLenght($options);
        $this->_placeholder =self::getPlaceholder($options);
        $this->_label =self::getLabel($options,$name);
    }

    private static function getId($options,$name)
    {
        return ' id="'. (isset($options[self::OPTIONS_ID]) ? $options[self::OPTIONS_ID] : $name) . '" ';
    }
    private static function getRequired($options)
    {
        return isset($options[self::OPTIONS_REQUIRED]) ? ' required ' : '';
    }
    private static function getType($options)
    {
        return isset($options[self::OPTIONS_TYPE]) ? $options[self::OPTIONS_TYPE] : 'text';
    }
    private static function getCssClass($options)
    {
        return isset($options[self::OPTIONS_CSSCLASS]) ? ' class="'. $options[self::OPTIONS_CSSCLASS]. '" ' : '';
    }
    private static function getMaxlenght($options)
    {
        return isset($options[self::OPTIONS_MAXLENGHT]) ? ' maxlenght="'. $options[self::OPTIONS_MAXLENGHT]. '" ' : '';
    }
    private static function getPlaceholder($options)
    {
        return isset($options[self::OPTIONS_PLACEHOLDER]) ? ' placeholder="'. $options[self::OPTIONS_PLACEHOLDER]. '" ' : '';
    }
    private static function getLabel($options,$name)
    {
        return isset($options[self::OPTIONS_LABEL]) ? ' <label for="'. $name .'">' . $options[self::OPTIONS_LABEL] . '</label>' : '';
    }

    #endregion

    /**
     * @param $name
     * @return string
     */
    public function submit($name,$label,$options= [])
    {
            $this->makeOptions($options,$name);

        return '<button type="submit" name="'. $name . '" ' . $this->_cssClass . $this->_id . '>'. $label . '</button>';
    }
    public function input($name, $options = [])
    {
        $this->makeOptions($options,$name);


        if ($this->_type === self::OPTIONS_TYPE_TEXTAREA)
        {
            $input = '<textarea name="'. $name . '" '.
                $this->_required . $this->_id . $this->_cssClass  . $this->_placeholder
                . ' >' . $this->getValue($name)
                . '</textarea>';
        }
        else
        {
            $input = '<input type="'. $this->_type .'" name="'. $name . '" '.
                $this->_maxlenght . $this->_required . $this->_id . $this->_cssClass . $this->_placeholder
                . ' value="' . $this->getValue($name) . '" />';
        }
        return $this->_label . $input;
    }
    public function input_password($name, $options = [])
    {
        $options[$this::OPTIONS_TYPE]=self::OPTIONS_TYPE_ETOILE;
        $options[$this::OPTIONS_MAXLENGHT]='60';
        $options[$this::OPTIONS_REQUIRED]=true;
        return $this->input($name,$options);
    }

}
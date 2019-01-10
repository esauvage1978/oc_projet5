<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 27/12/2018
 * Time: 09:21
 */

namespace ES\Core\Form;


/**
 * Class Form
 * @package Core\Form
 */
class Form
{
    /**
     * @var string
     */
    public $surround = 'p';
    /**
     * @var array
     */
    protected $data;

    /**
     * Form constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $name
     * @return string
     */
    public function input($name)
    {
        return $this->surround('<input type="text" name="'. $name .'" value="' . $this->getValue($name) .'"/>');
    }

    /**
     * @param $html
     * @return string
     */
    protected function surround($html)
    {
        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    /**
     * @param $index
     * @return mixed|null
     */
    protected function getValue($index)
    {
        return isset($this->data[$index])? $this->data[$index]:null;
    }

    /**
     * @param $name
     * @return string
     */
    public function submit($name)
    {
        return $this->surround('<button type="submit" name="'. $name . '">Envoyer</button>');
    }
}
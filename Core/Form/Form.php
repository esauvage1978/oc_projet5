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
    public $controls=[];

    public function render($key)
    {
        return $this->controls[$key]->render();
    }

    public function text($key)
    {
        return $this->controls[$key]->text();
    }

    public function select($name, $options = [])
    {
        $this->makeOptions($options,$name);



    }


}
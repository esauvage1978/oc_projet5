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
        return $this->controls[$key]->text;
    }

    protected function setText($datas=[])
    {
        foreach($datas as $name=>$value) {
            foreach($this->controls as $control) {
                if( $control->getName() === $name) {
                    $control->text=$value;
                }
            }

        }


    }


}
<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 28/12/2018
 * Time: 10:39
 */

namespace ES\Core\Form;

use ES\Core\Form\Form;


class BootStrapForm extends Form
{

    /**
     * @param $html
     * @return string
     */
    protected function surround($html)
    {
        return "<div class=\"form-group\">{$html}</div>";
    }
    /**
     * @param $name
     * @return string
     */
    public function input($name)
    {
        return $this->surround('<label for="'. $name .'">'. $name .'</label><input type="text" id="'. $name .'" name="'. $name .'" value="' . $this->getValue($name) .'" class="form-control"/>');
    }
    public function submit($name)
    {
        return $this->surround('<button type="submit" name="" class="btn btn-primary">Envoyer</button>');
    }
}
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
     * @param $name
     * @return string
     */
    public function input($name, $options = [])
    {
        $options[parent::OPTIONS_CSSCLASS]='form-control';
        return parent::input($name,$options);
    }

    public function input_password($name,$options = array())
    {
        $options[parent::OPTIONS_CSSCLASS]='form-control';
        return parent::input_password($name,$options);
    }

    public function submit($name,$label,$options= [])
    {
        $options[parent::OPTIONS_CSSCLASS]='btn';
        return parent::submit($name,$label,$options);
    }

    public function submit_primary($name,$label,$options= [])
    {
        $options[parent::OPTIONS_CSSCLASS]='btn btn-primary';
        return parent::submit($name,$label,$options);
    }

    protected function chooseClassCssValid($check):string
    {
        $retour='';
        if($check==Form::OPTIONS_VALID_YES) {
            $retour =' is-valid ';
        } else if($check==Form::OPTIONS_VALID_NO) {
            $retour =' is-invalid ';
        }
        return $retour;
    }
}
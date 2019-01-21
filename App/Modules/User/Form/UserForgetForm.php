<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\App\Modules\User\Form\WebControls\ButtonRecupere;
use ES\App\Modules\User\Form\WebControls\InputLogin;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForgetForm extends Form
{

    const BUTTON=0;
    const LOGIN=1;


    public function __construct($data)
    {
        $this->controls[self::BUTTON]=new ButtonRecupere();
        $this->controls[self::LOGIN]=new InputLogin($data);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->controls[self::LOGIN]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

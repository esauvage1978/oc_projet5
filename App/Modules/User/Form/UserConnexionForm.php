<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\App\Modules\User\Form\WebControls\ButtonConnexion;
use ES\App\Modules\User\Form\WebControls\InputLogin;
use ES\App\Modules\User\Form\WebControls\InputSecret;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserConnexionForm extends Form implements IForm
{

    const BUTTON=0;
    const LOGIN=1;
    const SECRET=2;

    public function __construct($data)
    {
        $this->controls[self::BUTTON]=new ButtonConnexion();
        $this->controls[self::LOGIN]=new InputLogin($data);
        $this->controls[self::SECRET]=new InputSecret($data);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->controls[self::LOGIN]->check()) {
            $checkOK=false;
        }

        if(!$this->controls[self::SECRET]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

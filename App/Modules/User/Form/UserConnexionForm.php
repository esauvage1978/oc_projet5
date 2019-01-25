<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\Core\Form\WebControlsStandard\ButtonConnexion;
use ES\Core\Form\WebControlsStandard\InputLogin;
use ES\Core\Form\WebControlsStandard\InputSecret;

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

    public function __construct($datas=[])
    {
        $this->controls[self::BUTTON]=new ButtonConnexion();
        $this->controls[self::LOGIN]=new InputLogin();
        $this->controls[self::SECRET]=new InputSecret();

        $this->setText($datas) ;
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

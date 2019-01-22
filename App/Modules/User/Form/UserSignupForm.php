<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\Core\Toolbox\Auth;
use ES\App\Modules\User\Form\WebControls\ButtonModifier;
use ES\App\Modules\User\Form\WebControls\InputSecretNew;
use ES\App\Modules\User\Form\WebControls\InputSecretConfirm;
use ES\App\Modules\User\Form\WebControls\InputMail;
use ES\App\Modules\User\Form\WebControls\InputIdentifiant;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserSignupForm extends Form implements IForm
{
    const BUTTON=0;
    const SECRET_NEW=1;
    const SECRET_CONFIRM=2;
    const MAIL=3;
    const IDENTIFIANT=4;

    use checkSecret;

    public function __construct($data)
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::SECRET_NEW]=new InputSecretNew($data);
        $this->controls[self::SECRET_CONFIRM]=new InputSecretConfirm($data);
        $this->controls[self::MAIL]=new InputMail($data);
        $this->controls[self::IDENTIFIANT]=new InputIdentifiant($data);
    }

    public function check():bool
    {
        $checkOK=true;

        $checkOK=$this->checkSecretNewAndConfirm();

        if(!$this->controls[self::IDENTIFIANT]->check()) {
            $checkOK=false;
        }

        if(!$this->controls[self::MAIL]->check()) {
            $checkOK=false;
        }


        return $checkOK ;
    }


}

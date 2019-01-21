<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\Core\Toolbox\Auth;
use ES\App\Modules\User\Form\WebControls\ButtonModifier;
use ES\App\Modules\User\Form\WebControls\InputSecretNew;
use ES\App\Modules\User\Form\WebControls\InputSecretConfirm;
use ES\App\Modules\User\Form\WebControls\InputHash;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForgetChangeForm extends Form implements IForm
{
    const BUTTON=0;
    const SECRET_NEW=1;
    const SECRET_CONFIRM=2;
    const HASH=3;


    public function __construct($data)
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::SECRET_NEW]=new InputSecretNew($data);
        $this->controls[self::SECRET_CONFIRM]=new InputSecretConfirm($data);
        $this->controls[self::HASH]=new InputHash($data);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->controls[self::SECRET_NEW]->check()) {
            $checkOK=false;
        }

        if(!$this->controls[self::SECRET_CONFIRM]->check()) {
            $checkOK=false;
        }

        if(!Auth::passwordCompare($this->controls[self::SECRET_NEW]->text(),
            $this->controls[self::SECRET_CONFIRM]->text(),false)) {

            $this->controls[self::SECRET_NEW]->setIsInvalid('Le mot de passe et/ou sa confirmation sont invalides' );
            return false;
        }
        return $checkOK ;
    }
}

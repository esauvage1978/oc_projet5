<?php

namespace ES\App\Modules\User\Form;
use ES\Core\Toolbox\Auth;

/**
 * checkSecret short summary.
 *
 * checkSecret description.
 *
 * @version 1.0
 * @author ragus
 */
trait checkSecret
{
    public function checkSecretNewAndConfirm():bool
    {
        $checkOK=true;

        if(!$this->controls[self::SECRET_NEW]->check()) {
            $checkOK=false;
        }

        if(!$this->controls[self::SECRET_CONFIRM]->check()) {
            $checkOK=false;
        }

        if(empty($this->controls[self::SECRET_NEW]->text()) ||
            empty($this->controls[self::SECRET_CONFIRM]->text()) ||
            !Auth::passwordCompare($this->controls[self::SECRET_NEW]->text(),
            $this->controls[self::SECRET_CONFIRM]->text(),false)) {

            $this->controls[self::SECRET_NEW]->setIsInvalid('Le mot de passe et/ou sa confirmation sont invalides' );
            return false;
        }
        return $checkOK ;
    }
}
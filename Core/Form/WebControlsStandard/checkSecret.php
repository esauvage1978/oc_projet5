<?php

namespace ES\Core\Form\WebControlsStandard;

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

        if(!$this[self::SECRET_NEW]->check()) {
            $checkOK=false;
        }

        if(!$this[self::SECRET_CONFIRM]->check()) {
            $checkOK=false;
        }

        if(empty($this[self::SECRET_NEW]->text) ||
            empty($this[self::SECRET_CONFIRM]->text) ||
        !Auth::passwordCompare($this[self::SECRET_NEW]->text,
            $this[self::SECRET_CONFIRM]->text,false)) {

            $this[self::SECRET_NEW]->setIsInvalid(
                'Le mot de passe et/ou sa confirmation sont invalides');
            return false;
        }
        return $checkOK ;
    }
}
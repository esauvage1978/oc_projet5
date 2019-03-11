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

        if(empty($this[self::SECRET_NEW]->getText()) ||
            empty($this[self::SECRET_CONFIRM]->getText()) ||
        !Auth::passwordCompare($this[self::SECRET_NEW]->getText(),
            $this[self::SECRET_CONFIRM]->getText(),false)) {

            $this[self::SECRET_NEW]->setIsInvalid(MSG_USER_INVALID_SECRET_AND_CONF
                );
            return false;
        }
        return $checkOK ;
    }
}
<?php

namespace ES\App\Modules\User\Form;



/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForgetChangeForm extends UserForm
{
    protected static $_loginInvalid;

    public function checkForm():bool
    {
        $checkOK=true;

        if(! $this->checkSecretNewAndConfirm()) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

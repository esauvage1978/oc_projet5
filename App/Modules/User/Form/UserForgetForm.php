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
class UserForgetForm extends UserForm
{
    protected static $_loginInvalid;

    public function checkForm():bool
    {
        $checkOK=true;

        if(! $this->checkLogin()) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

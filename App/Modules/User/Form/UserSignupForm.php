<?php

namespace ES\App\Modules\User\Form;

Use ES\Core\ToolBox\Auth;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserSignupForm extends UserForm
{

    public function checkForm():bool
    {
        $checkOK=true;

        if(!$this->checkIdentifiant()) {
            $checkOK =false;
        }

        if(!$this->checkMail()) {
            $checkOK =false;
        }

        if(!$this->checkSecretNewAndConfirm()) {
            $checkOK =false;
        }

        return $checkOK ;
    }
}

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
class UserModifyForm extends UserForm
{

    public function checkForm():bool
    {
        $checkOK=true;

        if(!$this->checkIdHidden() ) {
            $checkOK=false;
        }

        if(!$this->checkIdentifiant() ) {
            $checkOK=false;
        }

        if(!$this->checkMail() ) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

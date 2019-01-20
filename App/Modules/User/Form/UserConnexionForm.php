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
class UserConnexionForm extends UserForm
{
    public function checkForm():bool
    {
        $checkOK=true;

        if(!$this->checkLogin()) {
            $checkOK=false;
        }

        if(!$this->checkSecret() ) {

            $checkOK=false;
        }

        return $checkOK ;
    }
}

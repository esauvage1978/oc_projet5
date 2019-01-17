<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\BootStrapForm;
/**
 * UserForm short summary.
 *
 * UserForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForm extends BootStrapForm
{
    public function identifiant()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=40;
        $options[parent::OPTIONS_LABEL]='Identifiant';
        $options[parent::OPTIONS_PLACEHOLDER]='Identifiant';
        return parent::input('identifiant',$options);
    }

    public function login()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_LABEL]='Identifiant ou adresse mail';
        $options[parent::OPTIONS_PLACEHOLDER]='Identifiant ou adresse mail';
        return parent::input('login',$options);
    }

    public function hash()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_TYPE]=parent::OPTIONS_TYPE_HIDDEN;
        return parent::input('hash',$options);
    }
    public function idHidden()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_TYPE]=parent::OPTIONS_TYPE_HIDDEN;
        return parent::input('idHidden',$options);
    }
    public function mail()
    {
        $options[parent::OPTIONS_TYPE]=parent::OPTIONS_TYPE_EMAIL;
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_LABEL]='Adresse mail';
        $options[parent::OPTIONS_PLACEHOLDER]='Adresse mail';
        return parent::input('mail',$options);
    }

    public function passwordOld()
    {
        $options[parent::OPTIONS_LABEL]='Ancien mot de passe';
        $options[parent::OPTIONS_PLACEHOLDER]='Ancien mot de passe';
        return parent::input_password('pwdOld',$options);
    }
    public function passwordNew()
    {
        $options[parent::OPTIONS_LABEL]='Nouveau mot de passe';
        $options[parent::OPTIONS_PLACEHOLDER]='Nouveau mot de passe';
        return parent::input_password('pwdNew',$options);
    }
    public function password()
    {
        $options[parent::OPTIONS_LABEL]='Mot de passe';
        $options[parent::OPTIONS_PLACEHOLDER]='Mot de passe';
        return parent::input_password('pwd',$options);
    }
    public function passwordConfirmation()
    {
        $options[parent::OPTIONS_LABEL]='Confirmation du mot de passe';
        $options[parent::OPTIONS_PLACEHOLDER]='Confirmation du mot de passe';
        return parent::input_password('pwdConfirmation',$options);
    }
}
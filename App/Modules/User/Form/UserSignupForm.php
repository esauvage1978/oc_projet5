<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\Core\Form\WebControlsStandard\checkSecret;
use ES\Core\Form\WebControlsStandard\ButtonCreate;
use ES\Core\Form\WebControlsStandard\InputSecretNew;
use ES\Core\Form\WebControlsStandard\InputSecretConfirm;
use ES\Core\Form\WebControlsStandard\InputMail;
use ES\App\Modules\User\Form\WebControls\InputIdentifiant;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserSignupForm extends Form
{
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const MAIL=4;
    const IDENTIFIANT=5;

    public static $name_identifiant=InputIdentifiant::NAME;

    use checkSecret;

    public function __construct($datas=[],$byName=true)
    {
        $this[self::BUTTON]=new ButtonCreate();
        $this[self::SECRET_NEW]=new InputSecretNew();
        $this[self::SECRET_CONFIRM]=new InputSecretConfirm();
        $this[self::MAIL]=new InputMail();
        $this[self::IDENTIFIANT]=new InputIdentifiant();

        $this[self::SECRET_NEW]->label='Votre mot de passe';
        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this[self::IDENTIFIANT]->check()) {
            $checkOK=false;
        }

        if(!$this[self::MAIL]->check()) {
            $checkOK=false;
        }

        if(!$this->checkSecretNewAndConfirm()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction('user.signup') .
               $this->renderToken() .
               $this->renderControl(self::IDENTIFIANT) .
               $this->renderControl(self::MAIL) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }

}

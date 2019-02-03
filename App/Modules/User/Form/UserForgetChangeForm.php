<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;

use ES\Core\Form\WebControlsStandard\ButtonModifier;
use ES\Core\Form\WebControlsStandard\InputSecretNew;
use ES\Core\Form\WebControlsStandard\InputSecretConfirm;
use ES\Core\Form\WebControlsStandard\InputHash;
use ES\Core\Form\WebControlsStandard\checkSecret;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForgetChangeForm extends Form
{
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const HASH=4;

    public function __construct($datas=[],$byName=true)
    {
        $this[self::BUTTON]=new ButtonModifier();
        $this[self::SECRET_NEW]=new InputSecretNew();
        $this[self::SECRET_CONFIRM]=new InputSecretConfirm();
        $this[self::HASH]=new InputHash();

        $this->postConstruct($datas,$byName) ;
    }

    use checkSecret;

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }
        if(! $this->checkSecretNewAndConfirm()) {
            $checkOK =false;
        }

        return $checkOK;
    }

    public function render() 
    {
        return $this->getAction('user.pwdforgetchange') .
               $this->renderToken() .
               $this->renderControl(self::HASH) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

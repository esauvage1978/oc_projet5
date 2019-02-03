<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;
use ES\Core\Form\WebControlsStandard\InputSecretNew;
use ES\Core\Form\WebControlsStandard\InputSecretConfirm;
use ES\Core\Form\WebControlsStandard\InputSecretOld;
use ES\Core\Form\WebControlsStandard\checkSecret;
/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserPwdChangeForm extends Form 
{
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const SECRET_OLD=4;

    use checkSecret;

    public function __construct($datas=[],$byName=true)
    {

        $this[self::BUTTON]=new ButtonModifier();
        $this[self::SECRET_NEW]=new InputSecretNew();
        $this[self::SECRET_CONFIRM]=new InputSecretConfirm();
        $this[self::SECRET_OLD]=new InputSecretOld();

        $this->postConstruct($datas,$byName) ;
    }


    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this->checkSecretNewAndConfirm()) {
            $checkOK=false;
        }

        if(!$this[self::SECRET_OLD]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction('user.pwdchange') .
               $this->renderToken() .
               $this->renderControl(self::SECRET_OLD) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) .
               '<div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">' .
               $this->renderButton(self::BUTTON) .
               '<a  href="##INDEX##user.modify" class="btn btn-secondary">Retour</a>' .
               '</div>'.'</form>';
    }
}

<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
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
class UserPwdChangeForm extends Form implements IForm
{
    const BUTTON=0;
    const SECRET_NEW=1;
    const SECRET_CONFIRM=2;
    const SECRET_OLD=3;

    use checkSecret;

    public function __construct($datas=[])
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::SECRET_NEW]=new InputSecretNew();
        $this->controls[self::SECRET_CONFIRM]=new InputSecretConfirm();
        $this->controls[self::SECRET_OLD]=new InputSecretOld();

        $this->setText($datas) ;
    }


    public function check():bool
    {
        $checkOK=true;

        $checkOK=$this->checkSecretNewAndConfirm();

        if(!$this->controls[self::SECRET_OLD]->check()) {
            $checkOK=false;
        }


        return $checkOK ;
    }
}

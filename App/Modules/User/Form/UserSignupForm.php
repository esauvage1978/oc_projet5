<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\Core\Form\WebControlsStandard\checkSecret;
use ES\Core\Form\WebControlsStandard\ButtonModifier;
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
class UserSignupForm extends Form implements IForm
{
    const BUTTON=0;
    const SECRET_NEW=1;
    const SECRET_CONFIRM=2;
    const MAIL=3;
    const IDENTIFIANT=4;

    public static $name_identifiant=InputIdentifiant::NAME;

    use checkSecret;

    public function __construct($datas=[])
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::SECRET_NEW]=new InputSecretNew();
        $this->controls[self::SECRET_CONFIRM]=new InputSecretConfirm();
        $this->controls[self::MAIL]=new InputMail();
        $this->controls[self::IDENTIFIANT]=new InputIdentifiant();

        $this->setText($datas) ;
    }

    public function check():bool
    {
        $checkOK=true;

        $checkOK=$this->checkSecretNewAndConfirm();

        if(!$this->controls[self::IDENTIFIANT]->check()) {
            $checkOK=false;
        }

        if(!$this->controls[self::MAIL]->check()) {
            $checkOK=false;
        }


        return $checkOK ;
    }


}

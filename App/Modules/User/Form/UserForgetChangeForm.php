<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;

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
class UserForgetChangeForm extends Form implements IForm
{
    const BUTTON=0;
    const SECRET_NEW=1;
    const SECRET_CONFIRM=2;
    const HASH=3;

    public static $controlHashName='hash';

    public function __construct($datas=[])
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::SECRET_NEW]=new InputSecretNew();
        $this->controls[self::SECRET_CONFIRM]=new InputSecretConfirm();
        $this->controls[self::HASH]=new InputHash();

        $this->setText($datas) ;
    }

    use checkSecret;

    public function check():bool
    {
        return $this->checkSecretNewAndConfirm();
    }
}

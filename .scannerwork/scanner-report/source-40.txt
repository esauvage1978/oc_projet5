<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;

use ES\App\Modules\User\Form\WebControls\ButtonModifier;
use ES\App\Modules\User\Form\WebControls\InputSecretNew;
use ES\App\Modules\User\Form\WebControls\InputSecretConfirm;
use ES\App\Modules\User\Form\WebControls\InputHash;

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


    public function __construct($data)
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::SECRET_NEW]=new InputSecretNew($data);
        $this->controls[self::SECRET_CONFIRM]=new InputSecretConfirm($data);
        $this->controls[self::HASH]=new InputHash($data);
    }

    use checkSecret;

    public function check():bool
    {
        return $this->checkSecretNewAndConfirm(); 
    }
}

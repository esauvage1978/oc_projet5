<?php

namespace ES\Core\Form\WebControlsStandard;

use ES\Core\Form\WebControls\WebControlsInput;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class InputSecretConfirm extends WebControlsInput
{

    public function __construct()
    {
        $this->label ='Confirmation du mot de passe';
        $this->type= self::TYPE_SECRET;
        $this->name ='secretConfirm';
        $this->maxLength =100;
    }


}
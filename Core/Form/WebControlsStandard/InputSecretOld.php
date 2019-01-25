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
class InputSecretOld extends WebControlsInput
{

    public function __construct()
    {
        $this->label ='Ancien mot de passe';
        $this->type= self::TYPE_SECRET;
        $this->name ='secretOld';
        $this->maxLength =100;
    }


}
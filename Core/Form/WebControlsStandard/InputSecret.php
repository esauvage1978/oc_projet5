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
class InputSecret extends WebControlsInput
{

    public function __construct()
    {
        $this->label='Mot de passe';
        $this->type= self::TYPE_SECRET;
        $this->name='secret';
        $this->maxLength =100;
    }


}
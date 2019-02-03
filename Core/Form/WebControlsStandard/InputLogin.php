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
class InputLogin extends WebControlsInput
{

    public function __construct()
    {
        $this->label='Identifiant ou adresse mail';
        $this->name='login';
        $this->maxLength =100;
    }

    public function check():bool
    {
        $retour=true;

        if( !parent::check() || !parent::checkLenght(4,100) ) {
            $retour=false;
        }
        return $retour;
    }
}
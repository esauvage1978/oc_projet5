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
class InputMail extends WebControlsInput
{
    const NAME='mail';

    public function __construct()
    {
        $this->label='Adresse mail';
        $this->name=self::NAME;
        $this->type=parent::TYPE_EMAIL;
        $this->maxLength=100;
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text;
        if( empty($value) || !filter_var($value,FILTER_VALIDATE_EMAIL)) {

            $this->setIsInvalid(self::MSG_NOT_GOOD);
            $retour=false;

        } else if( !parent::checkLenght(4,100) ) {
            $retour=false;
        }
        return $retour;
    }
}
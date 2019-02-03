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
class InputToken extends WebControlsInput
{
    const NAME='TokenHidden';

    public function __construct()
    {
        $this->name=self::NAME;
        $this->type =parent::TYPE_HIDDEN;
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text;
        if( !parent::check()|| $value!= $_SESSION[ES_TOKEN] ) {
            $this->setIsInvalid('Ce formulaire est périmé !!');
            $retour=false;
        }
        return $retour;
    }
}
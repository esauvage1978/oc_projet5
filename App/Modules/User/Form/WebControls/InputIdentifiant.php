<?php

namespace ES\App\Modules\User\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsInput;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class InputIdentifiant extends WebControlsInput
{
    const NAME='identifiant';

    public function __construct()
    {
        $this->label ='Identifiant';
        $this->name=self::NAME;
        $this->helpBlock='L\'identifiant doit avoir entre 4 et 45 caractères.';
        $this->maxLength=45;
    }

    public function check():bool
    {
        $retour=true;
        if( !parent::check()|| !parent::checkLenght(4,$this->maxLength) ) {
            $retour=false;
        }
        return $retour;
    }

}
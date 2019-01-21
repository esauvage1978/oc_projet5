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

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Identifiant');
        $this->setName(self::NAME);
        $this->setRequired();
        $this->SetHelpBlock('L\'identifiant doit avoir entre 4 et 45 caractères.');
        $this->setMaxLength (45);
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text();
        if( empty($value)) {

            $this->setIsInvalid('L\'identifiant est vide');
            $retour=false;

        } else if (strlen($value)<=3 ||
            strlen($value)>45 ) {

            $this->setIsInvalid('La saisie doit être entre 4 et 45 caractères.');
            $retour=false;

        } else if (!(string)filter_var($value)) {

            $this->setIsInvalid('L\'identifiant est invalide');
            $retour=false;
        }
        return $retour;
    }

}
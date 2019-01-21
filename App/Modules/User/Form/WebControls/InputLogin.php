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
class InputLogin extends WebControlsInput
{

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Identifiant ou adresse mail');
        $this->setName('login');
        $this->setRequired();
        $this->setMaxLength (100);
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text();
        if( empty($value)) {

            $this->setIsInvalid('L\'identifiant ou le mail est vide');
            $retour=false;

        } else if (strlen($value)<=3 ||
            strlen($value)>100 ) {

            $this->setIsInvalid('La saisie doit être entre 4 et 100 caractères.');
            $retour=false;

        } else if (!(string)filter_var($value)) {

            $this->setIsInvalid('L\'identifiant ou le mail est invalide');
            $retour=false;
        }
        return $retour;
    }
}
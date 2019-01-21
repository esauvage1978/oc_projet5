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
class InputSecretNew extends WebControlsInput
{

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Nouveau mot de passe');
        $this->SetType(WebControlsInput::TYPE_SECRET);
        $this->setName('secretNew');

        $this->setRequired();
        $this->setMaxLength (100);
        $this->SetHelpBlock ('Le mot de passe doit avoir au minimum 8 caractères et avoir les éléments suivants :<ul>
            <li>Une lettre minuscule</li>
            <li>Une lettre majuscule</li>
            <li>Un chiffre</li></ul>');
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text();
        if( empty($value)) {

            $this->setIsInvalid('Le mot de passe est vide.');
            $retour=false;

        }  else if(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $value) ) {

            $this->setIsInvalid('Le mot de passe ne correspond pas aux règles de complexité.');
            return false;
        }
        return $retour;
    }

}
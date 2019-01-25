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
class InputSecretNew extends WebControlsInput
{
    const MSG_PASSWORD_NOT_COMPLEX='Le mot de passe ne correspond pas aux règles de complexité.';
    const MSG_PASSWORD_HELPBLOCK='Le mot de passe doit avoir au minimum 8 caractères et avoir les éléments suivants :<ul>
            <li>Une lettre minuscule</li>
            <li>Une lettre majuscule</li>
            <li>Un chiffre</li></ul>';

    public function __construct()
    {
        $this->label ='Nouveau mot de passe';
        $this->type= self::TYPE_SECRET;
        $this->name ='secretNew';
        $this->maxLength =100;
        $this->helpBlock =self::MSG_PASSWORD_HELPBLOCK;
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text;
        if( empty($value)) {

            $this->setIsInvalid(self::MSG_NOT_GOOD);
            $retour=false;

        }  else if(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $value) ) {

            $this->setIsInvalid(self::MSG_PASSWORD_NOT_COMPLEX);
            return false;
        }

        return $retour;
    }

}
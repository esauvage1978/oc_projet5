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
class InputMail extends WebControlsInput
{
    const NAME='mail';

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Adresse mail');
        $this->setName(self::NAME);
        $this->SetType(parent::TYPE_EMAIL);
        $this->setRequired();
        $this->setMaxLength (100);
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text();
        if( empty($value)) {

            $this->setIsInvalid('Le mail est vide');
            $retour=false;

        } else if (
            strlen($value)>100 ) {

            $this->setIsInvalid('La longueur maximale est de 100 caractères.');
            $retour=false;

        } else if (!filter_var($value,FILTER_VALIDATE_EMAIL)) {

            $this->setIsInvalid('Le mail est invalide');
            $retour=false;
        }
        return $retour;
    }
}
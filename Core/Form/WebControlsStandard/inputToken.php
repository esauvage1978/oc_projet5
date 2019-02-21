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
        $token=$_SESSION['formulaire']['CSRF'][$this->prefixeFormName]['old'.ES_TOKEN ];
        var_dump($_SESSION['formulaire']['CSRF'][$this->prefixeFormName]);
        var_dump($value);
        if( !parent::check()|| $value!= $token) {
            $this->setIsInvalid('Ce formulaire est périmé !!');
            $retour=false;
        } else {
            $this->text= $_SESSION['formulaire']['CSRF'][$this->prefixeFormName][ES_TOKEN ];
        }
        return $retour;
    }
}
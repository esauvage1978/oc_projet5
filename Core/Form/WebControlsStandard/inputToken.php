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
    public $CSRF=true;

    public function __construct($prefixeFormName)
    {
        $this->name=self::NAME;
        $this->type =parent::TYPE_HIDDEN;

        if( isset($_SESSION['formulaire']['CSRF'][$this->prefixeFormName]) ){
            $_SESSION['formulaire']['CSRF'][$this->prefixeFormName]['old'.ES_TOKEN ]=
                $_SESSION['formulaire']['CSRF'][$this->prefixeFormName][ES_TOKEN ];
        } else {
            $_SESSION['formulaire']['CSRF'][$this->prefixeFormName]['old'.ES_TOKEN ]='';
        }
        $_SESSION['formulaire']['CSRF'][$this->prefixeFormName][ES_TOKEN ]=bin2hex(random_bytes(32));

        $this->setText($_SESSION['formulaire']['CSRF'][$this->prefixeFormName][ES_TOKEN]);

    }

    public function check():bool
    {
        if(!$this->CSRF) {
            return true;
        }

        $retour=true;
        $value=$this->getText();
        $token=$_SESSION['formulaire']['CSRF'][$this->prefixeFormName]['old'.ES_TOKEN ];
        if( !parent::check()|| $value!= $token) {
            $this->setIsInvalid(MSG_FORM_TOKEN_OUT_OF_DATE);
            $retour=false;
        } else {
            $this->setText( $_SESSION['formulaire']['CSRF'][$this->prefixeFormName][ES_TOKEN ]);
        }
        return $retour;
    }
}
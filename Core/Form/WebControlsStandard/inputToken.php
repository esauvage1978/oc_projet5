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
    const KEY_FORM='formulaire';
    const KEY_CSRF='CSRF';
    const KEY_OLD_TOKEN='old'.ES_TOKEN;
    public $CSRF=true;

    public function __construct($prefixeFormName)
    {
        $this->prefixeFormName=$prefixeFormName;
        $this->name=self::NAME;
        $this->type =parent::TYPE_HIDDEN;


        if( isset($_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName]) ){
            $_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][self::KEY_OLD_TOKEN ]=
                $_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][ES_TOKEN ];
        } else {
            $_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][self::KEY_OLD_TOKEN ]='';
        }
        $_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][ES_TOKEN ]=bin2hex(random_bytes(32));

        $this->setText($_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][ES_TOKEN]);

    }

    public function check():bool
    {
        if(!$this->CSRF || ! ES_CONTROL_CSRF) {
            return true;
        }

        $retour=true;

        $value=$this->getText();
        $token=$_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][self::KEY_OLD_TOKEN];
        if( empty($value) || $value!= $token) {
            $this->setIsInvalid(MSG_FORM_TOKEN_OUT_OF_DATE);
            $retour=false;
        } else {
            $this->setText( $_SESSION[self::KEY_FORM][self::KEY_CSRF][$this->prefixeFormName][ES_TOKEN ]);
        }
        return $retour;
    }
}
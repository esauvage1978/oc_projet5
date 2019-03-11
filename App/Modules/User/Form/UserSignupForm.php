<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\checkSecret;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControlsStandard\InputToken;
use ES\Core\Toolbox\Url;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserSignupForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const MAIL=4;
    const IDENTIFIANT=5;

    use checkSecret;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Créer votre compte';
        $button->name='create';
        $button->addCssClass(WebControlsButtons::CSS_PRIMARY);
        $this[self::BUTTON]=$button;

        //mot de passe
        $secret=new WebControlsInput($this->_formName);
        $secret->label ='Votre mot de passe';
        $secret->name='secretNew';
        $secret->type= WebControlsInput::TYPE_SECRET;
        $secret->maxLength=100;
        $secret->helpBlock =MSG_FORM_SECRET_HELPBLOCK;
        $this[self::SECRET_NEW]=$secret;

        //mot de passe
        $secretConfirm=new WebControlsInput($this->_formName);
        $secretConfirm->label ='Confirmation du mot de passe';
        $secretConfirm->name='secretConfirm';
        $secretConfirm->type= WebControlsInput::TYPE_SECRET;
        $secretConfirm->maxLength=100;
        $this[self::SECRET_CONFIRM]=$secretConfirm;

        $mail=new WebControlsInput($this->_formName);
        $mail->label='Votre e-mail';
        $mail->name='mail';
        $mail->type=WebControlsInput::TYPE_EMAIL;
        $mail->maxLength=100;
        $this[self::MAIL]=$mail;

        //Login
        $login=new WebControlsInput($this->_formName);
        $login->label ='Votre identifiant';
        $login->name='login';
        $login->maxLength=100;
        $this[self::IDENTIFIANT]=$login;


        $this->setText($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }


        //contrôle du nom
        if( !$this[self::IDENTIFIANT]->check() || !$this[self::IDENTIFIANT]->checkLenght(4,100) ) {
            $checkOK=false;
        }

        //contrôle du mail
        $value=$this[self::MAIL]->getText();
        if( empty($value) || !filter_var($value,FILTER_VALIDATE_EMAIL)) {

            $this->setIsInvalid(MSG_FORM_NOT_GOOD);
            $checkOK=false;
        } else if( !$this[self::MAIL]->checkLenght(4,100) ) {
            $checkOK=false;
        }

        $value=$this[self::SECRET_NEW]->getText();
        if( empty($value)) {

            $this[self::SECRET_NEW]->setIsInvalid(MSG_FORM_NOT_GOOD);
            $checkOK=false;

        }  else if(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $value) ) {

            $this[self::SECRET_NEW]->setIsInvalid(MSG_FORM_SECRET_NOT_COMPLEX);
            $checkOK= false;
        }

        if(!$this->checkSecretNewAndConfirm()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','signup')) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::IDENTIFIANT) .
               $this->renderControl(self::MAIL) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }

}

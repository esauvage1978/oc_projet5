<?php

namespace ES\App\Modules\Shared\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControls\WebControlsTextaera;
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
class ContactForm extends Form
{
    /**
     * élément du tableau de control, premier élément=token
     */
    const TOKEN=0;
    const BUTTON=1;
    const NAME=2;
    const MAIL=3;
    const SUBJECT=4;
    const MESSAGE=5;
    const RECAPTCHA=6;

    

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Envoyer le message';
        $button->name='send';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;

        //ajout du nom
        $name=new WebControlsInput($this->_formName);
        $name->placeHolder ='Votre nom et prénom';
        $name->name='name';
        $name->maxLength=100;
        $this[self::NAME]=$name;

        //ajout du mail
        $mail=new WebControlsInput($this->_formName);
        $mail->placeHolder='Votre e-mail';
        $mail->name='mail';
        $mail->type=WebControlsInput::TYPE_EMAIL;
        $mail->defaultControl=WebControlsInput::CONTROLE_MAIL;
        $mail->maxLength=100;
        $this[self::MAIL]=$mail;

        $subject=new WebControlsInput($this->_formName);
        $subject->placeHolder='Sujet';
        $subject->name='subject';
        $subject->maxLength=100;
        $this[self::SUBJECT]=$subject;

        $message=new WebControlsTextaera($this->_formName);
        $message->placeHolder='Message';
        $message->name='message';
        $this[self::MESSAGE]=$message;

        $repcatcha=new WebControlsInput($this->_formName);
        $repcatcha->type =WebControlsInput::TYPE_HIDDEN;
        $repcatcha->name='recaptchaResponse';
        $this[self::RECAPTCHA]=$repcatcha;

        $this->setText($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

        //contrôle du nom
        if( !$this[self::NAME]->check() || !$this[self::NAME]->checkLenght(null,100) ) {
            $checkOK=false;
        }

        //contrôle du mail
        $value=$this[self::MAIL]->getText();
        if( !$this[self::MAIL]->check() || !filter_var($value,FILTER_VALIDATE_EMAIL)) {

            $this[self::MAIL]->setIsInvalid(MSG_FORM_NOT_GOOD);
            $checkOK=false;
        } else if( !$this[self::MAIL]->checkLenght(4,100) ) {
            $checkOK=false;
        }

        //contrôle du subject
        if( !$this[self::SUBJECT]->check() || !$this[self::SUBJECT]->checkLenght(null,100) ) {
            $checkOK=false;
        }

        //contrôle du Message
        if( !$this[self::MESSAGE]->check()) {
            $checkOK=false;
        }
        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('shared','contact#contact')) .
               $this->renderControl(self::TOKEN,false) .
               $this->renderControl(self::RECAPTCHA,false ) .
               $this->renderControl(self::NAME) .
               $this->renderControl(self::MAIL) .
               $this->renderControl(self::SUBJECT) .
               $this->renderControl(self::MESSAGE) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

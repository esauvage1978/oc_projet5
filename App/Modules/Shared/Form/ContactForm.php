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
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName,'send','Envoyer le message') ;
        $this[self::BUTTON]->addCssClass(WebControlsButtons::CSS_ROUNDED);

        //ajout du nom
        $this[self::NAME]=WebControlsInput::CreateInput($this->_formName,'name',null,'Votre nom et prénom');

        //ajout du mail
        $this[self::MAIL]=WebControlsInput::CreateInput($this->_formName,'mail',null,'Votre e-mail');
        $this[self::MAIL]->type=WebControlsInput::TYPE_EMAIL;
        $this[self::MAIL]->defaultControl=WebControlsInput::CONTROLE_MAIL;

        $this[self::SUBJECT]=WebControlsInput::CreateInput($this->_formName,'sujet',null,'Sujet');

        $message=new WebControlsTextaera($this->_formName);
        $message->placeHolder='Message';
        $message->name='message';
        $this[self::MESSAGE]=$message;

        $this[self::RECAPTCHA]=WebControlsInput::CreateHiddenId($this->_formName,'recaptchaResponse',null);

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

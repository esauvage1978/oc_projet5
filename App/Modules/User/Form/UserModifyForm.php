<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControls\WebControlsSelect;
use ES\Core\Form\WebControls\WebControlsCheckbox;
use ES\Core\Form\WebControls\WebControlsFile;
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
class UserModifyForm extends Form
{
    public $controls;
    const TOKEN=0;
    const BUTTON=1;
    const IDENTIFIANT=2;
    const MAIL=3;
    const USER_ROLE=4;
    const ID_HIDDEN=5;
    const ACTIF=6;
    const FILE=7;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Modifier';
        $button->name='modif';
        $button->addCssClass(WebControlsButtons::CSS_PRIMARY);
        $this[self::BUTTON]=$button;


        //Login
        $login=new WebControlsInput($this->_formName);
        $login->label ='Votre identifiant';
        $login->name='login';
        $login->maxLength=100;
        $this[self::IDENTIFIANT]=$login;

        $mail=new WebControlsInput($this->_formName);
        $mail->label='Votre e-mail';
        $mail->name='mail';
        $mail->type=WebControlsInput::TYPE_EMAIL;
        $mail->maxLength=100;
        $this[self::MAIL]=$mail;

        $userRole=new WebControlsSelect($this->_formName);
        $userRole->label='Rôle';
        $userRole->name='habil';
        $userRole->liste =ES_USER_ROLE;
        $this[self::USER_ROLE]=$userRole;

        $idHidden=new WebControlsInput($this->_formName);
        $idHidden->name ='hash';
        $idHidden->type=WebControlsInput::TYPE_HIDDEN;
        $idHidden->maxLength =60;
        $this[self::ID_HIDDEN]=$idHidden;

        $actif=new WebControlsCheckbox($this->_formName);
        $actif->name='actif';
        $this[self::ACTIF]=$actif;

        $avatar=new WebControlsFile($this->_formName);
        $avatar->label='Image de présentation (format jpg)';
        $avatar->name='avatar';
        $avatar->require =false;
        $this[self::FILE]=$avatar;

        $this->setText($datas,$byName);
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

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','modify'),$this[self::ID_HIDDEN]->getText(),true) .
               $this->renderControl(self::TOKEN,false) .
               $this->renderControl(self::ID_HIDDEN,false) .
               $this->renderControl(self::IDENTIFIANT) .
               $this->renderControl(self::MAIL) .
               $this->renderControl(self::USER_ROLE) .
               $this->renderControl(self::ACTIF) .
               $this->renderControl(self::FILE) . '<p><br/></p>' .
               '<div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">' .
               $this->renderButton(self::BUTTON) .
               '<a href="' . Url::to('user','pwdchange') . '"> Changer mon mot de passe ?</a>' .
               '</div>'.
               '</form>';
    }

}

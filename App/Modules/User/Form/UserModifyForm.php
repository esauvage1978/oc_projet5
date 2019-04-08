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
        $this[self::TOKEN]=new InputToken($this->_formName);

        //ajout du bouton
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName,'modif','Modifier');


        //Login
        $this[self::IDENTIFIANT]=WebControlsInput::CreateInput($this->_formName,'login','Votre identifiant');

        $this[self::MAIL]=WebControlsInput::CreateInput($this->_formName,'mail','Votre e-mail',null,100,WebControlsInput::TYPE_EMAIL);

        $userRole=new WebControlsSelect($this->_formName);
        $userRole->label='Rôle';
        $userRole->name='habil';
        $userRole->liste =ES_USER_ROLE;
        $this[self::USER_ROLE]=$userRole;

        $this[self::ID_HIDDEN]=WebControlsInput::CreateHiddenId($this->_formName,'hash');

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
        if( !$this[self::ID_HIDDEN]->check() ) {
            $checkOK=false;
        }

        //contrôle du nom
        if( !$this[self::IDENTIFIANT]->check() || !$this[self::IDENTIFIANT]->checkLenght(4,100) ) {
            $checkOK=false;
        }

        //contrôle du mail
        $value=$this[self::MAIL]->getText();
        if( empty($value) || !filter_var($value,FILTER_VALIDATE_EMAIL)) {

            $this[self::MAIL]->setIsInvalid(MSG_FORM_NOT_GOOD);
            $checkOK=false;
        } 
        
        if( !$this[self::MAIL]->checkLenght(4,100) ) {
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

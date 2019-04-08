<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
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
class UserPwdChangeForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const SECRET_OLD=4;


    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName,'update','Changer');

        //mot de passe
        $this[self::SECRET_NEW]=WebControlsInput::CreateInput($this->_formName,'secretNew','Nouveau mot de passe',null,100,WebControlsInput::TYPE_SECRET);
        $this[self::SECRET_NEW]->helpBlock =MSG_FORM_SECRET_HELPBLOCK;


        //mot de passe
        $this[self::SECRET_CONFIRM]=WebControlsInput::CreateInput($this->_formName,'secretConfirm','Confirmation du mot de passe',null,100,WebControlsInput::TYPE_SECRET);

        //mot de passe
        $this[self::SECRET_OLD]=WebControlsInput::CreateInput($this->_formName,'secretOld','Ancien mot de passe',null,100,WebControlsInput::TYPE_SECRET);


        $this->setText($datas,$byName) ;
    }


    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

        if(!$this[self::SECRET_OLD]->check()) {
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
        $valueConf=$this[self::SECRET_CONFIRM]->getText();
        if( empty($valueConf)) {

            $this[self::SECRET_CONFIRM]->setIsInvalid(MSG_FORM_NOT_GOOD);
            $checkOK=false;
        }

        if($value<>$valueConf ) {
            $this[self::SECRET_NEW]->setIsInvalid(MSG_USER_INVALID_SECRET_AND_CONF);
            $checkOK =false;
        }
        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','pwdchange')) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::SECRET_OLD) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) .
               '<div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">' .
               $this->renderButton(self::BUTTON) .
               '<a  href="'. Url::to('user','modify') .'" class="btn btn-secondary">Retour</a>' .
               '</div>'.'</form>';
    }
}

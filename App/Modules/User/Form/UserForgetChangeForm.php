<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;

use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControlsStandard\InputToken;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Toolbox\Url;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForgetChangeForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const HASH=4;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token

        $this[self::TOKEN]=new InputToken($this->_formName);

        //ajout du bouton
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName,'change','Changez votre mot de passe');


        //mot de passe
        $this[self::SECRET_NEW]=WebControlsInput::CreateInput($this->_formName,'secretNew','Nouveau mot de passe',null,100,WebControlsInput::TYPE_SECRET);
        $this[self::SECRET_NEW]->helpBlock =MSG_FORM_SECRET_HELPBLOCK;

        //mot de passe
        $this[self::SECRET_CONFIRM]=WebControlsInput::CreateInput($this->_formName,'secretConfirm','Confirmation du mot de passe',null,100,WebControlsInput::TYPE_SECRET);

        $this[self::HASH]=WebControlsInput::CreateHiddenId($this->_formName,'hash',false);


        $this->setText($datas,$byName) ;
    }


    public function check():bool
    {
        $checkOK=true;


        if(!$this[self::TOKEN]->check() ) {
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

        return $checkOK;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','pwdforgetchange', $this[self::HASH]->getText())) .
                $this->renderControl(self::TOKEN) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::HASH) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) . '<p><br/></p>' .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

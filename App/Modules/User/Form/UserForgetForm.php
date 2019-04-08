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
class UserForgetForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const LOGIN=2;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $this[self::TOKEN]=new InputToken($this->_formName);

        //ajout du bouton
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName,'recup','Récupérez votre mot de passe');

        //Login
        $this[self::LOGIN]=WebControlsInput::CreateInput($this->_formName,'login',null,'Identifiant ou adresse mail');

        $this->setText($datas,$byName);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

        //contrôle du nom
        if( !$this[self::LOGIN]->check() || !$this[self::LOGIN]->checkLenght(4,100) ) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','pwdforget')) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::LOGIN) . '<p><br/></p>' .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }

}

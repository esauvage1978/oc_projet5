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
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Récupérer votre mot de passe';
        $button->name='recup';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;

        //Login
        $login=new WebControlsInput($this->_formName);
        $login->placeHolder ='Identifiant ou adresse mail';
        $login->name='login';
        $login->maxLength=100;
        $this[self::LOGIN]=$login;

        $this->setText($datas,$byName);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

        //contrôle du nom
        if( !$this[self::LOGIN]->check() || !$this[self::LOGIN]->checkLenght(null,100) ) {
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

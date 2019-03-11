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
class UserConnexionForm extends Form
{
    /**
     * élément du tableau de control, premier élément=token
     */
    const TOKEN=0;
    const BUTTON=1;
    const LOGIN=2;
    const SECRET=3;


    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Connexion';
        $button->name='connexion';
        $button->addCssClass(WebControlsButtons::CSS_PRIMARY);
        $this[self::BUTTON]=$button;

        //Login
        $login=new WebControlsInput($this->_formName);
        $login->label ='Identifiant ou adresse mail';
        $login->name='login';
        $login->maxLength=100;
        $this[self::LOGIN]=$login;

        //mot de passe
        $secret=new WebControlsInput($this->_formName);
        $secret->label ='Mot de passe';
        $secret->name='secret';
        $secret->type= WebControlsInput::TYPE_SECRET;
        $secret->maxLength=100;
        $this[self::SECRET]=$secret;

        $this->setText($datas,$byName) ;
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


        if(!$this[self::SECRET]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','connexion')) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::LOGIN) .
               $this->renderControl(self::SECRET) .
               '<div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">' .
               $this->renderButton(self::BUTTON) .
               '<a href="'  . Url::to('user','pwdforget'). '"> Mot de passe oublié ?</a>' .
               '</div>'.
               '</form>';
    }
}

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

        $this[self::TOKEN]=new InputToken($this->_formName);

        //ajout du bouton
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName,'connecion','Connexion') ;

        //Login
        $this[self::LOGIN]=WebControlsInput::CreateInput($this->_formName,'login','Identifiant ou adresse mail');

        //mot de passe
        $this[self::SECRET]=WebControlsInput::CreateInput($this->_formName,'secret','Mot de passe',null,100,WebControlsInput::TYPE_SECRET);

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

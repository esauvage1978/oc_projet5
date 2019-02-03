<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonConnexion;
use ES\Core\Form\WebControlsStandard\InputLogin;
use ES\Core\Form\WebControlsStandard\InputSecret;

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
    const BUTTON=1;
    const LOGIN=2;
    const SECRET=3;


    public function __construct($datas=[],$byName=true)

    {
        $this[self::BUTTON]=new ButtonConnexion();
        $this[self::LOGIN]=new InputLogin();
        $this[self::SECRET]=new InputSecret();

        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this[self::LOGIN]->check()) {
            $checkOK=false;
        }

        if(!$this[self::SECRET]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction('user.connexion') .
               $this->renderToken() .
               $this->renderControl(self::LOGIN) .
               $this->renderControl(self::SECRET) .
               '<div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">' .
               $this->renderButton(self::BUTTON) .
               '<a href="##INDEX##user.pwdforget"> Mot de passe oublié ?</a>' .
               '</div>'.
               '</form>';
    }
}

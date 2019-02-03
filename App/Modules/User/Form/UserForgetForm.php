<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonRecupere;
use ES\Core\Form\WebControlsStandard\InputLogin;


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

    const BUTTON=1;
    const LOGIN=2;

    public function __construct($datas=[],$byName=true)
    {
        $this[self::BUTTON]=new ButtonRecupere();
        $this[self::LOGIN]=new InputLogin();

        $this->postConstruct($datas,$byName);
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

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction('user.pwdforget') .
               $this->renderToken() .
               $this->renderControl(self::LOGIN) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }

}

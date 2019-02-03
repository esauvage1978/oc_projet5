<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;
use ES\Core\Form\WebControlsStandard\InputMail;
use ES\App\Modules\User\Form\WebControls\InputIdentifiant;
use ES\App\Modules\User\Form\WebControls\SelectAccreditation;
use ES\Core\Form\WebControlsStandard\InputIdHidden;
use ES\App\Modules\User\Form\WebControls\CheckboxActif;

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

    const BUTTON=1;
    const IDENTIFIANT=2;
    const MAIL=3;
    const ACCREDITATION=4;
    const ID_HIDDEN=5;
    const ACTIF=6;

    public function __construct($datas=[],$byName=true)
    {
        $this[self::BUTTON]=new ButtonModifier();
        $this[self::IDENTIFIANT]=new InputIdentifiant();
        $this[self::MAIL]=new InputMail();
        $this[self::ACCREDITATION]=new SelectAccreditation();
        $this[self::ID_HIDDEN]=new InputIdHidden();
        $this[self::ACTIF]=new CheckboxActif();

        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this[self::IDENTIFIANT]->check()) {
            $checkOK=false;
        }

        if(!$this[self::MAIL]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction('user.modify/' . $this[self::ID_HIDDEN]->text) .
               $this->renderToken() .
               $this->renderControl(self::ID_HIDDEN) .
               $this->renderControl(self::IDENTIFIANT) .
               $this->renderControl(self::MAIL) .
               $this->renderControl(self::ACCREDITATION) .
               $this->renderControl(self::ACTIF) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }

}

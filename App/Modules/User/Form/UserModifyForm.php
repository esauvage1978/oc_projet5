<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
use ES\Core\Toolbox\Auth;
use ES\App\Modules\User\Form\WebControls\ButtonModifier;
use ES\App\Modules\User\Form\WebControls\InputMail;
use ES\App\Modules\User\Form\WebControls\InputIdentifiant;
use ES\App\Modules\User\Form\WebControls\SelectAccreditation;
use ES\App\Modules\User\Form\WebControls\InputIdHidden;
use ES\App\Modules\User\Form\WebControls\CheckboxActif;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserModifyForm extends Form implements IForm
{
    public $controls;

    const BUTTON=0;
    const IDENTIFIANT=1;
    const MAIL=2;
    const ACCREDITATION=3;
    const ID_HIDDEN=4;
    const ACTIF=5;

    public function __construct($data)
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::IDENTIFIANT]=new InputIdentifiant($data);
        $this->controls[self::MAIL]=new InputMail($data);
        $this->controls[self::ACCREDITATION]=new SelectAccreditation($data);
        $this->controls[self::ID_HIDDEN]=new InputIdHidden($data);
        $this->controls[self::ACTIF]=new CheckboxActif($data);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->controls[self::IDENTIFIANT]->check()) {
            $checkOK=false;
        }

        if(!$this->controls[self::MAIL]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }


}

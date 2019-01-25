<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\IForm;
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
class UserModifyForm extends Form implements IForm
{
    public $controls;

    const BUTTON=0;
    const IDENTIFIANT=1;
    const MAIL=2;
    const ACCREDITATION=3;
    const ID_HIDDEN=4;
    const ACTIF=5;

    public static $name_idHidden=InputIdHidden::NAME;
    public static $name_identifiant=InputIdentifiant::NAME;
    public static $name_mail=InputMail::NAME;
    public static $name_accreditation=SelectAccreditation::NAME;
    public static $name_actif=CheckboxActif::NAME;

    public function __construct($datas=[])
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::IDENTIFIANT]=new InputIdentifiant();
        $this->controls[self::MAIL]=new InputMail();
        $this->controls[self::ACCREDITATION]=new SelectAccreditation();
        $this->controls[self::ID_HIDDEN]=new InputIdHidden();
        $this->controls[self::ACTIF]=new CheckboxActif();

        $this->setText($datas) ;
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

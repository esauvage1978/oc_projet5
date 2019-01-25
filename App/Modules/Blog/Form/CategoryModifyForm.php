<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;
use ES\Core\Form\WebControlsStandard\InputIdHidden;
use ES\App\Modules\Blog\Form\WebControls\InputCategory;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class CategoryModifyForm extends Form
{

    const BUTTON=0;
    const CATEGORY=1;
    const IDHIDDEN=2;


    public static $name_category='catmodify_' . InputCategory::NAME;
    public static $name_idhidden='catmodify_' . InputIdHidden::NAME;

    public function __construct($datas=[])
    {
        $this->controls[self::BUTTON]=new ButtonModifier();
        $this->controls[self::CATEGORY]=new InputCategory();
        $this->controls[self::IDHIDDEN]=new InputIdHidden();

        $this->controls[self::CATEGORY]->prefixeFormName='catmodify';
        $this->controls[self::IDHIDDEN]->prefixeFormName='catmodify';
        $this->setText($datas);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->controls[self::IDHIDDEN]->check()) {
            $checkOK=false;
        }
        if(!$this->controls[self::CATEGORY]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

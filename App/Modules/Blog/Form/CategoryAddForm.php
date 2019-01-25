<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonAjouter;
use ES\App\Modules\Blog\Form\WebControls\InputCategory;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class CategoryAddForm extends Form
{

    const BUTTON=0;
    const CATEGORY=1;

    public static $name_category= 'catadd_' . InputCategory::NAME;

    public function __construct($datas=[])
    {


        $this->controls[self::BUTTON]=new ButtonAjouter();
        $this->controls[self::CATEGORY]=new InputCategory();

        $this->controls[self::CATEGORY]->prefixeFormName='catadd';

        $this->setText($datas);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->controls[self::CATEGORY]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }
}

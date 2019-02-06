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

    const BUTTON=1;
    const CATEGORY=2;
    const IDHIDDEN=3;



    public function __construct($datas=[],$byName=true)
    {
        $this[self::BUTTON]=new ButtonModifier();
        $this[self::CATEGORY]=new InputCategory();
        $this[self::IDHIDDEN]=new InputIdHidden();

        $this->postConstruct($datas,$byName);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this[self::IDHIDDEN]->check()) {
            $checkOK=false;
        }
        if(!$this[self::CATEGORY]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction('blog.category.modify#categorycrud') .

               $this->renderControl(self::TOKEN,false) .  
               $this->renderControl(self::IDHIDDEN,false) .
               $this->renderControl(self::CATEGORY) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

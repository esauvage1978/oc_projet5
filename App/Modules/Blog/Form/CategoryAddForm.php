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

    const BUTTON=1;
    const CATEGORY=2;


    public function __construct($datas=[],$byName=true)
    {

        $this[self::BUTTON]=new ButtonAjouter();
        $this[self::CATEGORY]=new InputCategory();

        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this[self::CATEGORY]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }


    public function render()
    {
        return $this->getAction('blog.category.add#categorycrud') .
               $this->renderToken() .
               $this->renderControl(self::CATEGORY) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

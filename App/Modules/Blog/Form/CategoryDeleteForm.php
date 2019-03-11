<?php

namespace ES\App\Modules\Blog\Form;

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
class CategoryDeleteForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const CATEGORY=2;
    const IDHIDDEN=3;



    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Supprimer';
        $button->name='delete';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;


        $idHidden=new WebControlsInput($this->_formName);
        $idHidden->name ='hash';
        $idHidden->type=WebControlsInput::TYPE_HIDDEN;
        $idHidden->maxLength =60;
        $this[self::IDHIDDEN]=$idHidden;

        //ajout de la catégorie
        $name=new WebControlsInput($this->_formName);
        $name->placeHolder='Catégorie';
        $name->name='category';
        $name->maxLength=20;
        $this[self::CATEGORY]=$name;

        $this->setText($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
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
        return $this->getAction(Url::to('blog','category','delete#categorycrud')) .

               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::IDHIDDEN) .
               $this->renderControl(self::CATEGORY) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

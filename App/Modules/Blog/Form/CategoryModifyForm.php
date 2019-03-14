<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControlsStandard\InputToken;
use ES\Core\Form\WebControls\WebControlsMessage;
use ES\Core\Toolbox\Url;


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
    const TOKEN=0;
    const MESSAGE=1;
    const BUTTON=2;
    const CATEGORY=3;
    const IDHIDDEN=4;



    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du message de retour
        $message=new WebControlsMessage($this->_formName);
        $this[self::MESSAGE]=$message ;


        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Modifier';
        $button->name='update';
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
        return $this->getAction(Url::to('blog','category','modify#categorycrud')) .
               $this->renderControl(self::MESSAGE,false) .
               $this->renderControl(self::TOKEN,false) .
               $this->renderControl(self::IDHIDDEN,false) .
               $this->renderControl(self::CATEGORY) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControls\WebControlsMessage;
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
class CategoryAddForm extends Form
{
    const TOKEN=0;
    const MESSAGE=1;
    const BUTTON=2;
    const CATEGORY=3;


    protected function initialise_control($datas=[])
    {
        //ajout du token
        $this[self::TOKEN]=new InputToken($this->_formName);


        //ajout du message de retour
        $this[self::MESSAGE]=new WebControlsMessage($this->_formName);

        //ajout du bouton
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName);



        //ajout de la catégorie
        $name=new WebControlsInput($this->_formName);
        $name->placeHolder='Catégorie';
        $name->name='category';
        $name->maxLength=20;
        $this[self::CATEGORY]=$name;

    }

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        $this->initialise_control($datas);

        $this->setText($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }


        if(!$this[self::CATEGORY]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    protected function render_control()
    {
        return $this->renderControl(self::MESSAGE,false) .
               $this->renderControl(self::TOKEN,false) .
               $this->renderControl(self::CATEGORY) .
               $this->renderButton(self::BUTTON) ;
    }

    public function render()
    {
        return $this->getAction(Url::to('blog','category','add#categorycrud')) .
                $this->render_control() .
               '</form>';
    }
}

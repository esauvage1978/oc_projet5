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
class CategoryModifyForm extends CategoryAddForm
{
    const IDHIDDEN=4;



    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();


        $this->initialise_control($datas);

        $this[self::IDHIDDEN]=WebControlsInput::CreateHiddenId($this->_formName);

        $this[self::BUTTON]->label='Modifier';
        $this[self::BUTTON]->name='modify';

        $this[self::TOKEN]->CSRF=false;

        $this->setText($datas,$byName) ;

    }

    public function check():bool
    {
        $checkOK=true;

        $checkOK =parent::check ();

        if(!$this[self::IDHIDDEN]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('blog','category','modify#categorycrud')) .
               $this->renderControl(self::IDHIDDEN,false) .
               $this->render_control () .
               '</form>';
    }
}

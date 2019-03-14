<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControls\WebControlsSelect;
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
class CommentModifyStatusForm extends Form
{
    const TOKEN=0;
    const MESSAGE=1;
    const BUTTON=2;
    const STATUS=3;
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
        $button->name='modify';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;

        //ajout de la catégorie
        $statut=new WebControlsSelect($this->_formName);
        $statut->Label='Statut';
        $statut->name='statut';
        $this[self::STATUS]=$statut;

        $idHidden=new WebControlsInput($this->_formName);
        $idHidden->name ='hash';
        $idHidden->type=WebControlsInput::TYPE_HIDDEN;
        $idHidden->maxLength =60;
        $this[self::IDHIDDEN]=$idHidden;

        $this->setText($datas,$byName);
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
        if(!$this[self::STATUS]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('blog','commentmoderate#commentlisttop')) .

               $this->renderControl(self::TOKEN,false) .
               $this->renderControl(self::IDHIDDEN,false) .
               $this->renderControl(self::STATUS) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

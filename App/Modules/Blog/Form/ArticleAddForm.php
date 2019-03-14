<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControls\WebControlsTextaera;
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
class ArticleAddForm extends Form
{
    const TOKEN=0;
    const MESSAGE=1;
    const BUTTON=2;
    const CATEGORY=3;
    const TITLE=4;
    const CHAPO=5;
    const CONTENT=6;


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
        $button->label='Ajouter';
        $button->name='add';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;

        //ajout de la catégorie
        $category=new WebControlsSelect($this->_formName);
        $category->Label='Catégorie';
        $category->name='category';
        $this[self::CATEGORY]=$category;

        //ajout de la catégorie
        $titre=new WebControlsInput($this->_formName);
        $titre->placeHolder='Titre';
        $titre->name='title';
        $titre->maxLength=100;
        $this[self::TITLE]=$titre;

        //ajout de la catégorie
        $chapo=new WebControlsTextaera($this->_formName);
        $chapo->placeHolder='Extrait';
        $chapo->name='chapo';
        $this[self::CHAPO]=$chapo;

        //ajout de la catégorie
        $mContent=new WebControlsTextaera($this->_formName);
        $mContent->placeHolder='Contenu';
        $mContent->name='content';
        $this[self::CONTENT]=$mContent;


        $this->setText($datas,$byName);
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

        if(!$this[self::TITLE]->check() || !$this[self::TITLE]->checkLenght(4,100)) {
            $checkOK=false;
        }

        if(!$this[self::CHAPO]->check()) {
            $checkOK=false;
        }

        if(!$this[self::CONTENT]->check()) {
            $checkOK=false;
        }
        return $checkOK ;
    }


    public function render()
    {
        return $this->getAction(Url::to('blog','article','add#articleadd')) .
               $this->renderControl(self::TOKEN,false) .
               $this->renderControl(self::MESSAGE,false) .
               $this->renderControl(self::TITLE) .
               $this->renderControl(self::CATEGORY) .
               $this->renderControl(self::CHAPO) .
               $this->renderControl(self::CONTENT) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

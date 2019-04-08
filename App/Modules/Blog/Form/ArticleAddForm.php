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
    const CHAPO=4;
    const CONTENT=4;


    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $this[self::TOKEN]=new InputToken($this->_formName);


        //ajout du message de retour
        $message=new WebControlsMessage($this->_formName);
        $this[self::MESSAGE]=$message ;

        //ajout du bouton
        $this[self::BUTTON]=WebControlsButtons::CreateButton($this->_formName);


        //ajout de la catégorie
        $category=new WebControlsSelect($this->_formName);
        $category->label='Catégorie';
        $category->name='category';
        $this[self::CATEGORY]=$category;

        //ajout de la catégorie

        $this[self::TITLE]=WebControlsInput::CreateInput($this->_formName,'title',null,'Titre');

        $champ=new WebControlsTextaera($this->_formName);
        $champ->label ='Chapo';
        $champ->name='message';
        $champ->rows=3;
        $this[self::CHAPO]=$champ;

        $contentArticle=new WebControlsTextaera($this->_formName);
        $contentArticle->label ='Contenu de l\'article';
        $contentArticle->name='articleContent';
        $contentArticle->rows=30;
        $this[self::CONTENT]=$contentArticle;



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

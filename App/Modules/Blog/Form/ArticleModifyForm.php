<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;

use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControls\WebControlsTextaera;
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
class ArticleModifyForm extends Form
{
    const TOKEN=0;
    const TITLE=1;
    const CHAPO=2;
    const CONTENT=3;
    const ID_HIDDEN=4;


    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $this[self::TOKEN]=new InputToken($this->_formName);

        //ajout du titre
        $this[self::TITLE]=WebControlsInput::CreateInput($this->_formName,'title','Titre');


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

        $this[self::ID_HIDDEN]=WebControlsInput::CreateHiddenId($this->_formName,'idArticle');

        $this->setText($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

        if(!$this[self::ID_HIDDEN]->check()) {
            $checkOK=false;
        }


        //contrôle du nom
        if( !$this[self::TITLE ]->check() || !$this[self::TITLE]->checkLenght(4,100) ) {
            $checkOK=false;
        }

        if(!$this[self::CHAPO]->check() || !$this[self::CHAPO]->checkLenght(1,255)) {
            $checkOK=false;
        }

        if(!$this[self::CONTENT]->check()) {
            $checkOK=false;
        }
        return $checkOK ;
    }


    public function render()
    {
        return
          $this->renderControl(self::TOKEN).
          $this->renderControl(self::TITLE) .
          $this->renderControl(self::CHAPO) .
          $this->renderControl(self::CONTENT) .
          $this->renderControl(self::TOKEN,false) .
          $this->renderControl(self::ID_HIDDEN,false) ;
    }
}

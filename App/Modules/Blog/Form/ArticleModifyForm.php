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
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du nom
        $title=new WebControlsInput($this->_formName);
        $title->label ='Titre de l\'article';
        $title->name='title';
        $title->maxLength=100;
        $this[self::TITLE]=$title;

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

        $idHidden=new WebControlsInput($this->_formName);
        $idHidden->name ='idArticle';
        $idHidden->type=WebControlsInput::TYPE_HIDDEN;
        $idHidden->defaultControl=WebControlsInput::CONTROLE_INT;
        $this[self::ID_HIDDEN]=$idHidden;

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
        if( !$this[self::TITLE ]->check() || !$this[self::TITLE]->checkLenght(3,100) ) {
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
        return
          $this->renderControl(self::TOKEN).
          $this->renderControl(self::TITLE) .
          $this->renderControl(self::CHAPO) .
          $this->renderControl(self::CONTENT) .
          $this->renderControl(self::TOKEN,false) .
          $this->renderControl(self::ID_HIDDEN,false) ;
    }
}

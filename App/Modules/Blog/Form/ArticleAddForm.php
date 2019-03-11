<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonAjouter;
use ES\App\Modules\Blog\Form\WebControls\SelectArticleCategory;
use ES\App\Modules\Blog\Form\WebControls\InputArticleTitle;
use ES\App\Modules\Blog\Form\WebControls\TextareaArticleChapo;
use ES\App\Modules\Blog\Form\WebControls\TextareaArticleContent;
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

    const BUTTON=1;
    const CATEGORY=2;
    const TITLE=3;
    const CHAPO=4;
    const CONTENT=5;


    public function __construct($datas=[],$byName=true)
    {

        $this[self::BUTTON]=new ButtonAjouter();
        $this[self::CATEGORY]=new SelectArticleCategory();
        $this[self::TITLE]=new InputArticleTitle();
        $this[self::CHAPO]=new TextareaArticleChapo();
        $this[self::CONTENT]=new TextareaArticleContent();

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

        if(!$this[self::TITLE]->check()) {
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
               $this->renderToken() .
               $this->renderControl(self::TITLE) .
               $this->renderControl(self::CATEGORY) .
               $this->renderControl(self::CHAPO) .
               $this->renderControl(self::CONTENT) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

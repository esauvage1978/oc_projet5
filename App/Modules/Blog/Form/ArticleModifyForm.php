<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;

use ES\App\Modules\Blog\Form\WebControls\InputArticleTitle;
use ES\App\Modules\Blog\Form\WebControls\TextareaArticleChapo;
use ES\App\Modules\Blog\Form\WebControls\TextareaArticleContent;
use ES\Core\Form\WebControlsStandard\InputIdHidden;

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

    const TITLE=1;
    const CHAPO=2;
    const CONTENT=3;
    const ID=4;


    public function __construct($datas=[],$byName=true)
    {


        $this[self::TITLE]=new InputArticleTitle();
        $this[self::CHAPO]=new TextareaArticleChapo();
        $this[self::CONTENT]=new TextareaArticleContent();
        $this[self::ID]=new InputIdHidden();

        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }
        if(!$this[self::ID]->check()) {
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
        //return $this->getAction('blog.article.modify#articleadd') .

        return
          $this->renderControl(self::TITLE) .

          $this->renderControl(self::CHAPO) .
          $this->renderControl(self::CONTENT) .
          $this->renderControl(self::TOKEN,false) .
          $this->renderControl(self::ID,false) ;
            //'</form>';
    }
}

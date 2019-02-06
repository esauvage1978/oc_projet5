<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;
use ES\App\Modules\Blog\Form\WebControls\SelectArticleState;
use ES\App\Modules\Blog\Form\WebControls\SelectArticleCategory;

/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class ArticleModifyStateForm extends Form
{

    const BUTTON=1;
    const STATE=2;
    const CATEGORY=3;

    public function __construct($datas=[],$byName=true)
    {

        $this[self::BUTTON]=new ButtonModifier();
        $this[self::STATE]=new SelectArticleState();
        $this[self::CATEGORY]=new SelectArticleCategory();
        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::STATE ]->check()) {
            $checkOK=false;
        }
        if(!$this[self::CATEGORY]->check()) {
            $checkOK=false;
        }
        return $checkOK ;
    }


    public function render()
    {
        //return $this->getAction('blog.article.modify#articleadd') .

        return  $this->renderControl(self::STATE) .
          $this->renderControl(self::CATEGORY) .
                $this->renderButton(self::BUTTON) ;
            //'</form>';
    }
}

<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControlsStandard\InputToken;
use ES\Core\Form\WebControls\WebControlsSelect;
use ES\Core\Form\WebControls\WebControlsFile;
use ES\Core\Toolbox\Url;


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
    const TOKEN=0;
    const BUTTON=1;
    const STATE=2;
    const CATEGORY=3;
    const FILE=4;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Modifier';
        $button->name='modify';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;

        $state=new WebControlsSelect($this->_formName);
        $state->label='Statut';
        $state->name='statut';
        $state->liste =ES_BLOG_ARTICLE_STATE;
        $this[self::STATE]=$state;

        $category=new WebControlsSelect($this->_formName);
        $category->label='Catégory';
        $category->name='category';
        $this[self::CATEGORY]=$category;

        $file=new WebControlsFile($this->_formName);
        $file->label='Image de présentation (format jpg)';
        $file->name='presentation';
        $file->require =false;
        $this[self::FILE]=$file;

        $this->setText($datas,$byName);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

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
        return  $this->renderControl(self::STATE) .
          $this->renderControl(self::CATEGORY) .
          $this->renderControl(self::FILE) .
                $this->renderButton(self::BUTTON) ;
    }
}

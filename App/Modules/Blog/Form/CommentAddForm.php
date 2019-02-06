<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonAjouter;
use ES\Core\Form\WebControlsStandard\TextareaComment;
use ES\Core\Form\WebControlsStandard\InputIdHidden;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class CommentAddForm extends Form
{

    const BUTTON=1;
    const COMMENT=2;
    const IDARTICLEHIDDEN=3;


    public function __construct($datas=[],$byName=true)
    {

        $this[self::BUTTON]=new ButtonAjouter();
        $this[self::COMMENT]=new TextareaComment();
        $this[self::IDARTICLEHIDDEN]=new InputIdHidden();

        $this->postConstruct($datas,$byName) ;
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
            $checkOK=false;
        }

        if(!$this[self::IDARTICLEHIDDEN]->check()) {
            $checkOK=false;
        }

        if(!$this[self::COMMENT]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render() : string
    {
        return $this->getAction('blog.comment.add#commentadd') .
               $this->renderToken() .
               $this->renderControl(self::IDARTICLEHIDDEN) .
               $this->renderControl(self::COMMENT) .
               //$this->renderButton(self::BUTTON) .
               '</form>';
    }
}


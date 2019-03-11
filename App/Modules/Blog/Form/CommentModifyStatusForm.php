<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControlsStandard\ButtonModifier;
use ES\Core\Form\WebControlsStandard\InputIdHidden;
use ES\App\Modules\Blog\Form\WebControls\InputCommentStatus;
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

    const BUTTON=1;
    const STATUS=2;
    const IDHIDDEN=3;



    public function __construct($datas=[],$byName=true)
    {
        $this[self::BUTTON]=new ButtonModifier();
        $this[self::STATUS]=new InputCommentStatus();
        $this[self::IDHIDDEN]=new InputIdHidden();

        $this->postConstruct($datas,$byName);
    }

    public function check():bool
    {
        $checkOK=true;

        if(!$this->checkToken() ) {
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

               $this->renderToken() .
               $this->renderControl(self::IDHIDDEN) .
               $this->renderControl(self::STATUS) .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

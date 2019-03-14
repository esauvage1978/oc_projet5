<?php

namespace ES\App\Modules\Blog\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsTextaera;
use ES\Core\Form\WebControlsStandard\InputToken;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Toolbox\Url;


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
    const TOKEN=0;
    const COMMENT=2;
    const IDARTICLEHIDDEN=3;


    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //Commentaire
        $comment=new WebControlsTextaera($this->_formName);
        $comment->placeHolder='Votre commentaire';
        $comment->rows =5;
        $comment->name='comment';
        $this[self::COMMENT]=$comment;

        $idHidden=new WebControlsInput($this->_formName);
        $idHidden->name ='id';
        $idHidden->type=WebControlsInput::TYPE_HIDDEN;
        $this[self::IDARTICLEHIDDEN]=$idHidden;

        $this->setText($datas,$byName) ;
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
        return $this->getAction(Url::to('blog','comment','add#commentadd')) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::IDARTICLEHIDDEN) .
               $this->renderControl(self::COMMENT) .
               '</form>';
    }
}


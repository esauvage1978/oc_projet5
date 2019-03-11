<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;
use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControlsStandard\checkSecret;
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
class UserPwdChangeForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const SECRET_OLD=4;

    use checkSecret;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Changer';
        $button->name='update';
        $button->addCssClass(WebControlsButtons::CSS_ROUNDED);
        $this[self::BUTTON]=$button;

        //mot de passe
        $secret=new WebControlsInput($this->_formName);
        $secret->label ='Nouveau mot de passe';
        $secret->name='secretNew';
        $secret->type= WebControlsInput::TYPE_SECRET;
        $secret->maxLength=100;
        $secret->helpBlock =MSG_FORM_SECRET_HELPBLOCK;
        $this[self::SECRET_NEW]=$secret;

        //mot de passe
        $secretConfirm=new WebControlsInput($this->_formName);
        $secretConfirm->label ='Confirmation du mot de passe';
        $secretConfirm->name='secretConfirm';
        $secretConfirm->type= WebControlsInput::TYPE_SECRET;
        $secretConfirm->maxLength=100;
        $this[self::SECRET_CONFIRM]=$secretConfirm;

        //mot de passe
        $secretOld=new WebControlsInput($this->_formName);
        $secretOld->label ='Ancien mot de passe';
        $secretOld->name='secretOld';
        $secretOld->type= WebControlsInput::TYPE_SECRET;
        $secretOld->maxLength=100;
        $this[self::SECRET_OLD]=$secretOld;

        $this->setText($datas,$byName) ;
    }


    public function check():bool
    {
        $checkOK=true;

        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }

        if(!$this->checkSecretNewAndConfirm()) {
            $checkOK=false;
        }

        if(!$this[self::SECRET_OLD]->check()) {
            $checkOK=false;
        }

        return $checkOK ;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','pwdchange')) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::SECRET_OLD) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) .
               '<div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">' .
               $this->renderButton(self::BUTTON) .
               '<a  href="'. Url::to('user','modify') .'" class="btn btn-secondary">Retour</a>' .
               '</div>'.'</form>';
    }
}

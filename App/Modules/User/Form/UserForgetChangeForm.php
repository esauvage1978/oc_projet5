<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\Form;

use ES\Core\Form\WebControls\WebControlsButtons;
use ES\Core\Form\WebControlsStandard\InputToken;
use ES\Core\Form\WebControls\WebControlsInput;
use ES\Core\Form\WebControlsStandard\checkSecret;
use ES\Core\Toolbox\Url;


/**
 * UserConnexionForm short summary.
 *
 * UserConnexionForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForgetChangeForm extends Form
{
    const TOKEN=0;
    const BUTTON=1;
    const SECRET_NEW=2;
    const SECRET_CONFIRM=3;
    const HASH=4;

    public function __construct($datas=[],$byName=true)
    {
        $this->_formName=$this->getFormName();

        //ajout du token
        $token=new InputToken($this->_formName);
        $this[self::TOKEN]=$token;

        //ajout du bouton
        $button=new WebControlsButtons ($this->_formName);
        $button->label='Changer votre mot de passe';
        $button->name='change';
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

        $hash=new WebControlsInput($this->_formName);
        $hash->name ='hash';
        $hash->type=WebControlsInput::TYPE_HIDDEN;
        $hash->maxLength =60;
        $this[self::HASH]=$hash;

        $this->setText($datas,$byName) ;
    }

    use checkSecret;

    public function check():bool
    {
        $checkOK=true;


        if(!$this[self::TOKEN]->check() ) {
            $checkOK=false;
        }


        $value=$this[self::SECRET_NEW]->getText();
        if( empty($value)) {

            $this[self::SECRET_NEW]->setIsInvalid(MSG_FORM_NOT_GOOD);
            $checkOK=false;

        }  else if(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $value) ) {

            $this[self::SECRET_NEW]->setIsInvalid(MSG_FORM_SECRET_NOT_COMPLEX);
            $checkOK= false;
        }

        if(! $this->checkSecretNewAndConfirm()) {
            $checkOK =false;
        }

        return $checkOK;
    }

    public function render()
    {
        return $this->getAction(Url::to('user','pwdforgetchange')) .
                $this->renderControl(self::TOKEN) .
               $this->renderControl(self::TOKEN) .
               $this->renderControl(self::HASH) .
               $this->renderControl(self::SECRET_NEW) .
               $this->renderControl(self::SECRET_CONFIRM) . '<p><br/></p>' .
               $this->renderButton(self::BUTTON) .
               '</form>';
    }
}

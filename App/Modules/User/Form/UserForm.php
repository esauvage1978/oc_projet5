<?php

namespace ES\App\Modules\User\Form;

use ES\Core\Form\BootStrapForm;
use ES\Core\Toolbox\Auth;

/**
 * UserForm short summary.
 *
 * UserForm description.
 *
 * @version 1.0
 * @author ragus
 */
class UserForm extends BootStrapForm
{
    public static $formIdentifiant='identifiant';
    public static $formLogin='identifiant';
    public static $formHash='hash';
    public static $formIdHidden='idHidden';
    public static $formMail='mail';
    public static $formSecretOld='secretOld';
    public static $formSecretNew='secretNew';
    public static $formSecret='secret';
    public static $formSecretConfirm='secretConfirm';

    protected static $_loginInvalid='';
    protected static $_identifiantInvalid='';
    protected static $_mailInvalid='';
    protected static $_secretInvalid='';
    protected static $_secretOldInvalid='';
    protected static $_secretNewInvalid='';
    protected static $_secretConfirmInvalid='';

    #region LOGIN
    public function RenderLogin()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_LABEL]='Identifiant ou adresse mail';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_loginInvalid);
        return parent::input(static::$formLogin,$options);
    }
    protected function checkLogin():bool
    {
        $field=UserForm::$formLogin;
        $value=$this->getValue($field);
        if( empty($value) ||
            strlen($value)<=3 ||
            strlen($value)>100 ||
            !(string)filter_var($value)) {

            $this->flash->writeError('Le login est invalide');
            $this->isInvalid($field);
            return false;
        }
        return true;
    }
    #endregion

    #region IDENTIFIANT
    public function RenderIdentifiant()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=40;
        $options[parent::OPTIONS_LABEL]='Identifiant';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_identifiantInvalid);
        $options[parent::OPTIONS_HELP_BLOCK]='L\'identifiant doit avoir entre 4 et 45 caractères.';
        return parent::input(static::$formIdentifiant,$options);
    }

    protected function checkIdentifiant():bool
    {
        $field=UserForm::$formIdentifiant;
        $value=$this->getValue($field);
        if( empty($value) ||
            strlen($value)<=3 ||
            strlen($value)>45 ||
            !(string)filter_var($value)) {

            $this->flash->writeError('L\'identifiant est invalide');
            $this->isInvalid($field);
            return false;
        }
        return true;
    }
    #endregion

    #region HASH
    public function RenderHash()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_TYPE]=parent::OPTIONS_TYPE_HIDDEN;
        return parent::input(static::$formHash,$options);
    }

    #endregion

    #region IDHIDDEN
    public function RenderIdHidden()
    {
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_TYPE]=parent::OPTIONS_TYPE_HIDDEN;
        return parent::input(static::$formIdHidden,$options);
    }
    public function getIdHidden()
    {
        return $this->getValue(UserForm::$formIdHidden);
    }
    protected function checkIdHidden():bool
    {
        $field=UserForm::$formIdHidden;
        $value=$this->getValue($field);
        return !empty($value);
    }
    #endregion
    #region MAIL
    public function RenderMail()
    {
        $options[parent::OPTIONS_TYPE]=parent::OPTIONS_TYPE_EMAIL;
        $options[parent::OPTIONS_REQUIRED]='true';
        $options[parent::OPTIONS_MAXLENGHT]=100;
        $options[parent::OPTIONS_LABEL]='Adresse mail';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_mailInvalid);
        return parent::input(static::$formMail,$options);
    }

    protected function checkMail():bool
    {
        $field=UserForm::$formMail;
        $value=$this->getValue($field);
        if( empty($value) ||
            strlen($value)>100 ||
            !filter_var($value,FILTER_VALIDATE_EMAIL)) {

            $this->flash->writeError('Le mail est invalide');
            $this->isInvalid($field);
            return false;
        }
        return true;
    }
    #endregion
    #region SECRET
    public function RenderSecret()
    {
        $options[parent::OPTIONS_LABEL]='Mot de passe';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_secretInvalid);
        return parent::input_password(static::$formSecret,$options);
    }

    protected function checkSecret():bool
    {
        $field=UserForm::$formSecret;
        $value=$this->getValue($field);
        return !empty($value);
    }

    #endregion
    #region SECRET OLD
    public function RenderSecretOld()
    {
        $options[parent::OPTIONS_LABEL]='Ancien ot de passe';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_secretOldInvalid);
        return parent::input_password(static::$formSecretOld,$options);
    }

    protected function checkSecretOld():bool
    {
        $field=UserForm::$formSecretOld;
        $value=$this->getValue($field);
        return !empty($value);
    }

    #endregion
    #region SECRET NEW
    public function RenderSecretNew()
    {
        $options[parent::OPTIONS_LABEL]='Nouveau mot de passe';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_secretNewInvalid);
        $options[parent::OPTIONS_HELP_BLOCK]='Le mot de passe doit avoir au minimum 8 caractères et avoir les éléments suivants :<ul>
            <li>Une lettre minuscule</li>
            <li>Une lettre majuscule</li>
            <li>Un chiffre</li></ul>';

        return parent::input_password(static::$formSecretNew,$options);
    }

    protected function checkSecretNewAndConfirm():bool
    {
        $field=UserForm::$formSecretNew;
        $value=$this->getValue($field);
        $fieldConfirm=UserForm::$formSecretConfirm;
        $valueConfirm=$this->getValue($fieldConfirm);
        if(empty($value) ||
           empty($valueConfirm) ||
           !Auth::passwordCompare($value,$valueConfirm,false)) {

            $this->flash->writeError('Le mot de passe et/ou sa confirmation sont invalides' );
            $this->isInvalid($field);
            $this->isInvalid($fieldConfirm);
            return false;
        }
        if(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $value) ) {

            $this->flash->writeError('Le mot de passe ne correspond pas aux règles de complexité.');
            $this->isInvalid($field);
            return false;
        }

        return true;
    }
    #endregion

    #region SECRET CONFIRM
    public function RenderSecretConfirm()
    {
        $options[parent::OPTIONS_LABEL]='Confirmation du mot de passe';
        $options[parent::OPTIONS_VALID ]=$this::chooseClassCssValid(static::$_secretConfirmInvalid);
        return parent::input_password(static::$formSecretConfirm,$options);
    }


    #endregion



    public function isInvalid($field)
    {
        $option=parent::OPTIONS_VALID_NO;
        switch ($field)
        {
            case self::$formLogin:
                static::$_loginInvalid=$option;
                break;
            case self::$formIdentifiant:
                static::$_identifiantInvalid=$option;
                break;
            case self::$formMail:
                static::$_mailInvalid=$option;
                break;
            case self::$formSecret:
                static::$_secretInvalid=$option;
                break;
            case self::$formSecretNew:
                static::$_secretNewInvalid=$option;
                break;
            case self::$formSecretOld:
                static::$_secretOldInvalid=$option;
                break;
            case self::$formSecretConfirm:
                static::$_secretConfirmInvalid=$option;
                break;
            default:
                throw new \InvalidArgumentException('Option incorrecte');

        }

    }




}

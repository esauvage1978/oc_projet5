<?php

namespace ES\Core\Form\WebControls;


/**
 * WebControlsInput short summary.
 *
 * WebControlsInput description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsInput extends WebControls
{

    /**
     * Champ requis?
     * @var bool
     */
    public $require=true;
    /**
     * Donnée saisie dans le champ text
     * @var mixed
     */
    public $text;
    /**
     * Summary of $placeHolder
     * @var mixed
     */
    public $placeHolder;
    /**
     * *
     * @var int
     */
    public $maxLength;

    use Valid;




    protected static $buildParamsMaxLength='maxlenth';
    protected static $buildParamsPlaceHolder='placeholder';
    protected static $buildParamsRequire='require';
    protected static $buildParamsValue='value';

    const TYPE_TEXT='text';
    const TYPE_EMAIL='email';
    const TYPE_TEL='tel';
    const TYPE_SEARCH='search';
    const TYPE_URL='url';
    const TYPE_SECRET='password';
    const TYPE_HIDDEN='hidden';

    const MSG_NOT_GOOD='La saisie est incorrecte.';
    const MSG_LENGHT_NOT_GOOD='La longueur de la saisie est incorrecte.';


    public function render()
    {
        $this->addCssClass ('form-control');
        $input= '<input '.
            $this->buildParams([
                self::$buildParamsCSS,
                self::$buildParamsType,
                self::$buildParamsName,
                self::$buildParamsId,
                self::$buildParamsMaxLength,
                self::$buildParamsRequire,
                self::$buildParamsDisabled,
                self::$buildParamsPlaceHolder,
                self::$buildParamsValue]) .
            ' />';
        return $this->buildParams([self::$buildParamsLabel]) .
               $input .
               $this->buildParams([
               self::$buildParamsValid,
               self::$buildParamsHelBlock]);
    }

    protected function buildParams($keys=[]):string
    {
        $params='';
        $param='';
        foreach($keys as $key)
        {
            switch ($key)
            {
                case self::$buildParamsType:
                    if(isset($this->type)) {
                        $param='type="'. $this->type . '"';
                    } else {
                        $param='type="'. self::TYPE_TEXT . '"';
                    }
                    break;
                case self::$buildParamsValue:
                    if(isset($this->text)) {
                        $param='value="'. $this->text . '"';
                    }
                    break;
                case self::$buildParamsRequire:
                    if ($this->require) {
                        $param='	required';
                    }
                    break;
                case self::$buildParamsValid:
                    if ($this->_showIsValid) {
                        $param='<div class="valid-feedback">' . $this->_validMessage . '</div>';
                    } else if ($this->_showIsInvalid) {
                        $param='<div class="invalid-feedback">' . $this->_validMessage . '</div>';
                    }

                    break;
                case self::$buildParamsMaxLength:
                    if(isset($this->maxLength) && is_int($this->maxLength) ) {
                        $param='maxLength="'. $this->maxLength  . '"';
                    }
                    break;
                case self::$buildParamsPlaceHolder:
                    if(isset($this->placeHolder) && $this->type != self::TYPE_HIDDEN ) {
                        $param='placeholder="'. $this->placeHolder  . '"';
                    }
                    break;
                case self::$buildParamsLabel:
                    if(isset($this->label)) {
                        $param='<label for="' . $this->name . '">'. $this->label  .'</label>';
                    }
                    break;
                default:
                    $param=parent::buildParams([$key]);
                    break;
            }

            if($param!=self::PARAMS_NOT_FOUND && !empty($param)) {
                $param .=' ';
            }
            $params .= $param;
        }
        return $params;
    }


    public function check():bool
    {
        $retour=true;
        $value=$this->text;
        if( empty($value)) {

            $this->setIsInvalid(self::MSG_NOT_GOOD);
            $retour=false;
        }

        return $retour;
    }
    protected function checkLenght($mini=null,$maxi=null):bool
    {
        $retour=true;
        $value=$this->text;
        if( isset($mini) && strlen($value)<=$mini ) {

            $this->setIsInvalid(self::MSG_LENGHT_NOT_GOOD . ' (plus de ' . $mini . ' caractères).');
            $retour=false;
        } else if( isset($maxi) && strlen($value)>$maxi ) {

            $this->setIsInvalid(self::MSG_LENGHT_NOT_GOOD . ' (moins de ' . $maxi . ' caractères).');
            $retour=false;
        }
        return $retour;
    }

}
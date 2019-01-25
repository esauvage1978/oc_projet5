<?php

namespace ES\Core\Form\WebControls;


/**
 * WebControlsTextaera short summary.
 *
 * WebControlsTextaera description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsCheckbox extends WebControls
{
    use Valid ;

    /**
     * Donnée saisie dans le champ text
     * @var mixed
     */
    public $text;

    protected static $buildParamsChecked='checked';

    public function render()
    {
        $this->type='checkbox';
        $this->addCssClass ('custom-control-input');

        return '<div class="custom-control custom-checkbox"><input '.
            $this->buildParams([
                self::$buildParamsCSS,
                self::$buildParamsType,
                self::$buildParamsName,
                self::$buildParamsId,
                self::$buildParamsDisabled,
                self::$buildParamsChecked]) .
            '>' .
            $this->buildParams([
                self::$buildParamsLabel,
                self::$buildParamsHelBlock]) .
            '</div>'.
            $this->buildParams([
                self::$buildParamsValid
                ]) ;

    }

    protected function buildParams($keys=[]):string
    {
        $params='';
        $param='';
        foreach($keys as $key)
        {
            switch ($key)
            {

                case self::$buildParamsChecked:
                    if(isset($this->text) && ($this->text =='1' || $this->text =='on' )) {
                        $param ='checked';
                    }
                    break;
                case self::$buildParamsLabel:
                    if(isset($this->label)) {
                        $param= '<label class="custom-control-label" for="' . $this->name . '">'. $this->label  .'</label>';
                    }
                    break;
                case self::$buildParamsValid:
                    if ($this->_showIsValid) {
                        $param='<div class="valid-feedback">' . $this->_validMessage . '</div>';
                    } else if ($this->_showIsInvalid) {
                        $param='<div class="invalid-feedback">' . $this->_validMessage . '</div>';
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

    public function isChecked() :bool
    {
        $retour=false;
        if($retour=='1' || $retour=='on' ) {
            return true;
        }
        return $retour;
    }
}
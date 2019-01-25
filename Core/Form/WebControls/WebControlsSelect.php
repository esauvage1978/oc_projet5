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
class WebControlsSelect extends WebControls
{
    use Valid;

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
     * Liste des valeurs de la liste déroulante
     * @var array
     */
    public $liste;

    protected static $buildParamsListe='liste';
    protected static $buildParamsRequire='require';

    public function render()
    {
        $this->addCssClass ('form-control');
        $input = '<select '.
            $this->buildParams([
                self::$buildParamsCSS,
                self::$buildParamsName,
                self::$buildParamsId,
                self::$buildParamsRequire,
                self::$buildParamsDisabled]) .
            '>'.
            $this->buildParams([self::$buildParamsListe]) .
            '</select>';
        return $this->buildParams([self::$buildParamsLabel]) .
            $input .
            $this->buildParams([self::$buildParamsHelBlock]);

    }
    protected function buildParams($keys=[]):string
    {
        $params='';
        $param='';
        foreach($keys as $key)
        {
            switch ($key)
            {
                case self::$buildParamsListe:
                    if(isset($this->liste)) {
                        foreach ($this->liste as $listekey => $listevalue) {
                            $attributes = '';
                            if($listekey == $this->text) {
                                $attributes = ' selected';
                            }
                            $param .= '<option value="' . $listekey . '"' . $attributes . '>' . $listevalue . '</option>';
                        }
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

}
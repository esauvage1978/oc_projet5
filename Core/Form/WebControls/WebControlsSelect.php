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
    use ParamValid;
    use ParamRequire;

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


    const MSG_NOT_GOOD='La saisie est incorrecte.';
    protected static $buildParamsListe='liste';

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
                    $param=$this->paramBuildsRequire();
                    break;
                case self::$buildParamsValid:
                    $param=$this->paramBuildsValid();
                    break;
                case self::$buildParamsLabel:
                    if(isset($this->label)) {
                        $param='<label for="' . $this->getName() . '">'. $this->label  .'</label>';
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
}
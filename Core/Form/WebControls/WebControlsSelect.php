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
class WebControlsSelect extends WebControlsParamText
{
    use ParamValid;
    use ParamRequire;


    /**
     * Liste des valeurs de la liste déroulante
     * @var array
     */
    public $liste;


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

                            $listekey == $this->getText()?
                            $attributes = ' selected':
                            $attributes = '';

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
                    $param=$this->label?:
                        '<label for="' . $this->getName() . '">'. $this->label  .'</label>';
                    break;
                default:
                    $param=parent::buildParams([$key]);
                    break;
            }

            if($param!=MSG_FORM_PARAMS_NOT_FOUND && !empty($param)) {
                $param .=' ';
            }
            $params .= $param;
        }
        return $params;
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->getText();

        if( empty($value)) {
            $this->setIsInvalid(MSG_FORM_NOT_GOOD);
            $retour=false;
        }

        return $retour;
    }
}
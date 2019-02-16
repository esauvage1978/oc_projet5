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
class WebControlsFile extends WebControls
{
    use ParamValid;
    use ParamRequire;

    /**
     * Donnée saisie dans le champ text
     * @var mixed
     */
    public $text;



    const MSG_NOT_GOOD='La saisie est incorrecte.';

    public function render()
    {
        $this->addCssClass ('custom-file-input');
        $input = '<div class="custom-file"><input  type="file"'.
            $this->buildParams([
                self::$buildParamsCSS,
                self::$buildParamsName,
                self::$buildParamsId,
                self::$buildParamsRequire,
                self::$buildParamsDisabled]) .
            '>';

        return $input .
            $this->buildParams([self::$buildParamsLabel]) .
            $this->buildParams([self::$buildParamsHelBlock]) .'</div>';

    }
    protected function buildParams($keys=[]):string
    {
        $params='';
        $param='';
        foreach($keys as $key)
        {
            switch ($key)
            {
                case self::$buildParamsRequire:
                    $param=$this->paramBuildsRequire();
                    break;
                case self::$buildParamsValid:
                    $param=$this->paramBuildsValid();
                    break;
                case self::$buildParamsLabel:
                    if(isset($this->label)) {
                        $param='<label class="custom-file-label" for="' . $this->getName() . '"  data-browse="Parcourir" >'. $this->label  .'</label>';
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
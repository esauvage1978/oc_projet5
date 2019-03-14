<?php

namespace ES\Core\Form\WebControls;


class WebControlsFile extends WebControlsParamText
{
    use ParamValid;
    use ParamRequire;

    private $_text;
    public function setText(string $value)
    {
        $this->_text=$value;
    }
    public function getText()
    {
        return $this->_text;
    }


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
            $this->setIsInvalid(MSG_FORM_PARAMS_NOT_FOUND);
            $retour=false;
        }

        return $retour;
    }
}
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
class WebControlsTextaera extends WebControlsParamText
{
    use ParamRequire;
    use ParamPlaceholder;
    use ParamValid ;

    /**
     * Summary of $rows
     * @var int
     */
    public $rows=5;

    protected static $buildParamsRows='rows';
    protected static $buildParamsValue='value';

    public function render()
    {
        $this->addCssClass ('form-control');
        $input= '<textarea  '.
            $this->buildParams([
                self::$buildParamsCSS,
                self::$buildParamsType,
                self::$buildParamsName,
                self::$buildParamsId,
                self::$buildParamsRows,
                self::$buildParamsRequire,
                self::$buildParamsDisabled,
                self::$buildParamsPlaceHolder]) .
            '>'.
            $this->buildParams([self::$buildParamsValue]) .
            '</textarea>';
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
                case self::$buildParamsValue:
                    $param=$this->getText();
                    break;
                case self::$buildParamsRows:
                    if(isset($this->rows) && is_int($this->rows) ) {
                        $param='rows="'. $this->rows  . '"';
                    }
                    break;
                case self::$buildParamsLabel:
                    if(isset($this->label)) {
                        $param='<label for="' . $this->getName() . '">'. $this->label  .'</label>';
                    }
                    break;
                case self::$buildParamsRequire:
                    $param=$this->paramBuildsRequire();
                    break;
                case self::$buildParamsValid:
                    $param=$this->paramBuildsValid();
                    break;
                case self::$buildParamsPlaceHolder:
                    $param=$this->paramBuildsPlaceholder();
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



}
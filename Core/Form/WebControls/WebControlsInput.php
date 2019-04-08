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
class WebControlsInput extends WebControlsParamText
{
    use ParamRequire;
    use ParamPlaceholder;
    use ParamValid;

    /**
     * *
     * @var int
     */
    public $maxLength;




    protected static $buildParamsMaxLength='maxlenth';


    protected static $buildParamsValue='value';

    const TYPE_TEXT='text';
    const TYPE_EMAIL='email';
    const TYPE_TEL='tel';
    const TYPE_SEARCH='search';
    const TYPE_URL='url';
    const TYPE_SECRET='password';
    const TYPE_HIDDEN='hidden';



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
                    $param=$this->paramBuildsText();
                    break;
                case self::$buildParamsRequire:
                    $param=$this->paramBuildsRequire();
                    break;
                case self::$buildParamsValid:
                    $param=$this->paramBuildsValid();
                    break;
                case self::$buildParamsMaxLength:
                    if(isset($this->maxLength) && is_int($this->maxLength) ) {
                        $param='maxLength="'. $this->maxLength  . '"';
                    }
                    break;
                case self::$buildParamsPlaceHolder:
                    if($this->type != self::TYPE_HIDDEN ) {
                        $param=$this->paramBuildsPlaceholder();
                    }
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

            if($param!=MSG_FORM_PARAMS_NOT_FOUND && !empty($param)) {
                $param .=' ';
            }
            $params .= $param;
        }
        return $params;
    }





    public static function CreateHiddenId($formName,$controlName='idhidden',$controlInt=true)
    {
        $idHidden=new WebControlsInput($formName);
        $idHidden->name =$controlName;
        $idHidden->type=WebControlsInput::TYPE_HIDDEN;
        $idHidden->maxLength =60;
        if($controlInt) {
            $idHidden->defaultControl=WebControlsInput::CONTROLE_INT;
        }
        return $idHidden;
    }
    public static function CreateInput($formName,$controlName,$controlLabel,$controlPlaceHolder=null,$controlLenght=100,$controlType=WebControlsInput::TYPE_TEXT)
    {
        $input=new WebControlsInput($formName);
        $input->name =$controlName;
        $input->label =$controlLabel;
        $input->placeHolder =$controlPlaceHolder;
        $input->maxLength =$controlLenght ;
        $input->type = $controlType;
        return $input;
    }
}
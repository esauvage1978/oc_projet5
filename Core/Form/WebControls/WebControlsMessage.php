<?php

namespace ES\Core\Form\WebControls;

/**
 * WebControlsMessage short summary.
 *
 * WebControlsMessage description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsMessage extends WebControls
{

    const TYPE_DANGER="alert-danger";
    const TYPE_SUCCESS="alert-success";
    //const TYPE_INFO="alert-info";
    //const TYPE_WARNING="alert-warning";

    private $_type_message;
    private $_message;

    public function render()
    {
        if(isset($this->_message)){
            return '<div '.
                $this->buildParams([self::$buildParamsType]) .
                '  role="alert">'.
                $this->buildParams([self::$buildParamsLabel]) .
                '</div>';
        }
        return '';
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
                    if(isset($this->_type_message)) {
                    $param='class="alert ' . $this->_type_message .' alert-dismissible fade show"';
                    } else {
                        $param='class="alert ' .self::TYPE_SUCCESS . ' alert-dismissible fade show"';
                    }
                    break;
                case self::$buildParamsLabel:

                    $param=$this->_message;
                    break;
            }

            if($param!=MSG_FORM_PARAMS_NOT_FOUND && !empty($param)) {
                $param .=' ';
            }
            $params .= $param;
        }
        return $params;
    }

    public function showMessage($message,$success=true)
    {
        $this->_message =$message ;
        $this->_type_message =($success?self::TYPE_SUCCESS:self::TYPE_DANGER);
    }

}
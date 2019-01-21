<?php

namespace ES\Core\Form\WebControls;

/**
 * WebControls short summary.
 *
 * WebControls description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControls
{
    protected $_name='name';
    protected $_id;
    protected $_label;
    protected $_cssClass;
    protected $_disabled;
    protected $_type;
    protected $_helpBlock;
    protected $_isValid;
    protected $_isInvalid;
    protected $_readOnly;

    private $data;

    public function __construct($data)
    {
        $this->_data = $data;
    }

    protected function getValue($index)
    {
        $data=null;

        if(is_array ( $this->_data)) {
            $data=array_key_exists($index,$this->_data)?$this->_data[$index]:null;
        } else {
            $data=$this->_data->getPostValue($index);
        }

        return $data;
    }

    #region SET
    public function setReadOnly()
    {
        $this->_readOnly='readonly';
    }
    public function SetName($value)
    {
        $this->_name =$value;
    }
    public function SetId($value)
    {
        $this->_id =$value;
    }
    public function SetLabel($value)
    {
        $this->_label=$value;
    }
    public function SetType($value)
    {
        $this->_type=$value;
    }
    public function addCssClass($value)
    {
        $this->_cssClass.= ' '. $value;
    }
    public function SetHelpBlock($value)
    {
        $this->_helpBlock=$value;
    }
    public function setIsValid($value)
    {
        $this->addCssClass ('is-valid');
        $this->_isValid=$value;
    }
    public function setDisabled()
    {
        $this->_disabled= 'disabled';
    }
    public function setIsInvalid($value)
    {
        $this->addCssClass ('is-invalid');
        $this->_isInvalid=$value;
    }
    #endregion

    #region GET
    public function getReadOnly():string
    {
        return $this->_readOnly. ' ';
    }
    public function getDisabled():string
    {
        return $this->_disabled. ' ';
    }
    protected function getCssClass()
    {
        return 'class="' . $this->_cssClass . '" ';
    }
    protected function getType()
    {
        return 'type="' . $this->_type . '" ';
    }
    protected function getName()
    {
        return 'name="'. $this->_name . '" ';
    }
    protected function getHelpBlock()
    {
        return isset($this->_helpBlock)
            ? '<small class="form-text text-muted">'. $this->_helpBlock . '</small>'
            :'';
    }
    protected function getId()
    {
        if(isset($this->_id)) {
            return 'id="'.$this->_id . '" ';
        } else {
            return 'id="'. $this->_name . '" ';
        }

    }
    public function getLabel()
    {
        return isset($this->_label)?$this->_label:$this->_name . ' ';
    }
    protected function getValid()
    {
        $retour='';
        if (isset($this->_isValid))
        {
            $retour='<div class="valid-feedback">' . $this->_isValid . '</div>';
        }
        else if (isset($this->_isInvalid))
        {
            $retour='<div class="invalid-feedback">' . $this->_isInvalid . '</div>';
        }
        return  $retour;

    }
    #endregion
}
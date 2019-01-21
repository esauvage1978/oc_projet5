<?php

namespace ES\Core\Form\WebControls;

use ES\Core\Form\WebControls\WebControls;

/**
 * WebControlsInput short summary.
 *
 * WebControlsInput description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsInput extends WebControls
{
    protected $_type=self::TYPE_TEXT;
    protected $_require;
    protected $_placeHolder;
    protected $_maxLength;


    protected $_cssClass='form-control';

    const TYPE_TEXT='text';
    const TYPE_EMAIL='email';
    const TYPE_SECRET='password';
    const TYPE_HIDDEN='hidden';

    public function render()
    {

        $input= '<input '.
            $this->getType() .
            $this->getName() .
            $this->getMaxLength() .
            $this->getRequired() .
            $this->getReadOnly() .
            $this->getId() .
            $this->getCssClass() .
            $this->getPlaceholder() .
            ' value="' . $this->text() . '" />';
        return $this->getLabel() . $input . $this->getValid() . $this->getHelpBlock();
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->text();
        if( empty($value)) {

            $this->setIsInvalid('La saisie est incorrecte.');
            $retour=false;

        }
        return $retour;
    }
    #region SET
    public function setRequired()
    {
        $this->_require='Required';
    }
    public function setPlaceHolder($value)
    {
        $this->_placeHolder =$value;
    }
    public function setMaxLength($value)
    {
        $this->_maxLength =$value;
    }
    #endregion

    #region GET
    public function getRequired():string
    {
        return $this->_require. ' ';
    }
    public function getPlaceHolder():string
    {
        return isset($this->_placeHolder)? 'placeholder="'. $this->_placeHolder  . '" ':'';
    }
    public function getMaxLength()
    {
        return isset($this->_maxLength)?'maxlength="' . $this->_maxLength . '" ':'';
    }
    public function text()
    {
        return $this->getValue($this->_name);
    }
    public function getLabel()
    {
        $retour= '<label for="' . $this->_name . '">'. parent::getLabel()  .'</label>';

        if($this->_type==self::TYPE_HIDDEN)
            $retour='';

        return $retour;
    }
    #endregion
}
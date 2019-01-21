<?php

namespace ES\Core\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsInput;
/**
 * WebControlsTextaera short summary.
 *
 * WebControlsTextaera description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsTextaera extends WebControlsInput
{
    protected $_rows;

    public function render()
    {
        $input = '<textarea '.
           $this->getName() .
           $this->getRequired() .
           $this->getReadOnly() .
           $this->getId() .
           $this->getCssClass() .
           $this->getPlaceHolder().
           $this->getRows().
           ' >' . $this->text()
           . '</textarea>';
        return $this->_label . $input . $this->_helpBlock;

    }


    #region SET
    public function setRows($value)
    {
        $this->_rows =$value;
    }

    #endregion

    #region GET
    public function getRows():string
    {
        return 'rows="'. $this->_rows  . '" ';
    }
    #endregion
}
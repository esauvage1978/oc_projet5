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
class WebControlsSelect extends WebControlsInput
{
    protected $_liste;

    public function render()
    {
        $input = '<select '.
           $this->getName() .
           $this->getRequired() .
           $this->getReadOnly() .
           $this->getId() .
           $this->getCssClass() .
           $this->getPlaceHolder().
           $this->getDisabled().
            '>';
        foreach ($this->getListe() as $key => $value) {
            $attributes = '';
            if($key == $this->text()) {
                $attributes = ' selected';
            }
            $input .= '<option value=' . $key . $attributes . '>' . $value . '</option>';
        }
        $input .= '</select>';
        return $this->getLabel() . $input . $this->getHelpBlock();

    }


    #region SET
    public function setListe(array $value)
    {
        $this->_liste =$value;
    }

    #endregion

    #region GET
    public function getListe():array
    {
        return $this->_liste ;
    }
    #endregion
}
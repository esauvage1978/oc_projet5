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
class WebControlsCheckbox extends WebControls
{
    protected $_liste;
    protected $_cssClass='custom-control-input';

    public function render()
    {
        return '<div class="custom-control custom-checkbox"><input type="checkbox"  '.
           $this->getName() .
           $this->getReadOnly() .
           $this->getId() .
           $this->getCssClass() .
           $this->getDisabled().
           $this->getChecked() .
            '>' .
            $this->getLabel()  .
            $this->getHelpBlock() .
            '</div>';
    }

    public function text()
    {
        return $this->getValue($this->_name);
    }
    public function getLabel()
    {
        return '<label class="custom-control-label" for="' . $this->_name . '">'
            . $this->_label  . '</label>';
    }
    public function getChecked()
    {
        $retour=$this->text();
        if($retour=='1' || $retour=='on' ) {
            return 'checked';
        }
        return '';
    }
    public function isChecked() :bool
    {
        $retour=$this->render();
        if(! strstr($retour, 'checked')) {
            return false;
        }
        return true;
    }
}
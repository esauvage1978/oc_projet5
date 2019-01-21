<?php

namespace ES\Core\Form\WebControls;

use ES\Core\Form\WebControls\WebControls;

/**
 * WebControlsSubmit short summary.
 *
 * WebControlsSubmit description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsButtons extends WebControls
{

    protected $_cssClass='btn';

    protected $_type=self::TYPE_SUBMIT;

    const CSS_PRIMARY='btn-primary';
    const CSS_SECONDARY='btn-secondary';
    const CSS_SUCCESS='btn-success';
    const CSS_DANGER='btn-danger';
    const CSS_WARNING='btn-warning';
    const CSS_INFO='btn-info';
    const CSS_LIGHT='btn-light';
    const CSS_DARK='btn-dark';
    const CSS_LINK='btn-link';

    const TYPE_SUBMIT='submit';
    const TYPE_BUTTON='button';
    const TYPE_RESET='reset';

    const CSS_LARGE='btn-lg';
    const CSS_BLOCK='btn-block';

    public function __construct()
    {
    }



    public function render()
    {
        return '<button ' .
            $this->getCssClass() .
            $this->getType() .
            $this->getName() .
            $this->getId() .
            '>'.
            $this->getLabel()
            . '</button>';
    }





}
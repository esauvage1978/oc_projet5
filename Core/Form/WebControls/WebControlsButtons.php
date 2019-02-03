<?php

namespace ES\Core\Form\WebControls;


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
    /* CSS propre aux boutons */
    const CSS_PRIMARY='btn-primary';
    const CSS_SECONDARY='btn-secondary';
    const CSS_SUCCESS='btn-success';
    const CSS_DANGER='btn-danger';
    const CSS_WARNING='btn-warning';
    const CSS_INFO='btn-info';
    const CSS_LIGHT='btn-light';
    const CSS_DARK='btn-dark';
    const CSS_LINK='btn-link';
    const CSS_LARGE='btn-lg';
    const CSS_BLOCK='btn-block';

    public function render()
    {
        $this->addCssClass('btn');
        $this->type='submit';

        return '<button ' .
            $this->buildParams([
                self::$buildParamsCSS,
                self::$buildParamsType,
                self::$buildParamsName,
                self::$buildParamsId,
                self::$buildParamsCSS,
                self::$buildParamsDisabled]) .
            '>'.
            $this->buildParams([self::$buildParamsLabel]) .
            '</button>' .
            $this->buildParams([self::$buildParamsHelBlock]);
    }





}
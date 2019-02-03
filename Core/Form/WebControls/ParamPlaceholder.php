<?php

namespace ES\Core\Form\WebControls;

/**
 * ParamText short summary.
 *
 * ParamText description.
 *
 * @version 1.0
 * @author ragus
 */
trait ParamPlaceholder
{
    /**
     * Summary of $placeHolder
     * @var mixed
     */
    public $placeHolder;

    protected static $buildParamsPlaceHolder='placeholder';

    private function paramBuildsPlaceholder()
    {
        if(isset($this->placeHolder) ) {
            return 'placeholder="'. $this->placeHolder  . '"';
        }
        return '';
    }
}

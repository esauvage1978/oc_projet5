<?php

namespace ES\Core\Form\WebControls;

/**
 * Required short summary.
 *
 * Required description.
 *
 * @version 1.0
 * @author ragus
 */
trait ParamRequire
{
    /**
     * Champ requis?
     * @var bool
     */
    public $require=true;

    protected static $buildParamsRequire='require';

    private function paramBuildsRequire()
    {
        if ($this->require) {
            return '	required';
        }
        return '';
    }
}

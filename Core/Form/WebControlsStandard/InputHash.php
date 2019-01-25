<?php

namespace ES\Core\Form\WebControlsStandard;

use ES\Core\Form\WebControls\WebControlsInput;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class InputHash extends WebControlsInput
{
    const NAME='hash';

    public function __construct()
    {
        $this->name =self::NAME;
        $this->type=parent::TYPE_HIDDEN;
        $this->maxLength =60;
    }
}
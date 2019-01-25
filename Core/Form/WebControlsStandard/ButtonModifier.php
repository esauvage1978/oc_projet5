<?php

namespace ES\Core\Form\WebControlsStandard;

use ES\Core\Form\WebControls\WebControlsButtons;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class ButtonModifier extends WebControlsButtons
{
    private $_webControls;

    public function __construct()
    {
        $this->label='Modifier';
        $this->name='modify';
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
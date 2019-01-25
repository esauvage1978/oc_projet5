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
class ButtonAjouter extends WebControlsButtons
{

    public function __construct()
    {

        $this->label='Ajouter';
        $this->name='add';
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
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
class ButtonRecupere extends WebControlsButtons
{

    public function __construct()
    {
        $this->label='Récupérer';
        $this->name='recup';
        $this->addCssClass(parent::CSS_PRIMARY);

    }

}
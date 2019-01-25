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
class ButtonConnexion extends WebControlsButtons
{

    public function __construct()
    {
        $this->label='Connexion';
        $this->name='connexion';
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
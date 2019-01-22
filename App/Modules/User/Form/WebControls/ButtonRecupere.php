<?php

namespace ES\App\Modules\User\Form\WebControls;

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
        $this->SetLabel('Récupérer');
        $this->setName('recup');
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
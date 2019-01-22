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
class ButtonConnexion extends WebControlsButtons
{

    public function __construct()
    {
        $this->SetLabel('connexion');
        $this->setName('Connexion');
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
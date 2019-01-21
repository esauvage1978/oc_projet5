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
class ButtonModifier extends WebControlsButtons
{
    private $_webControls;

    public function __construct()
    {
        $this->SetLabel('Modifier');
        $this->setName('modif');
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
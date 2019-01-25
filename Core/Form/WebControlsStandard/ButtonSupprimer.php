<?php

namespace ES\App\Modules\Blog\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsButtons;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class ButtonSupprimer extends WebControlsButtons
{

    public function __construct()
    {
        $this->label='Supprimer';
        $this->name='delete';
        $this->addCssClass(parent::CSS_PRIMARY);
    }

}
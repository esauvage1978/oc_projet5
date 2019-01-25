<?php

namespace ES\App\Modules\User\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsCheckbox;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class CheckboxActif extends WebControlsCheckbox
{
    const NAME='chkactif';

    public function __construct()
    {
        //$this->label='Compte actif';
        $this->name=self::NAME;
    }

}
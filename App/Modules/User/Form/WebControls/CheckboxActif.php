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

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Compte actif');
        $this->setName(self::NAME);
    }

}
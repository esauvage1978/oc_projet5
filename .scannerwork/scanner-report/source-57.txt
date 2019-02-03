<?php

namespace ES\App\Modules\User\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsSelect;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class SelectAccreditation extends WebControlsSelect
{
    const NAME='habil';

    public function __construct()
    {
        $this->label='Habilitation';
        $this->name=self::NAME;
        $this->liste =ES_ACCREDITATION;
    }

}
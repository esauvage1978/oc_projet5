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
class SelectUserRole extends WebControlsSelect
{
    const NAME='habil';

    public function __construct()
    {
        $this->label='Rôle';
        $this->name=self::NAME;
        $this->liste =ES_USER_ROLE;
    }

}
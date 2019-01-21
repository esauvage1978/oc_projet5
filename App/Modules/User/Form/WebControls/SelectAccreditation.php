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

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Habilitation');
        $this->setName(self::NAME);
        $this->setListe(ES_ACCREDITATION);
    }

}
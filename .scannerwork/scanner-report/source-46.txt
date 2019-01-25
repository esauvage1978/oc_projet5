<?php

namespace ES\App\Modules\User\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsInput;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class InputHash extends WebControlsInput
{
    const NAME='hash';

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->setName(self::NAME);
        $this->SetType(parent::TYPE_HIDDEN);
        $this->setMaxLength (60);
    }
}
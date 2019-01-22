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
class InputSecret extends WebControlsInput
{

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Mot de passe');
        $this->SetType(WebControlsInput::TYPE_SECRET);
        $this->setName('secret');
        $this->setRequired();
        $this->setMaxLength (100);
    }


}
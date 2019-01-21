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
class InputSecretOld extends WebControlsInput
{

    public function __construct($data)
    {
        parent::__construct ($data);
        $this->SetLabel('Ancien mot de passe');
        $this->SetType(WebControlsInput::TYPE_SECRET);
        $this->setName('secretOld');
        $this->setRequired();
        $this->setMaxLength (100);
    }


}
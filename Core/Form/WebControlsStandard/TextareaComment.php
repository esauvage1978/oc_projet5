<?php

namespace ES\Core\Form\WebControlsStandard;

use ES\Core\Form\WebControls\WebControlsTextaera;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class TextareaComment extends WebControlsTextaera
{
    const NAME='comment';

    public function __construct()
    {
        $this->placeHolder='Votre commentaire';
        $this->rows =5;
        $this->name=self::NAME;
    }


}
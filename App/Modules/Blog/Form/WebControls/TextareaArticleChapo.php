<?php

namespace ES\App\Modules\Blog\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsTextaera;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class TextareaArticleChapo extends WebControlsTextaera
{
    const NAME='articleChapo';

    public function __construct()
    {
        $this->label='Texte chapo';
        $this->rows =3;
        $this->name=self::NAME;
    }


}
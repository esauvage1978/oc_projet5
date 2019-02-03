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
class TextareaArticleContent extends WebControlsTextaera
{
    const NAME='articleContent';

    public function __construct()
    {
        $this->label='Contenu';
        $this->rows =10;
        $this->name=self::NAME;
    }


}
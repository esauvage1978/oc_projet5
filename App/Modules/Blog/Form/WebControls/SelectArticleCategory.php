<?php

namespace ES\App\Modules\Blog\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsSelect;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class SelectArticleCategory extends WebControlsSelect
{
    const NAME='articleCategory';

    public function __construct()
    {
        $this->label='Catégorie';
        $this->name=self::NAME;
    }

}
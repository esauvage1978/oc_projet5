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
class SelectArticleState extends WebControlsSelect
{
    const NAME='articleState';

    public function __construct()
    {
        $this->label='Statut';
        $this->name=self::NAME;
    }

}
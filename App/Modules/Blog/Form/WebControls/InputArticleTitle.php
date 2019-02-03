<?php

namespace ES\App\Modules\Blog\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsInput;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class InputArticleTitle extends WebControlsInput
{
    const NAME='articleTitle';

    public function __construct()
    {
        $this->label='Titre';
        $this->name=self::NAME;
        $this->maxLength=255;
    }

    public function check():bool
    {
        $retour=true;

        if( !parent::check() || !parent::checkLenght(null,$this->maxLength) ) {
            $retour=false;
        }
        return $retour;
    }

}
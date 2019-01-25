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
class InputCategory extends WebControlsInput
{
    const NAME='category';

    public function __construct()
    {
        $this->placeHolder='Catégorie';
        $this->name=self::NAME;
        $this->maxLength=20;
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
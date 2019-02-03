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
class InputCommentStatus extends WebControlsInput
{
    const NAME='status';

    public function __construct()
    {
        $this->placeHolder='Status';
        $this->name=self::NAME;
        $this->maxLength=1;
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
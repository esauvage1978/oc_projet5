<?php

namespace ES\App\Modules\Blog\Form\WebControls;

use ES\Core\Form\WebControls\WebControlsFile;
/**
 * ButtonsConnexion short summary.
 *
 * ButtonsConnexion description.
 *
 * @version 1.0
 * @author ragus
 */
class FilePresentation extends WebControlsFile
{
    const NAME='articlePresentation';

    public function __construct()
    {
        $this->label='Image de présentation (format jpg)';
        $this->name=self::NAME;
        $this->require =false;
        
    }

    public function check():bool
    {
        $retour=true;

        return $retour;
    }

}
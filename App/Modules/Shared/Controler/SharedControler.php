<?php

namespace ES\App\Modules\Shared\Controler;


use \ES\Core\Controler\AbstractControler;

/**
 * SharedControler short summary.
 * Controler frontal de base
 * SharedControler description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedControler extends AbstractControler
{
    protected static $module='Shared';

    public function homeShow()
    {
        $this->renderView->render(
            ES_ROOT_PATH_FAT_MODULES . 'Shared\\View\\HomeView.php',
            array('title'=>'Page d\'accueil')
        );
    }
}
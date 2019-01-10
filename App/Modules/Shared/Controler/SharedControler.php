<?php

namespace ES\App\Modules\Shared\Controler;


use \ES\App\Modules\Shared\Render\SharedRenderView;
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
    private $_renderView;

    public function __construct()
    {
        parent::__construct();
        $this->_renderView=new SharedRenderView();
    }

    public function homeShow()
    {
        $this->_renderView->show(
            ES_ROOT_PATH_FAT_MODULES . 'Shared\\View\\HomeView.php',
            array('title'=>'Page d\'accueil')
        );
    }
}
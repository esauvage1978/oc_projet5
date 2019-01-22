<?php

namespace ES\App\Modules\Shared\Controller;

use \ES\Core\Controller\AbstractController;


/**
 * SharedController short summary.
 * Controller frontal de base
 * SharedController description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedController extends AbstractController
{
    static $module='Shared';

    public function homeShow()
    {
        $this->view('HomeView',
            [
                'title'=>'Page d\'accueil'
            ]);
    }


}
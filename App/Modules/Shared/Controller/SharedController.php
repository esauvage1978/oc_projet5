<?php

namespace ES\App\Modules\Shared\Controller;

use \ES\Core\Controller\AbstractController;
use \ES\App\Modules\User\Controller\UserController;


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
        $this->view('HomeView',['title'=>'Page d\'accueil']);
    }

    public function dashboard()
    {
        $userController=new UserController();
        $contentDashboard=$userController->getWidgetDashboard ();
        $this->view('DashboardView',
    [
        'title'=>'Tableau de bord',
        'contentDashboard'=>$contentDashboard
            ]);
    }

}
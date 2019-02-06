<?php

namespace ES\App\Modules\Shared\Controller;

use \ES\Core\Controller\AbstractController;
use \ES\App\Modules\User\Controller\UserController;
use \ES\App\Modules\Blog\Controller\BlogController;

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

    public function Show()
    {
        $this->view('HomeView',['title'=>'Page d\'accueil']);
    }

    public function accessdenied()
    {
        $this->view('AccessDenied',['title'=>'Accès interdit']);
    }

    public function errorcatch()
    {
        $this->view('ErrorCatch',['title'=>'Erreur sur le site']);
    }

    public function dashboard()
    {
        $contentDashboard='';
        if($this->_userConnect->canRedactor() )
        {
            $blogController=new BlogController($this->_userConnect,$this->_request);
            $contentDashboard.=$blogController->getWidgetDashboard ();
        }
        if($this->_userConnect->canAdministrator() ) {
            $userController=new UserController($this->_userConnect,$this->_request );
            $contentDashboard.=$userController->getWidgetDashboard ();
        }

        $this->view('DashboardView',
        ['title'=>'Tableau de bord',
        'contentDashboard'=>$contentDashboard]);
    }

}
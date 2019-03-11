<?php

namespace ES\App\Modules\Blog\Controller;


use ES\App\Modules\Blog\Model\ArticleManager;
use ES\App\Modules\Blog\Model\ArticleFactory;



use ES\App\Modules\Blog\Controller\CommentController;
use ES\App\Modules\Blog\Controller\ArticleController;

use \ES\Core\Controller\AbstractController;
use ES\App\Modules\User\Model\UserConnect;
use \ES\Core\Toolbox\Request;

class BlogController extends AbstractController
{
    static $module='Blog';
    private $_commentController;
    private $_articleController;


    public function __construct(UserConnect $userConnect, Request $request)
    {
        parent::__construct($userConnect,$request);
        $this->_articleController=new ArticleController($userConnect,$request);
        $this->_commentController=new CommentController($userConnect,$request);
    }
    public function getWidgetDashboard():string
    {
        $retour='';
        if($this->_userConnect->canRedactor()  ) {
            $retour.= $this->_articleController->getWidgetDashboard ();
        }

        if($this->_userConnect->canModerator()  ) {
            $retour.= $this->_commentController->getWidgetDashboard ();
        }

        return $retour;
    }

}

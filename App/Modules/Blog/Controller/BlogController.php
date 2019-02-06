<?php

namespace ES\App\Modules\Blog\Controller;


use ES\App\Modules\Blog\Model\ArticleManager;
use ES\App\Modules\Blog\Model\ArticleFactory;



use ES\App\Modules\Blog\Controller\CommentController;
use ES\App\Modules\Blog\Controller\ArticleController;
use ES\App\Modules\Blog\Form\CommentModifyStatusForm;


use ES\App\Modules\Blog\Form\CommentAddForm;
use ES\App\Modules\Blog\Model\CommentManager;

use \ES\Core\Controller\AbstractController;
use ES\App\Modules\User\Model\UserConnect;
use \ES\Core\Toolbox\Request;


/**
 * BlogController short summary.
 *
 * BlogController description.
 *
 * @version 1.0
 * @author ragus
 */
class BlogController extends AbstractController
{
    static $module='Blog';
    private $_articleManager;
    private $_categoryManager;
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


    #region AJAX


    #endregion

    #region COMMENT

    #endregion




    private function  blogView($view,$title,$form,$user,$exit)
    {
        $params=['title'=>$title,'form'=>$form];
        if($user)
        {
            $params['userConnect']=$this->_userConnect->user;
        }

        $this->view($view,$params);
        if($exit){exit;}
    }
    public function commentlist()
    {
        $formcomment=new CommentModifyStatusForm($this->_request->getPost());
        $list=null;
        try
        {
            if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
                $this->AccueilView(true) ;
            }
            $list=$this->_commentManager->getCommentForModerator();
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->commentListView($list,$formcomment);
    }
    public function commentmoderate()
    {
        $formcomment=new CommentModifyStatusForm($this->_request->getPost());
        try
        {
            if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
                $this->AccueilView(true) ;
            }

            if($this->_request->hasPost())
            {
                $id=$formcomment[$formcomment::IDHIDDEN]->text;
                $value=$formcomment[$formcomment::STATUS]->text;


                if ($formcomment->check()) {
                    $this->_commentManager->changeStatusOfComment ($id,$this->_userConnect->user,$value);
                    $this->flash->writeSucces("Le commentaire est modifié.");
                }

            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->commentlist();

    }
    public function commentListView($list,$formcomment)
    {
            $params=['title'=>'Blog',
            'list'=>$list,
            'formcomment'=>$formcomment];
        $this->view('CommentListView',$params);

    }
    #region CATEGORY



    #endregion


}

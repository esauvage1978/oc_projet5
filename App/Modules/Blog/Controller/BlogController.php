<?php

namespace ES\App\Modules\Blog\Controller;


use ES\App\Modules\Blog\Model\ArticleManager;
use ES\App\Modules\Blog\Model\ArticleFactory;



use ES\App\Modules\Blog\Model\CategoryManager;
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
    private $_commentManager;


    public function __construct(UserConnect $userConnect, Request $request)
    {
        parent::__construct($userConnect,$request);
        $this->_articleManager=new ArticleManager();
        $this->_commentManager=new CommentManager();
    }
    public function getWidgetDashboard():string
    {
        $numberTotal=$this->_commentManager->countComment();
        $numberModerator=$this->_commentManager->countComment('moderator_status',0);
        $numberModerateorKO=$this->_commentManager->countComment('moderator_status',1);
        $numberModeratorOK=$this->_commentManager->countComment('moderator_status',2);
        $data=[
            'numberTotal'=>$numberTotal,
            'numberModerator'=>$numberModerator,
            'numberModerateorKO'=>$numberModerateorKO,
            'numberModeratorOK'=>$numberModeratorOK
            ];
        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboard.php';
        return $this->renderView->genererFichier($fichier, $data);
    }



    #region AJAX
    public function lastarticles()
    {
        echo json_encode( $this->_articleManager->getLastArticles());
    }

    #endregion
    #region SHOW

    #region COMMENT
    public function commentadd()
    {
        $formComment=new CommentAddForm($this->_request->getPost() );

        try
        {

            if($this->_request->hasPost()) {

                $commentContent=$formComment[$formComment::COMMENT]->text;
                $articleRef=$formComment[$formComment::IDARTICLEHIDDEN]->text;
                if ($formComment->check()) {

                    if($this->_userConnect->isConnect () ) {
                        $this->_commentManager->createComment($commentContent,$articleRef,
                            $this->_userConnect->userConnect->user->getId());
                    } else {
                        $this->_commentManager->createComment($commentContent,$articleRef);
                    }

                    $this->flash->writeSucces("Le commentaire est ajouté, il est en attente de modération.");
                    $this->_request->unsetPost($formComment[$formComment::COMMENT]->getName() );
                }

            }

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->show($formComment[$formComment::IDARTICLEHIDDEN]->text);
    }
    #endregion
    public function find()
    {
        try
        {

            if($this->_request->hasPostValue('recherche')) {
                $word=$this->_request->getPostValue('recherche');
                $list= $this->_articleManager->getArticles('find',$word);
            } else {
                $list=$this->_articleManager->getArticles();
            }

            $this->listView($list,null,true);

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }
    #region LIST
    public function list($filtre=null,$number=null)
    {
        $filtre;
        try
        {
            $list=null;
            if(isset($filtre) && isset($number)) {
                $filtrePermis=['category','user','tags','word'];
                if( in_array ($filtre,$filtrePermis)) {
                    $list=$this->_articleManager->getArticles($filtre,$number);
                    $filtre=true;
                } else {
                    $list=$this->_articleManager->getArticles();
                }
            } else {
                $list=$this->_articleManager->getArticles();
            }

            $this->listView($list,$filtre,true);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }
    private function listView($list,$filtre=null,$exit=false)
    {
        $params=['title'=>'Blog',
                 'list'=>$list];

        if(isset($filtre)) {
            $params['filtre']=$filtre;
        }

        $this->view('listView',$params);
        if($exit){exit;}
    }

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

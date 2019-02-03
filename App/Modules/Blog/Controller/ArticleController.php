<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\Blog\Model\ArticleManager;
use ES\App\Modules\Blog\Model\CategoryManager;
use ES\App\Modules\Blog\Form\ArticleAddForm;
use \ES\Core\Controller\AbstractController;
use ES\App\Modules\User\Model\UserConnect;
use \ES\Core\Toolbox\Request;

/**
 * ArticleController short summary.
 *
 * ArticleController description.
 *
 * @version 1.0
 * @author ragus
 */
class ArticleController extends AbstractController
{
    static $module='Blog';

    private $_articleManager;
    private $_categoryManager;

    public function __construct(UserConnect $userConnect, Request $request)
    {
        parent::__construct($userConnect,$request);
        $this->_articleManager =new ArticleManager();
        $this->_categoryManager =new CategoryManager();
    }

    public function show($id=null)
    {
        $formComment=new CommentAddForm($this->_request->getPost());
        $articleFactory=null;
        if(!empty($id)) {
            $formComment[$formComment::IDARTICLEHIDDEN]->text =$id;
            $articleFactory=$this->_articleManager->findById($id);
            $articleFactory->comments=$this->_commentManager->getCommentsValid($id);
            $articleFactory->commentNbr=\count($articleFactory->comments);
        }
        $this->showView($articleFactory,$formComment);

    }

    public function showView($articleFactory, $formComment=null)
    {

        //$list=$this->_categoryManager->getCategorys();
        $params=['title'=>$articleFactory->article->getTitle(),
            'articleFactory'=>$articleFactory,
            'formComment'=>$formComment
        ];
        $this->view('ShowView',$params);

    }

    public function add()
    {
        $form =new ArticleAddForm($this->_request->getPost());

        //initialisation de la liste des catégories
        $form[$form::CATEGORY]->liste=$this->_categoryManager->getCategorysForSelect(true);  

        try
        {
            if($this->_request->hasPost()) {
                //Contrôle de la saisie des données de l'utilisateur
                if (!$form->check()) {
                    $this->addView($form,true);
                }


                //initialisation de la class UserTable
                $article=$this->_articleManager->createArticle(
                    $form->text($form::TITLE),
                    $form->text($form::CATEGORY),
                    $form->text($form::CHAPO),
                    $form->text($form::CONTENT),
                    $this->_userConnect->user->getId()
                    );



                if($article->hasId())  {

                    $this->flash->writeSucces ("Article créé en tant que brouillon") ;

                    //Retour page d'accueil
                    $this->modify($article->getId());

                } else {
                    $this->flash->writeError ('Erreur lors de la création.');
                    $this->addView($form);
                }
            } else {
                $this->addView($form);
            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView( $e->getMessage(),true);
        }
    }
    private function addView($form,$exit=false)
    {


        $this->view('ArticleAddView',[
            'title'=>'Création d\'un article',
            'form'=>$form]);
        if($exit) {exit;}
    }


}
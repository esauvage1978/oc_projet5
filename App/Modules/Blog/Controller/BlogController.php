<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\User\Model\UserConnect;
use ES\App\Modules\Blog\Model\ArticleManager;
use ES\App\Modules\Blog\Form\CategoryAddForm;
use ES\App\Modules\Blog\Form\CategoryModifyForm;
use ES\App\Modules\Blog\Model\CategoryManager;

use ES\App\Modules\Shared\Controller\restrictControler;
use \ES\Core\Controller\AbstractController;
Use ES\Core\Toolbox\Auth;

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
    private $_userConnect=null;
    private $_userConnected;

    use restrictControler;

    public function __construct()
    {
        parent::__construct ();
        $this->_articleManager=new ArticleManager();
        $this->_categoryManager=new CategoryManager();
        $this->_userConnect=new UserConnect($this->_request);
        if($this->_userConnect->isConnect () ) {
            $this->_userConnected=$this->_userConnect->getUserConnect();
        }

    }



    #region LIST
    public function list($filtre=null,$number=null)
    {

        try
        {
            $list=null;
            if(isset($filtre) && isset($number)) {
                $filtreOK=['category','tags','word'];
                if( in_array ($filtre,$filtreOK)) {
                    $list=$this->_articleManager->getArticles($filtre,$number);
                } else {
                    $list=$this->_articleManager->getArticles();
                }
            } else {
                $list=$this->_articleManager->getArticles();
            }

            $this->listView($list,true);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }
    private function listView($list,$exit=false)
    {
        $this->blogView('ListView','Blog de My LOST UNIVER',
            $list,
            false,
            $exit);
    }

    #endregion

    #region SHOW
    public function show($id=null)
    {
        $this->showView(null,true);
    }
    private function showView($list,$exit=false)
    {
        $this->blogView('ShowView','Article',
            $list,
            false,
            $exit);
    }
    #endregion
    private function  blogView($view,$title,$form,$user,$exit)
    {
        $params=['title'=>$title,'form'=>$form];
        if($user)
        {
            $params['userConnect']=$this->_userConnected;
        }

        $this->view($view,$params);
        if($exit){exit;}
    }

    public function categorylist()
    {
        $formAdd=new CategoryAddForm($this->_request->getPost());
        $formModify=new CategoryModifyForm($this->_request->getPost());
        $this->categoryListView($formAdd,$formModify);
    }
    public function categorymodify()
    {
        $formAdd=new CategoryAddForm($this->_request->getPost() );
        $formModify=new CategoryModifyForm($this->_request->getPost());
        try
        {
            if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
                $this->AccueilView(true) ;
            }

            if($this->_request->hasPost()) {

                $title=$formModify->controls[$formModify::CATEGORY]->text;
                $idHidden=$formModify->controls[$formModify::IDHIDDEN]->text;

                if ($formModify->check() &&
                    !$this->_categoryManager->categoryExist($title,$idHidden)) {

                    //récupération de l'userTable
                    $category=$this->_categoryManager->findById ($idHidden);
                    $category->setTitle ($title);
                    $this->_categoryManager->updateCategory($category);
                    $this->flash->writeSucces("La catégorie est modifiée");

                    //Mise à blanc des formulaires
                    $formModify->controls[$formModify::CATEGORY]->text=null;
                    $formModify->controls[$formModify::IDHIDDEN]->text=null;


                } else if (!$formModify->check()){
                    $this->flash->writeError("Les informations sont incorrectes.");
                } else {
                    $this->flash->writeWarning("Cette catégorie existe déjà");
                }

            }

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->categoryListView($formAdd,$formModify);

    }
    public function categorydelete($id)
    {
        $formAdd=new CategoryAddForm($this->_request->getPost() );
        $formModify=new CategoryModifyForm($this->_request->getPost());

        if(isset($id) && is_int($id) && !$this->_categoryManager->hasArticle($id) ) {
            $this->_categoryManager->deleteCategory($id);
            $this->flash->writeSucces("La catégorie est supprimée.");

        } else {
            $this->flash->writeInfo("La suppression est impossible.");
        }

        $this->categoryListView($formAdd,$formModify);
    }
    public function categoryadd()
    {
        $formAdd=new CategoryAddForm($this->_request->getPost() );
        $formModify=new CategoryModifyForm($this->_request->getPost());
        try
        {
            if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
                $this->AccueilView(true) ;
            }

            if($this->_request->hasPost()) {

                $category=$formAdd->controls[$formAdd::CATEGORY]->text;

                if ($formAdd->check() &&
                    !$this->_categoryManager->categoryExist($category)) {

                    $this->_categoryManager->createCategory($category);
                    $this->flash->writeSucces("La catégorie est ajoutée");
                    $formAdd->controls[$formAdd::CATEGORY]->text=null;


                } else {
                    $this->flash->writeWarning("Cette catégorie existe déjà");
                }

            }

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->categoryListView($formAdd,$formModify);

    }
    public function categoryListView($formAdd, $formModify)
    {
        $list=$this->_categoryManager->getCategorys();
        $params=['title'=>'Blog',
            'list'=>$list,
            'formAdd'=>$formAdd,
            'formModify'=>$formModify];
        $this->view('CategoryCRUD',$params);
    }
}

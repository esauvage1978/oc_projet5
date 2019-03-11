<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\Blog\Model\ArticleManager;
use ES\App\Modules\Blog\Model\CategoryManager;
use ES\App\Modules\Blog\Form\CommentAddForm;
use ES\App\Modules\Blog\Form\ArticleAddForm;
use ES\App\Modules\Blog\Form\ArticleModifyForm;
use ES\App\Modules\Blog\Form\ArticleModifyStateForm;
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


    public function getWidgetDashboard():string
    {
        $articles=[];

        $articles[0]=[
            ES_DASHBOARD_TITRE=>'Total',
            ES_DASHBOARD_ICONE=>'ion-folder',
            ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles(),
            ES_DASHBOARD_CONTENT=>'Nombre total d\'article',
            ES_DASHBOARD_LINK=>'blog/article/listadmin#articlelistadmintop'
        ];


        $articles[1]=[
           ES_DASHBOARD_TITRE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_ACTIF] ,
           ES_DASHBOARD_ICONE=>'ion-thumbsup',
           ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles('state',ES_BLOG_ARTICLE_STATE_ACTIF),
           ES_DASHBOARD_CONTENT=>'Article publié',
           ES_DASHBOARD_LINK=>'blog/article/listadmin/state/' .ES_BLOG_ARTICLE_STATE_ACTIF. '#articlelistadmintop'

       ];

        $nombre=$this->_articleManager->countArticles('state',ES_BLOG_ARTICLE_STATE_BROUILLON);
        $articles[2]=[
           ES_DASHBOARD_TITRE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_BROUILLON] ,
           ES_DASHBOARD_ICONE=>'ion-compose',
           ES_DASHBOARD_NUMBER=>$nombre,
           ES_DASHBOARD_CONTENT=>'Article en cours de rédaction',
           ES_DASHBOARD_LINK=>'blog/article/listadmin/state/' .ES_BLOG_ARTICLE_STATE_BROUILLON. '#articlelistadmintop',
           ES_DASHBOARD_COLOR=>($nombre?'list-group-item-warning':'')
       ];

        $articles[3]=[
           ES_DASHBOARD_TITRE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_ARCHIVE] ,
           ES_DASHBOARD_ICONE=>'ion-archive',
           ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles('state',ES_BLOG_ARTICLE_STATE_ARCHIVE),
           ES_DASHBOARD_CONTENT=>'Article archivé',
           ES_DASHBOARD_LINK=>'blog/article/listadmin/state/' .ES_BLOG_ARTICLE_STATE_ARCHIVE. '#articlelistadmintop'
       ];

        $articles[4]=[
           ES_DASHBOARD_TITRE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_CORBEILLE] ,
           ES_DASHBOARD_ICONE=>'ion-trash-b',
           ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles('state',ES_BLOG_ARTICLE_STATE_CORBEILLE),
           ES_DASHBOARD_CONTENT=>'Article supprimé',
           ES_DASHBOARD_LINK=>'blog/article/listadmin/state/' .ES_BLOG_ARTICLE_STATE_CORBEILLE. '#articlelistadmintop'
       ];
        $data=[
            'articles'=>$articles
            ];
        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboardArticles.php';
        return $this->renderView->genererFichier($fichier, $data);
    }

    public function show($id)
    {
        $formComment=new CommentAddForm($this->_request->getPost());
        $articleComposer=null;
        if(!empty($id)) {
            $formComment[$formComment::IDARTICLEHIDDEN]->setText($id);

            $articleComposer=$this->_articleManager->findById($id);
            $articleComposer->initComment();
        }
        $this->showView($articleComposer,$formComment);

    }

    public function showView($articleComposer, $formComment=null)
    {

        $params=['title'=>$articleComposer->article->getTitle(),
            'articleComposer'=>$articleComposer,
            'formComment'=>$formComment
        ];
        $this->view('ShowView',$params);

    }
    public function last($number)
    {
        header('Content-Type: application/json');
        echo json_encode( $this->_articleManager->getLastArticles($number));
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
                    $form[$form::TITLE]->getText(),
                    $form[$form::CATEGORY]->getText(),
                    $form[$form::CHAPO]->getText(),
                    $form[$form::CONTENT]->getText(),
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
    public function modify($id)
    {

        $formModify = new ArticleModifyForm($this->_request->getPost());

        $formModifyState = new ArticleModifyStateForm($this->_request->getPost());
        $formModifyState[$formModifyState::CATEGORY]->liste=$this->_categoryManager->getCategorysForSelect(true);

        $articleComposer=$this->_articleManager->findById($id);
        try
        {
            if($this->_request->hasPost()) {

                $retour=$this->_articleManager->createPicture(
                   $formModifyState[$formModifyState::FILE]->getName() ,$id) ;
                if(!empty($retour)) {
                    $this->flash->writeError ($retour);
                }
                //Contrôle de la saisie des données de l'utilisateur
                if (!$formModify->check()) {
                    $this->modifyView($articleComposer,$formModify,$formModifyState,true);
                }

                $articleComposer->article->setTitle($formModify[$formModify::TITLE]->text);
                $articleComposer->article->setChapo($formModify[$formModify::CHAPO]->text);
                $articleComposer->article->setContent($formModify[$formModify::CONTENT]->text);
                $articleComposer->article->setCategoryRef($formModifyState[$formModifyState::CATEGORY]->text);
                if($articleComposer->article->getState() != $formModifyState[$formModifyState::STATE]->text) {
                    $articleComposer->article->setState($formModifyState[$formModifyState::STATE]->text);
                    $articleComposer->article->setModifyDate(\date(ES_NOW)) ;
                }

                $this->_articleManager->modifyArticle($articleComposer->article,$this->_userConnect->user->getId());

                $this->flash->writeSucces ('Modification effectuée');

            } else {
                $formModify[$formModify::ID_HIDDEN]->setText($id);
                $formModify[$formModify::TITLE]->setText($articleComposer->article->getTitle());
                $formModify[$formModify::CHAPO]->setText($articleComposer->article->getChapo());
                $formModify[$formModify::CONTENT]->setText($articleComposer->article->getContent());
                $formModifyState[$formModifyState::CATEGORY]->setText($articleComposer->article->getCategoryRef());
                $formModifyState[$formModifyState::STATE]->setText($articleComposer->article->getState());
            }
            $this->modifyView($articleComposer,$formModify,$formModifyState ,true);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView( $e->getMessage(),true);
        }
    }
    private function modifyView($articleComposer,$formModify,$formModifyState,$exit=false)
    {


        $this->view('ArticleModifyView',[
            'title'=>'Modification d\'un article',
            'articleComposer'=>$articleComposer,
            'formModifyState'=>$formModifyState,
            'formModify'=>$formModify]);
        if($exit) {exit;}
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
            $this->listView($list,true,true);
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
            $params['filtre']=true;
        }
        if(isset($list)) {
            $params['message']='Il n\'y a aucun article pour votre recherche';
        }


        if($this->_userConnect->canRedactor() ) {
            $params['redacteur']=true;
        }


        $this->view('ListView',$params);
        if($exit){exit;}
    }

    #endregion

    public function listadmin($filtre=null,$value=null)
    {
        $list=null;
        try
        {
            $list=$this->_articleManager->getArticles($filtre,$value,false);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e,true);
        }
        if(isset($filtre)) {
            $this->listAdminView($list,true );
        } else {
            $this->listAdminView($list );
        }

    }
    public function listAdminView($list,$filtre=false)
    {
        $params=['title'=>'Blog',
       'list'=>$list];

        if(isset($filtre)) {
            $params['filtre']=$filtre;
        }
        $this->view('ArticleListAdminView',$params);

    }

    public function changestatut()
    {
        if($this->_articleManager->changeStatut(
            $this->_request->getPostValue('id'),
            $this->_request->getPostValue('value'),
            $this->_userConnect->user)) {
            echo 'Statut changé.';
        } else {
            echo 'Erreur recontrée.';
        }

    }

}
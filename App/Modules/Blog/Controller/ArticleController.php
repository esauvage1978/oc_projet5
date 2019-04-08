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

    const TITLE='title';

    public function __construct(UserConnect $userConnect, Request $request,$flash, $renderView)
    {
        parent::__construct($userConnect,$request,$flash, $renderView);
        $this->_articleManager =new ArticleManager();
        $this->_categoryManager =new CategoryManager();
    }


    public function getWidgetDashboard() : string
    {
        $articles=[];

        $urlLisAdmin='blog/article/listadmin';
        $urlLisAdminState=$urlLisAdmin . '/state/';
        $filtre='state';
        $anchor='#articlelistadmintop';

        $articles[0]=[
            ES_DASHBOARD_TITLE=>'Total',
            ES_DASHBOARD_ICONE=>'ion-folder',
            ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles(),
            ES_DASHBOARD_CONTENT=>'Nombre total d\'article',
            ES_DASHBOARD_LINK=>$urlLisAdmin .$anchor
        ];


        $articles[1]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_ACTIF] ,
           ES_DASHBOARD_ICONE=>'ion-thumbsup',
           ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles($filtre,ES_BLOG_ARTICLE_STATE_ACTIF),
           ES_DASHBOARD_CONTENT=>'Article publié',
           ES_DASHBOARD_LINK=>$urlLisAdminState. ES_BLOG_ARTICLE_STATE_ACTIF. '#'

       ];

        $nombre=$this->_articleManager->countArticles($filtre,ES_BLOG_ARTICLE_STATE_BROUILLON);
        $articles[2]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_BROUILLON] ,
           ES_DASHBOARD_ICONE=>'ion-compose',
           ES_DASHBOARD_NUMBER=>$nombre,
           ES_DASHBOARD_CONTENT=>'Article en cours de rédaction',
           ES_DASHBOARD_LINK=>$urlLisAdminState .ES_BLOG_ARTICLE_STATE_BROUILLON. $anchor,
           ES_DASHBOARD_COLOR=>($nombre?'list-group-item-warning':'')
       ];

        $articles[3]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_ARCHIVE] ,
           ES_DASHBOARD_ICONE=>'ion-archive',
           ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles($filtre,ES_BLOG_ARTICLE_STATE_ARCHIVE),
           ES_DASHBOARD_CONTENT=>'Article archivé',
           ES_DASHBOARD_LINK=>$urlLisAdminState .ES_BLOG_ARTICLE_STATE_ARCHIVE. $anchor
       ];

        $articles[4]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_CORBEILLE] ,
           ES_DASHBOARD_ICONE=>'ion-trash-b',
           ES_DASHBOARD_NUMBER=>$this->_articleManager->countArticles($filtre,ES_BLOG_ARTICLE_STATE_CORBEILLE),
           ES_DASHBOARD_CONTENT=>'Article supprimé',
           ES_DASHBOARD_LINK=>$urlLisAdminState .ES_BLOG_ARTICLE_STATE_CORBEILLE. $anchor
       ];
        $data=[
            'articles'=>$articles
            ];
        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboardArticles.php';
        return $this->renderView->genererFichier($fichier, $data);
    }

    public function show(int $id)
    {
        $formComment=new CommentAddForm($this->request->getPost());
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

        $params=[self::TITLE =>$articleComposer->article->getTitle(),
            'articleComposer'=>$articleComposer,
            'formComment'=>$formComment
        ];
        $this->view('ShowView',$params);

    }
    public function last(int $number)
    {
        header('Content-Type: application/json');
        echo json_encode( $this->_articleManager->getLastArticles($number));
    }

    public function add()
    {
        $form =new ArticleAddForm($this->request->getPost());

        //initialisation de la liste des catégories
        $form[$form::CATEGORY]->liste=$this->_categoryManager->getCategorysForSelect(true);

        try
        {
            if($this->request->hasPost()) {
                //Contrôle de la saisie des données de l'utilisateur
                if ($form->check()) {

                    //initialisation de la class UserTable
                    $article=$this->_articleManager->createArticle(
                        $form[$form::TITLE]->getText(),
                        $form[$form::CATEGORY]->getText(),
                        $form[$form::CHAPO]->getText(),
                        $form[$form::CONTENT]->getText(),
                        $this->userConnect->user->getId()
                        );

                    if($article->hasId())  {

                        $this->flash->writeSucces ("Article créé en tant que brouillon") ;

                        header('location: ' .ES_ROOT_PATH_WEB . 'blog/article/modify/' . $article->getId()  );
                        exit;


                    } else {
                        $this->flash->writeError ('Erreur lors de la création.');
                        $this->addView($form);
                    }
                } else {

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
    private function addView($form)
    {
        $this->view('ArticleAddView',[
            self::TITLE =>'Création d\'un article',
            'form'=>$form]);
    }
    public function modify(int $id)
    {

        $formModify = new ArticleModifyForm($this->request->getPost());

        $formModifyState = new ArticleModifyStateForm($this->request->getPost());
        $formModifyState[$formModifyState::CATEGORY]->liste=$this->_categoryManager->getCategorysForSelect(true);

        $articleComposer=$this->_articleManager->findById($id);





        try
        {
            if($this->request->hasPost()) {

                $retour=$this->_articleManager->createPicture(
                   $formModifyState[$formModifyState::FILE]->getName() ,$id) ;
                if(!empty($retour)) {
                    $this->flash->writeError ($retour);
                }
                //Contrôle de la saisie des données de l'utilisateur
                if ($formModify->check()) {

                    $articleComposer->article->setTitle($formModify[$formModify::TITLE]->getText());
                    $articleComposer->article->setChapo($formModify[$formModify::CHAPO]->getText());
                    $articleComposer->article->setContent($formModify[$formModify::CONTENT]->getText());
                    $articleComposer->article->setCategoryRef($formModifyState[$formModifyState::CATEGORY]->getText());
                    if($articleComposer->article->getState() != $formModifyState[$formModifyState::STATE]->getText()) {
                        $articleComposer->article->setState($formModifyState[$formModifyState::STATE]->getText());
                        $articleComposer->article->setModifyDate(\date(ES_NOW)) ;
                    }

                    $this->_articleManager->modifyArticle($articleComposer->article,$this->userConnect->user->getId());

                    $this->flash->writeSucces ('Modification effectuée');
                }

            } else {
                $formModify[$formModify::ID_HIDDEN]->setText($id);
                $formModify[$formModify::TITLE]->setText($articleComposer->article->getTitle());
                $formModify[$formModify::CHAPO]->setText($articleComposer->article->getChapo());
                $formModify[$formModify::CONTENT]->defaultControl=$formModify[$formModify::CONTENT]::CONTROLE_NOTHING;
                $formModify[$formModify::CONTENT]->setText($articleComposer->article->getContent());
                $formModifyState[$formModifyState::CATEGORY]->setText($articleComposer->article->getCategoryRef());
                $formModifyState[$formModifyState::STATE]->setText($articleComposer->article->getState());
            }
            $this->modifyView($articleComposer,$formModify,$formModifyState );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView( $e->getMessage(),true);
        }
    }
    private function modifyView($articleComposer,$formModify,$formModifyState)
    {


        $this->view('ArticleModifyView',[
            self::TITLE =>'Modification d\'un article',
            'articleComposer'=>$articleComposer,
            'formModifyState'=>$formModifyState,
            'formModify'=>$formModify]);
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

            $this->listView($list,$filtre);
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
            if($this->request->hasPostValue('recherche')) {
                $word=$this->request->getPostValue('recherche');
                $list= $this->_articleManager->getArticles('find',$word);
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

    private function listView($list,$filtre=null)
    {
        $params=[self::TITLE =>'Blog',
                 'list'=>$list];

        if(isset($filtre)) {
            $params['filtre']=true;
        }
        if(isset($list)) {
            $params['message']='Il n\'y a aucun article pour votre recherche';
        }


        if($this->userConnect->canRedactor() ) {
            $params['redacteur']=true;
        }


        $this->view('ListView',$params);
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
    private function listAdminView($list,$filtre=false)
    {
        $params=[self::TITLE =>'Blog',
       'list'=>$list];

        if(isset($filtre)) {
            $params['filtre']=$filtre;
        }
        $this->view('ArticleListAdminView',$params);

    }

    public function changestatut()
    {
        header('Content-Type: application/json');
        if($this->_articleManager->changeStatut(
            $this->request->getPostValue('id',Request::TYPE_INT),
            $this->request->getPostValue('value',Request::TYPE_INT),
            $this->userConnect->user->getId())) {
            echo json_encode(['message'=>'Statut changé.']);
        } else {
            echo  json_encode(['message'=>'Erreur recontrée.']);
        }

    }

}
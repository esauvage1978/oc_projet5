<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\Blog\Model\CommentManager;
use ES\App\Modules\Blog\Model\CommentComposer;
use ES\App\Modules\Blog\Form\CommentAddForm;
use ES\App\Modules\Blog\Form\CommentModifyStatusForm;
use \ES\Core\Controller\AbstractController;
use ES\App\Modules\User\Model\UserConnect;
use \ES\Core\Toolbox\Request;


/**
 * CategoryController short summary.
 *
 * CategoryController description.
 *
 * @version 1.0
 * @author ragus
 */
class CommentController extends AbstractController
{
    static $module='Blog';

    private $_commentManager;

    public function __construct(UserConnect $userConnect, Request $request)
    {
        parent::__construct($userConnect,$request);
        $this->_commentManager =new CommentManager();
    }

    public function getWidgetDashboard():string
    {
        $commentaires=[];

        $commentaires[0]=[
            ES_DASHBOARD_TITLE=>'Total',
            ES_DASHBOARD_ICONE=>'ion-chatbubbles',
            ES_DASHBOARD_NUMBER=>$this->_commentManager->countComment(),
            ES_DASHBOARD_CONTENT=>'Nombre total de commentaire pour les articles publiés',
            ES_DASHBOARD_LINK=>'blog/comment/listadmin'
        ];

        $nombre=$this->_commentManager->countComment('moderator_state',ES_BLOG_COMMENT_STATE_WAIT);
        $commentaires[1]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_COMMENT_STATE [ ES_BLOG_COMMENT_STATE_WAIT],
           ES_DASHBOARD_ICONE=>'ion-eye',
           ES_DASHBOARD_NUMBER=>$nombre,
           ES_DASHBOARD_CONTENT=>'Commentaire à moderer',
           ES_DASHBOARD_LINK=>'blog/comment/listadmin/'.ES_BLOG_COMMENT_STATE_WAIT,
           ES_DASHBOARD_COLOR=>($nombre? 'list-group-item-info':'')
       ];

        $commentaires[2]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_COMMENT_STATE [ ES_BLOG_COMMENT_STATE_REJECT],
           ES_DASHBOARD_ICONE=>'ion-thumbsdown',
           ES_DASHBOARD_NUMBER=>$this->_commentManager->countComment('moderator_state',ES_BLOG_COMMENT_STATE_REJECT),
           ES_DASHBOARD_CONTENT=>'Commentaire rejeté par le gestionnaire',
           ES_DASHBOARD_LINK=>'blog/comment/listadmin/' . ES_BLOG_COMMENT_STATE_REJECT
       ];

        $commentaires[3]=[
           ES_DASHBOARD_TITLE=>ES_BLOG_COMMENT_STATE [ ES_BLOG_COMMENT_STATE_APPROVE],
           ES_DASHBOARD_ICONE=>'ion-thumbsup',
           ES_DASHBOARD_NUMBER=>$this->_commentManager->countComment('moderator_state',ES_BLOG_COMMENT_STATE_APPROVE),
           ES_DASHBOARD_CONTENT=>'Commentaire approuvé et publié',
           ES_DASHBOARD_LINK=>'blog/comment/listadmin/' . ES_BLOG_COMMENT_STATE_APPROVE
       ];
        $data=[
            'commentaires'=>$commentaires
            ];
        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboardComment.php';
        return $this->renderView->genererFichier($fichier, $data);
    }

    /**
     * Fonction ajax appelée sur la page article.show
     */
    public function add()
    {
        //récupération des données envoyées par ajax
        $id=$this->_request->getPostValue('id',Request::TYPE_INT);
        $value=$this->_request->getPostValue('value',Request::TYPE_HTMLENTITY);
        $token=$this->_request->getPostValue('token',Request::TYPE_HTMLENTITY );
        if(!empty($token)) {
            $form=new CommentAddForm();
            $form[$form::TOKEN]->setText($token);
            if(!$form[$form::TOKEN]->check()) {
                echo 'Le formulaire est périmé';
            }
            elseif(!empty ( $value)) {
                if($this->_userConnect->isConnect() ) {
                    $this->_commentManager->createComment($value,$id,
                        $this->_userConnect->user->getId());
                } else {
                    $this->_commentManager->createComment($value,$id);
                }
                header('Content-Type: application/json');
                echo json_encode([ES_TOKEN=>$form[$form::TOKEN]->getText(),
                    'message'=> \date(ES_DATE_FR) . ' : Le commentaire est créé, il est en attente de modération.']);
            }
        }
    }

    public function Listadmin($value=null)
    {
        $formcomment=new CommentModifyStatusForm($this->_request->getPost());
        $list=null;
        try
        {
            $list=$this->_commentManager->getCommentsForModerator($value);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->ListAdminView($list,$formcomment);
    }
    public function changemoderatorstate()
    {
        $id=$this->_request->getPostValue('id');
        $value=$this->_request->getPostValue('value');
        $retour=$this->_commentManager->changeStatusOfComment($id,$this->_userConnect->user,$value);
        echo 'Statut changé.';
    }

    public function ListAdminView($list,$formcomment)
    {
        $params=['title'=>'Blog',
        'list'=>$list,
        'formcomment'=>$formcomment];
        $this->view('CommentListAdminView',$params);

    }
}

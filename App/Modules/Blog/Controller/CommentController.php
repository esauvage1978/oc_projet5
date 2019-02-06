<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\Blog\Model\CommentManager;
use ES\App\Modules\Blog\Form\CommentAddForm;
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
            'title'=>'Total',
            'icone'=>'ion-chatbubbles',
            'number'=>$this->_commentManager->countComment(),
            'content'=>'Nombre total de commentaire pour les articles publiés',
            'link'=>'blog.comment.list/all'
        ];

        $nombre=$this->_commentManager->countComment('moderator_state',ES_BLOG_COMMENT_STATE_WAIT);
        $commentaires[1]=[
           'title'=>ES_BLOG_COMMENT_STATE [ ES_BLOG_COMMENT_STATE_WAIT],
           'icone'=>'ion-eye',
           'number'=>$nombre,
           'content'=>'Commentaire à moderer',
           'link'=>'blog.comment.moderate',
           'color'=>($nombre? 'list-group-item-info':'')
       ];

        $commentaires[2]=[
           'title'=>ES_BLOG_COMMENT_STATE [ ES_BLOG_COMMENT_STATE_REJECT],
           'icone'=>'ion-thumbsdown',
           'number'=>$this->_commentManager->countComment('moderator_state',ES_BLOG_COMMENT_STATE_REJECT),
           'content'=>'Commentaire rejeté par le gestionnaire',
           'link'=>'blog.comment.moderate'
       ];

        $commentaires[3]=[
           'title'=>ES_BLOG_COMMENT_STATE [ ES_BLOG_COMMENT_STATE_APPROVE],
           'icone'=>'ion-thumbsup',
           'number'=>$this->_commentManager->countComment('moderator_state',ES_BLOG_COMMENT_STATE_APPROVE),
           'content'=>'Commentaire approuvé et publié',
           'link'=>'blog.comment.moderate'
       ];
        $data=[
            'commentaires'=>$commentaires
            ];
        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboardComment.php';
        return $this->renderView->genererFichier($fichier, $data);
    }
    public function add()
    {
        $id=$this->_request->getPostValue('id');
        $comment=$this->_request->getPostValue('value');

        if(!empty ( $comment)) {
            if($this->_userConnect->isConnect () ) {
                $this->_commentManager->createComment($comment,$id,
                    $this->_userConnect->userConnect->user->getId());
            } else {
                $this->_commentManager->createComment($comment,$id);
            }
        }
    }
}

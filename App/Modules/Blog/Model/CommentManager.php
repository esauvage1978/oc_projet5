<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;

/**
 * CommentManager short summary.
 *
 * CommentManager description.
 *
 * @version 1.0
 * @author ragus
 */
class CommentManager extends AbstractManager
{
    protected static $table='ocp5_blog_comment';
    protected static $order_by= CommentTable::CREATE_DATE. ' DESC; ';
    protected static $id=CommentTable::ID;
    protected static $classTable='ES\App\Modules\Blog\Model\CommentFactory';


    public function changeStatusOfComment($id,$user,$status)
    {
        $comment=$this->findById($id);
        $comment->comment->setModeratorDate(date(ES_NOW));
        $comment->comment->setModeratorStatus($status);
        $comment->comment->setModeratorUserRef($user->getId());
        return $this->update($comment->comment->getId(),$comment->comment->ToArray());
    }
    public function findById($key) :CommentFactory
    {
        return $this->findByField(CommentTable::ID,$key);
    }
    public function createComment($content,$articleRef,$userRef=null):CommentTable
    {
        $comment= $this->newComment($content,$articleRef,$userRef);
        $retour= $this->create($comment->ToArray());

        if(!$retour) {
            throw new \InvalidArgumentException('Erreur lors de la création du commentaire');
        }
        $comment->setId($retour);

        return $comment;
    }
    protected function newComment($content,$articleRef,$userRef=null):CommentTable
    {
        $comment = new CommentTable([]);
        $comment->setCreateDate(date(ES_NOW));
        $comment->setContent($content);
        $comment->setModeratorStatus('0');
        $comment->setCreateUserRef($userRef);
        $comment->setArticleRef($articleRef);
        return $comment;
    }

    public function getCommentsValid($articleRef)
    {
        return $this->query('SELECT * FROM ocp5_blog_comment
                            WHERE bco_moderator_status=2 and bco_article_ref=:id
                            ORDER BY bco_create_date DESC;'
            ,['id'=>$articleRef],false,true);
    }
    public function getCommentForModerator()
    {
        return $this->query('SELECT bco_id,ba_title,u_identifiant, bco_create_date,bco_content FROM ocp5_blog_comment
                            INNER JOIN ocp5_blog_article ON ba_id=bco_article_ref
                            LEFT OUTER JOIN ocp5_user ON u_id=bco_create_user_ref
                            WHERE bco_moderator_status=0
                            ORDER BY bco_create_date DESC;'
            ,null,false,false);
    }
    #region count
    public function countComment($key=null,$value=null)
    {
        if(isset($key) && isset($value)) {
            $retour= $this->query($this->_selectCount . 'bco_' . $key .'=:value ORDER BY ' . static::$order_by . ';',['value'=>$value],true)['count(*)'];
        } else {
            $retour= $this->Count();
        }
        return $retour;
    }
    #endregion
}
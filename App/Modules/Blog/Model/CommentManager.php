<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;
use ES\Core\Database\QueryBuilder;

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
    protected static $classTable='ES\App\Modules\Blog\Model\CommentComposer';


    public function changeStatusOfComment($id,$user,$value)
    {
        $commentComposer=$this->findById($id);
        $commentComposer->comment->setModeratorDate(\date(ES_NOW));
        $commentComposer->comment->setModeratorState($value);
        $commentComposer->comment->setModeratorUserRef($user->getId());
        return $this->update($id,$commentComposer->comment->ToArray());
    }

    public function findById($key) :CommentComposer
    {
        return $this->findByField(CommentTable::ID,$key);
    }

    public function modifyComment(CommentTable $datas) :bool
    {
        return $this->update($datas->getId(),$datas->toArray()) ;
    }

    public function createComment($content,$articleRef,$userRef=null):CommentTable
    {
        $comment= $this->newComment($content,$articleRef,$userRef);
        $retour= parent::create($comment->ToArray());

        if(!$retour) {
            throw new \InvalidArgumentException('Erreur lors de la création du commentaire');
        }
        $comment->setId($retour);

        return $comment;
    }
    private function newComment($content,$articleRef,$userRef=null):CommentTable
    {
        $comment = new CommentTable([]);
        $comment->setCreateDate(date(ES_NOW));
        $comment->setContent($content);
        $comment->setModeratorState(ES_BLOG_COMMENT_STATE_WAIT);
        isset($userRef)?$comment->setCreateUserRef($userRef):'';
        $comment->setArticleRef($articleRef);
        return $comment;
    }

    public function getCommentsValid($articleRef)
    {
        $requete=new QueryBuilder();
        $requete->select ('*')
                ->from('ocp5_blog_comment')
                ->innerJoin('ocp5_blog_article ON bco_article_ref=ba_id')
                ->where('bco_moderator_state=' . ES_BLOG_COMMENT_STATE_APPROVE,
                'bco_article_ref=:id','ba_state=' . ES_BLOG_ARTICLE_STATE_ACTIF)
                ->orderBy('bco_create_date DESC');

        return $this->query($requete->render(),['id'=>$articleRef],false,true);
    }
    public function getCommentsForModerator($value)
    {
        $params=[];
        $requete=new QueryBuilder();
        $requete->select ('bco_id','ba_title','u1.u_identifiant as creator','bco_create_date',
            'bco_content','bco_moderator_state','u2.u_identifiant','bco_moderator_date')
                ->from('ocp5_blog_comment')
                ->innerJoin('ocp5_blog_article ON bco_article_ref=ba_id')
                ->outerJoin('ocp5_user u1 ON u1.u_id=bco_create_user_ref',
                'ocp5_user u2 ON u2.u_id=bco_moderator_user_ref');

        if(!empty($value )) {
            $requete->where('bco_moderator_state=:value',
                    'ba_state=' . ES_BLOG_ARTICLE_STATE_ACTIF);
            $params['value']=$value;
        } else {
            $requete->where(
                    'ba_state=' . ES_BLOG_ARTICLE_STATE_ACTIF);
        }

                $requete->orderBy('bco_create_date DESC');

           return $this->query($requete->render() ,(\count($params)?$params:null),false,false);
    }
    #region count
    public function countComment($key=null,$value=null)
    {
        $this->_queryBuilder
            ->select ('count(*)')
            ->from(self::$table);
        $params=[];

        if(isset($key) && isset($value)) {
            $this->_queryBuilder
                ->where('bco_' . $key .'=:value');
            $params['value']=$value;
        }

        return $this->query(
            $this->_queryBuilder
                ->render(),
            count($params)?$params:null,true,false)['count(*)'] ;
    }
    #endregion

    public function delete($id)
    {
        return parent::delete($id);
    }
}
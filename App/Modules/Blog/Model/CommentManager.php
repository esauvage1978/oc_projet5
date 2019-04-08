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
    public function deleteComment($id)
    {
        return $this->delete($id);
    }
    public function getCommentsValid($articleRef)
    {
        return $this->query(
            $this->_queryBuilder
                ->select ('*')
                ->from(static::$table)
                ->innerJoin('ocp5_blog_article  ON ' . CommentTable::ARTICLE_REF . '=' . ArticleTable::ID )
                ->where(CommentTable::MODERATOR_STATE . '=' . ES_BLOG_COMMENT_STATE_APPROVE,
                CommentTable::ARTICLE_REF . '=:id',ArticleTable::STATE . '=' . ES_BLOG_ARTICLE_STATE_ACTIF)
                ->orderBy(CommentTable::CREATE_DATE .' DESC')
                ->render(),
           ['id'=>$articleRef],false,true);
    }

    public function getCommentsForModerator($value=null)
    {
        $params=[];

        $this->_queryBuilder
            ->select (CommentTable::ID, ArticleTable::TITLE,'u1.u_identifiant as creator',CommentTable::CREATE_DATE ,
            CommentTable::CONTENT ,CommentTable::MODERATOR_STATE ,'u2.u_identifiant',CommentTable::MODERATOR_DATE )
                ->from(static::$table)
                ->innerJoin('ocp5_blog_article ON ' . CommentTable::ARTICLE_REF  . '=' . ArticleTable::ID)
                ->outerJoin('ocp5_user u1 ON u1.u_id=' . CommentTable::CREATE_USER_REF ,
                'ocp5_user u2 ON u2.u_id=' . CommentTable::MODERATOR_USER_REF );

        if(!empty($value )) {
            $this->_queryBuilder->where(CommentTable::MODERATOR_STATE.'=:value',
                    ArticleTable::STATE .'=' . ES_BLOG_ARTICLE_STATE_ACTIF);
            $params['value']=$value;
        } else {
            $this->_queryBuilder->where(
                    ArticleTable::STATE .'=' . ES_BLOG_ARTICLE_STATE_ACTIF);
        }

        $this->_queryBuilder->orderBy(CommentTable::CREATE_DATE . ' DESC');

        return $this->query($this->_queryBuilder->render()
            ,(\count($params)?$params:null),false,false);
    }
    #region count
    public function countComment($key=null,$value=null)
    {
        try
        {
            $this->_queryBuilder
                ->select ($this->_queryBuilder::COUNT)
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
                count($params)?$params:null,true,false)[$this->_queryBuilder::COUNT] ;
        } catch (\PDOException $ex)
        {
            throw new \InvalidArgumentException ('La requête est incorrecte.');
        }
    }
    #endregion


}
<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\Blog\Model\ArticleFactory;

/**
 * UserManager short summary.
 *
 * UserManager description.
 *
 * @version 1.0
 * @author ragus
 */
class ArticleManager extends AbstractManager
{
    protected static $table='ocp5_blog_article';
    protected static $order_by= ArticleTable::ID. ' DESC; ';
    protected static $id=ArticleTable::ID;
    protected static $classTable='ES\App\Modules\Blog\Model\ArticleFactory';

    public function getArticles($key=null,$value=null)
    {
        if($key==='validaccount' && $value===0) {
            $retour= $this->query($this->_selectAll . ' u_valid_account_date is null ORDER BY ' . static::$order_by . ';');
        } elseif($key==='user' && isset($value)) {
            $retour= $this->query($this->_selectAll . 'ba_create_user_ref=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } elseif($key==='category' && isset($value)) {
            $retour= $this->query($this->_selectAll . 'ba_category_ref=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } elseif($key==='find' && isset($value)) {
            $retour= $this->query($this->_selectAll . ' (ba_title like :title OR ba_chapo like :chapo OR ba_content like :content) ORDER BY ' . static::$order_by . ';',
                ['title'=>'%'.$value.'%',
                'chapo'=>'%'.$value.'%',
                'content'=>'%'.$value.'%']);
        } elseif(isset($key) && isset($value)) {
            $retour= $this->query($this->_selectAll . 'ba_' . $key .'=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } else {
            $retour= $this->getAll();
        }
        return $retour;
    }
    public function getLastArticles()
    {
        return $this->query('SELECT ba_id, ba_title FROM ocp5_blog_article ORDER BY ba_id limit 5;',null,false,false);
    }

    public function findById($key) :ArticleFactory
    {
        return $this->findByField(ArticleTable::ID,$key);
    }

    public function createArticle($title, $categoryRef,$chapo,$content,$userRef):ArticleTable
    {
        $article= $this->NewArticle($title,$categoryRef,$chapo,$content,$userRef);

        $retour= $this->create($article->ToArray());

        if(!$retour) {
            throw new \InvalidArgumentException('Erreur lors de la création de l\'article');
        }
        $article->setId($retour);

        return $article;
    }

    public function NewArticle($title, $categoryRef,$chapo,$content,$userRef):ArticleTable
    {
        $user = new ArticleTable([]);
        $user->setTitle($title);
        $user->setCategoryRef($categoryRef);
        $user->setChapo($chapo);
        $user->setContent($content);
        $user->setCreateUserRef($userRef);
        $user->setCreateDate(\date(ES_NOW)) ;
        $user->setState(1);
        $user->setStateDate(\date(ES_NOW)) ;
        return $user;
    }
}
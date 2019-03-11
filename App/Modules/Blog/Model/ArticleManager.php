<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;
use ES\Core\Database\QueryBuilder;
use ES\App\Modules\Blog\Model\ArticleComposer;
use ES\Core\Upload\JpgUpload;
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
    protected static $classTable='ES\App\Modules\Blog\Model\ArticleComposer';

    public function countArticles($key=null,$value=null)
    {
        $this->_queryBuilder
            ->select ('count(*)')
            ->from(self::$table);
        $params=[];

        if(isset($key) && isset($value)) {
            $this->_queryBuilder
                ->where('ba_' . $key .'=:value');
            $params['value']=$value;
        }

        return $this->query(
            $this->_queryBuilder
                ->render(),
            count($params)?$params:null,true,false)['count(*)'] ;
    }

    public function getArticles($key=null,$value=null,$actif=true)
    {

        $rqt=new QueryBuilder();
        $rqt->select('*')
            ->from($this::$table);
        $params=[];
        if($key==='validaccount' && $value===0) {

            $rqt->where('u_valid_account_date is null');

        } elseif($key==='user' && isset($value)) {

            $rqt->where('ba_create_user_ref=:value');
            $params['value']=$value;

        } elseif($key==='category' && isset($value)) {

            $rqt->where('ba_category_ref=:value');
            $params['value']=$value;

        } elseif($key==='find' && isset($value)) {

            $rqt->where('(ba_title like :title OR ba_chapo like :chapo OR ba_content like :content)');
            $params['title']='%'.$value.'%';
            $params['chapo']='%'.$value.'%';
            $params['content']='%'.$value.'%';

        } elseif(isset($key) && isset($value)) {

            $rqt->where( 'ba_' . $key .'=:value');
            $params['value']=$value;
        }
        if($actif)
            $rqt->where('ba_state=' . ES_BLOG_ARTICLE_STATE_ACTIF);

        $rqt->orderBy($this::$order_by);
        return $this->query($rqt->render(),(count($params)?$params:null),false,true);
    }
    public function getLastArticles($number)
    {
        if(!is_integer($number) ) {
            $number=3;
        } elseif ($number >100) {
            $number=3;
        }

        return $this->query(
            $this->_queryBuilder
            ->select('ba_id, ba_title')
            ->from('ocp5_blog_article')
            ->where('ba_state=' . ES_BLOG_ARTICLE_STATE_ACTIF)
            ->orderBy('ba_id')
            ->limit($number)
            ->render()
            ,null,false,false);
    }

    public function findById($key) :ArticleComposer
    {
        return $this->findByField(ArticleTable::ID,$key);
    }

    public function createArticle($title, $categoryRef,$chapo,$content,$userRef)
    {
        $article= $this->NewArticle($title,$categoryRef,$chapo,$content,$userRef);

        $retour= $this->create($article->ToArray());

        if(!$retour) {
            throw new \InvalidArgumentException('Erreur lors de la création de l\'article');
        }

        $imageSource=ES_ROOT_PATH_FAT . 'Public/images/blog/model.jpg';
        $imageDestination=ES_ROOT_PATH_FAT . 'Public/images/blog/' . $retour . '.jpg';
        \copy($imageSource,$imageDestination);

        $article->setId($retour);

        return $article;
    }

    public function modifyArticle(ArticleTable $article,$userRef) :bool
    {
        $article->setModifyUserRef($userRef);
        $article->setModifyDate(\date(ES_NOW));
        return $this->update($article->getId(),$article->toArray()) ;
    }

    public function changeStatut($id,$value,$user) :bool
    {
        $articleComposer=$this->_articleManager->findById($id);
        $articleComposer->article->setState($value);
        $articleComposer->article->setStateDate(\date(ES_NOW));
        return $this->_articleManager->modifyArticle($articleComposer->article,$user);
    }

    public function createPicture($key,$id)
    {
        $jpgUpload=new JpgUpload('blog');
        return $jpgUpload->createMiniature($key,$id);
    }

    public function NewArticle($title, $categoryRef,$chapo,$content,$userRef):ArticleTable
    {
        $article = new ArticleTable([]);
        $article->setTitle($title);
        $article->setCategoryRef($categoryRef);
        $article->setChapo($chapo);
        $article->setContent($content);
        $article->setCreateUserRef($userRef);
        $article->setCreateDate(\date(ES_NOW)) ;
        $article->setState(ES_BLOG_ARTICLE_STATE_BROUILLON);
        $article->setStateDate(\date(ES_NOW)) ;
        return $article;
    }
}

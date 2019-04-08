<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;
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
        try
        {
        $this->_queryBuilder
            ->select ($this->_queryBuilder::COUNT)
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
            count($params)?$params:null,true,false)[$this->_queryBuilder::COUNT] ;
        }
        catch (\PDOException $ex ) {
            throw new \InvalidArgumentException('Erreur lors du comptage des articles');
        }
    }

    public function getArticles($key=null,$value=null,$actif=true)
    {

        $this->_queryBuilder
            ->select('*')
            ->from($this::$table);
        $params=[];
        $valueParams='value';

        if($key==='user' && isset($value)) {

            $this->_queryBuilder->where(ArticleTable::CREATE_USER_REF . '=:'.$valueParams);
            $params[$valueParams]=$value;

        } elseif($key==='category' && isset($value)) {

            $this->_queryBuilder->where(ArticleTable::CATEGORY_REF . '=:'.$valueParams);
            $params[$valueParams]=$value;

        } elseif($key==='find' && isset($value)) {

            $this->_queryBuilder->where('('. ArticleTable::TITLE . ' like :title OR '. ArticleTable::CHAPO .' like :chapo OR '. ArticleTable::CONTENT .' like :content)');
            $params['title']='%'.$value.'%';
            $params['chapo']='%'.$value.'%';
            $params['content']='%'.$value.'%';

        } elseif(isset($key) && isset($value)) {

            $this->_queryBuilder->where( 'ba_' . $key .'=:'.$valueParams);
            $params[$valueParams]=$value;
        }

        if($actif) {
            $this->_queryBuilder->where(ArticleTable::STATE . '=' . ES_BLOG_ARTICLE_STATE_ACTIF);
        }

        return $this->query(
            $this->_queryBuilder
            ->orderBy($this::$order_by)
            ->render(),(count($params)?$params:null),false,true);
    }

    public function getLastArticles($number)
    {
        if(!is_integer($number) || $number >100) {
            $number=3;
        }

        return $this->query(
            $this->_queryBuilder
            ->select(ArticleTable::ID. ',' . ArticleTable::TITLE)
            ->from(static::$table)
            ->where(ArticleTable::STATE . '=' . ES_BLOG_ARTICLE_STATE_ACTIF)
            ->orderBy(ArticleTable::ID)
            ->limit($number)
            ->render()
            ,null,false,false);
    }

    public function findById($key) :ArticleComposer
    {
        return $this->findByField(ArticleTable::ID,$key);
    }

    public function createArticle($title, $categoryRef,$chapo,$content,$userRef) : ArticleTable
    {
        $article= $this->NewArticle($title,$categoryRef,$chapo,$content,$userRef);

        $retour= $this->create($article->ToArray());

        if(!$retour) {
            throw new \InvalidArgumentException('Erreur lors de la création de l\'article');
        }

        try{
            $imageSource=ES_ROOT_PATH_FAT_DATAS_IMG . 'blog/model.jpg';
            $imageDestination=ES_ROOT_PATH_FAT_DATAS_IMG . 'blog/' . $retour . '.jpg';
            \copy($imageSource,$imageDestination);
        } catch (\InvalidArgumentException $ex ) {
            throw new \InvalidArgumentException('Erreur lors de la création de la vignette');
        }

        $article->setId($retour);

        return $article;
    }
    public function deleteArticle($id)
    {
        return $this->delete($id);
    }

    public function modifyArticle(ArticleTable $article,$userRef) :bool
    {
        $article->setModifyUserRef($userRef);
        $article->setModifyDate(\date(ES_NOW));
        return $this->update($article->getId(),$article->toArray()) ;
    }

    public function changeStatut($id,$state,$userRef) :bool
    {
        $articleComposer=$this->findById($id);
        $articleComposer->article->setState($state);
        $articleComposer->article->setStateDate(\date(ES_NOW));
        return $this->modifyArticle($articleComposer->article,$userRef);
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
        $article->setChapo($chapo);
        $article->setContent($content);
        $article->setTitle($title);
        $article->setCategoryRef($categoryRef);
        $article->setCreateUserRef($userRef);
        $article->setCreateDate(\date(ES_NOW)) ;
        $article->setState(ES_BLOG_ARTICLE_STATE_BROUILLON);
        $article->setStateDate(\date(ES_NOW)) ;
        return $article;
    }
}

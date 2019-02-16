<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;
use ES\Core\Database\QueryBuilder;
use ES\App\Modules\Blog\Model\ArticleComposer;

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
        $requete=new QueryBuilder();
        $requete->select ('count(*)')
            ->from(self::$table);
        $params=[];

        if(isset($key) && isset($value)) {
            $requete->where('ba_' . $key .'=:value');
            $params['value']=$value;
        }

        return $this->query($requete->render(),count($params)?$params:null,true)['count(*)'] ;
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
        return $this->query('SELECT ba_id, ba_title FROM ocp5_blog_article ORDER BY ba_id limit '. $number .';',null,false,false);
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

    public function recupereImagePresentation($key,$id):bool
    {

        if(!isset($_FILES[$key]['name'])) {
            return true;
        } elseif($_FILES[$key]['error']=='1') {
            $this->flash->writeError('Erreur lors de \'upload de l\'image code error:1');
        } elseif (isset($_FILES[$key]['tmp_name'])) {

            if($_FILES[$key]['type']=='image/jpeg') {
                try {


                    $destination=ES_ROOT_PATH_FAT . 'Public/images/blog/' . $id . '.jpg';
                    //return \copy($_FILES[$key]['tmp_name'],$destination);
                    if(\file_exists($destination) ){
                        \unlink($destination);
                    }
                    $taille = getimagesize($_FILES[$key]['tmp_name']);
                    $largeur = $taille[0];
                    $hauteur = $taille[1];
                    $largeur_miniature = 600;
                    $hauteur_miniature = $hauteur / $largeur * 600;
                    $im = \imagecreatefromjpeg($_FILES[$key]['tmp_name']);
                    $im_miniature = \imagecreatetruecolor($largeur_miniature
                    , $hauteur_miniature);
                    if(!\imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur)) {
                        $this->flash->writeError('Erreur lors de la création de imagecopyresampled');
                    }
                    if(!\imagejpeg($im_miniature, $destination, 90)) {
                        $this->flash->writeError('Erreur lors de la création de imagejpeg');
                    }
                } catch (\InvalidArgumentException $ex) {
                        $this->flash->writeError('Erreur lors de la création de l\'image ' . $ex->getMessage ());
                }

                return true;
            }
        }

        return false;
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

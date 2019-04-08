<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;
use ES\Core\Database\QueryBuilder;

/**
 * CategoryManager short summary.
 *
 * CategoryManager description.
 *
 * @version 1.0
 * @author ragus
 */
class CategoryManager extends AbstractManager
{
    protected static $table='ocp5_blog_category';
    protected static $order_by= CategoryTable::TITLE. ' DESC; ';
    protected static $id=CategoryTable::ID;
    protected static $classTable='ES\App\Modules\Blog\Model\CategoryTable';

    public function findById($value) :CategoryTable
    {
        return $this->findByField(CategoryTable::ID,$value);
    }
    public function findByTitle($value) :CategoryTable
    {
        return $this->findByField(CategoryTable::TITLE,$value);
    }
    public function getCategorys()
    {
        return $this->query($this->_queryBuilder
             ->select(CategoryTable::ID . ',' . CategoryTable::TITLE .', count(ba_id)')
             ->from(self::$table)
             ->outerJoin('ocp5_blog_article ON '.CategoryTable::ID.'=ba_category_ref')
             ->groupBy(CategoryTable::ID)
             ->orderBy(CategoryTable::TITLE)->render(),
           null,false,false);
    }
    public function getCategorysForSelect($firstElementEmpty)
    {
        return $this->getArrayForSelect(CategoryTable::ID,CategoryTable::TITLE,$firstElementEmpty);
    }
    public function categoryNotEmpty()
    {
        //retourne les catégorie pour les articles publiés
        return $this->query(
             $this->_queryBuilder
             ->select(CategoryTable::ID . ',' . CategoryTable::TITLE)
             ->from(self::$table)
             ->outerJoin('ocp5_blog_article ON '.CategoryTable::ID .'=ba_category_ref')
             ->where('ba_state='. ES_BLOG_ARTICLE_STATE_ACTIF)
             ->groupBy(CategoryTable::ID)
             ->having('count(ba_id)>0')
             ->orderBy(CategoryTable::TITLE)->render(),
           null,false,false);
    }

    public function deleteCategory($id)
    {
        return $this->delete($id);
    }

    public function hasArticle($id) :bool
    {
        $retour= $this->query($this->_queryBuilder
            ->select('count(ba_id)')
            ->from('ocp5_blog_article')
            ->where('ba_category_ref=:id')
            ->render(),
        ['id'=>$id],true,false)['count(ba_id)'];
        
        if ($retour==0) {
            return false;
        }
        return true;
        

    }

    public function titleExist($value, $id=null):bool
    {
        return parent::exist(CategoryTable::TITLE, $value, $id);
    }

    public function createCategory($title):CategoryTable
    {
        $category= $this->newCategory($title);

        $retour= $this->create($category->ToArray());

        if(!$retour) {
            throw new \InvalidArgumentException('Erreur lors de la création de la catégorie');
        }
        $category->setId($retour);

        return $category;
    }
    public function updateCategory(CategoryTable $category):bool
    {
        return $this->update($category->getId(),$category->ToArray());
    }
    protected function newCategory($title):CategoryTable
    {
        $category = new CategoryTable([]);
        $category->setTitle($title);
        return $category;
    }
}
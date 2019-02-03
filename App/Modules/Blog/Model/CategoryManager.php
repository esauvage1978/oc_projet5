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

    public function findById($key) :CategoryTable
    {
        return $this->findByField(CategoryTable::ID,$key);
    }

    public function getCategorys()
    {
        return $this->query('SELECT bc_id, bc_title, count(ba_id)  FROM ocp5_blog_category LEFT OUTER JOIN ocp5_blog_article ON bc_id=ba_category_ref GROUP BY bc_id ORDER BY bc_title;'
            ,null,false,false);
    }
    public function getCategorysForSelect($firstElementEmpty)
    {
        return $this->getArrayForSelect('bc_id','bc_title',$firstElementEmpty);
    }
    public function categoryNotEmpty()
    {
        return $this->query('SELECT bc_id, bc_title FROM ocp5_blog_category LEFT OUTER JOIN ocp5_blog_article ON bc_id=ba_category_ref GROUP BY bc_id HAVING count(ba_id)>0 ORDER BY bc_title ;'
            ,null,false,false);
    }

    public function deleteCategory($id)
    {
        return $this-> delete($id);
    }

    public function hasArticle($id) :bool
    {
        $retour= $this->query('SELECT count(ba_id) FROM ocp5_blog_article where ba_category_ref=:id;',
            ['id'=>$id], true)['count(ba_id)'];
        if($retour==0) {
            return false;
        }else {
            return true;
        }

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
<?php

namespace ES\App\Modules\Blog\Model;

use ES\Core\Model\AbstractManager;

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
        $retour= $this->query('SELECT bc_id, bc_title, count(ba_id)  FROM ocp5_blog_category LEFT OUTER JOIN ocp5_blog_article ON bc_id=ba_category_ref GROUP BY bc_id ORDER BY bc_title;'
            ,null,false,false);
        return $retour;
    }

    public function deleteCategory($id)
    {
        $retour= $this-> delete($id);
        return $retour;
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

    public function categoryExist($value, $id=null):bool
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
    public function newCategory($title):CategoryTable
    {
        $category = new CategoryTable([]);
        $category->setTitle($title);
        return $category;
    }
}
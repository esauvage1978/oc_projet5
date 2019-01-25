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
        } else if($key==='user' && isset($value)) {
            $retour= $this->query($this->_selectAll . 'ba_create_user_ref=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } else if($key==='category' && isset($value)) {
            $retour= $this->query($this->_selectAll . 'ba_category_ref=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } else if(isset($key) && isset($value)) {
            $retour= $this->query($this->_selectAll . 'ba_' . $key .'=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } else {
            $retour= $this->getAll();
        }
        return $retour;
    }
}
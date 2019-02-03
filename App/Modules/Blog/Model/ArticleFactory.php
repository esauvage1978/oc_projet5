<?php

namespace ES\App\Modules\Blog\Model;

use ES\App\Modules\Blog\Model\ArticleTable;
use ES\App\Modules\User\Model\UserManager;
use ES\App\Modules\Blog\Model\CategoryManager;
use ES\App\Modules\Blog\Model\CommentManager;
/**
 * ArticleFactory short summary.
 *
 * ArticleFactory description.
 *
 * @version 1.0
 * @author ragus
 */
class ArticleFactory
{
    public $userCreate;
    public $article;
    public $category;
    public $comments;
    public $commentNbr;

    public function __construct($data)
    {
        $this->article=new ArticleTable($data);

        if($this->article->hasId())
        {
            // recherche de l'utilisateur créateur
            $userManager=new UserManager();
            $this->userCreate=$userManager->findById($this->article->getCreateUserRef());

            // recherche de la catégory
            $categoryRef=$this->article->getCategoryRef();
            if(isset($categoryRef))
            {
                $categoryManager=new CategoryManager();
                $this->category=$categoryManager->findById($categoryRef);
            }

        }

    }
}
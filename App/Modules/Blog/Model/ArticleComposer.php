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
class ArticleComposer
{
    public $createUser;
    public $modifyUser;
    public $article;
    public $category;
    public $comments;
    public $commentNbr;

    public function __construct($data)
    {
        $this->article=new ArticleTable($data);

        $this->initCreateUser();
        $this->initModifyUser();
        $this->initCategory();

    }

    private function initCreateUser()
    {
        if($this->article->hasId() && !is_null($this->article->getCreateUserRef()) ) {
            // recherche de l'utilisateur créateur
            $userManager=new UserManager();
            $this->createUser=$userManager->findById($this->article->getCreateUserRef());
        }
    }
    private function initModifyUser()
    {
        if($this->article->hasId() && !is_null($this->article->getModifyUserRef()) ) {
            // recherche de l'utilisateur créateur
            $userManager=new UserManager();
            $this->modifyUser=$userManager->findById($this->article->getModifyUserRef());
        }
    }
    private function initCategory()
    {
        if($this->article->hasId() && !is_null($this->article->getCategoryRef())) {
            $categoryManager=new CategoryManager();
            $this->category=$categoryManager->findById($this->article->getCategoryRef());
        }
    }

    public function initComment()
    {
        $commentManager =new CommentManager();
        $this->comments =$commentManager->getCommentsValid($this->article->getId());  
        $this->commentNbr =\count($this->comments);
    }

}
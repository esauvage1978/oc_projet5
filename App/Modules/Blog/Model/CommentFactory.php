<?php

namespace ES\App\Modules\Blog\Model;

use ES\App\Modules\Blog\Model\CommentTable;
use ES\App\Modules\User\Model\UserManager;

/**
 * CommentFactory short summary.
 *
 * CommentFactory description.
 *
 * @version 1.0
 * @author ragus
 */
class CommentFactory
{
    public $userCreate;
    public $comment;

    public function __construct($data)
    {

        $this->comment=new CommentTable($data);

        if($this->comment->hasId())
        {

            // recherche de l'utilisateur créateur
            $userManager=new UserManager();
            $this->userCreate=$userManager->findById($this->comment->getCreateUserRef());
            if(!$this->userCreate->hasId()) {
                $this->userCreate->setIdentifiant('Anonyme');
                $this->userCreate->setId(0);
            }

        }

    }
}
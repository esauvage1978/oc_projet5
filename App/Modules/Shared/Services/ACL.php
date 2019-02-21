<?php

namespace ES\App\Modules\Shared\Services;

use ES\App\Modules\User\Model\UserConnect;

/**
 * Restrict short summary.
 *
 * Restrict description.
 *
 * @version 1.0
 * @author ragus
 */
class ACL
{
    private $_forAll=[
        'shared.accessdenied',
        'shared.show',
        'shared.errorcatch',
        'user.deconnexion',
        'blog.article.list',
        'blog.article.show',
        'blog.article.find',
        'blog.comment.add',
        'blog.category.listnotempty',
        'blog.article.last',
        'ckeditor/ckeditor.js'
        ];

    private $_restrict=[
    ES_USER_ROLE_NOT_CONNECTED =>[
        'user.connexion',
        'user.pwdforget',
        'user.pwdforgetchange',
        'user.signup',
        'user.validaccount'
        ],
    ES_USER_ROLE_VISITEUR =>[
        'user.modify',
        'blog.show'
        ],
    ES_USER_ROLE_REDACTEUR=>[
        'user.modify',
        'blog.show',
        'blog.article.add',
        'blog.article.modify',
        'blog.article.listadmin',
        'blog.article.changestatut',
        'blog.category.list'
        ],
    ES_USER_ROLE_MODERATEUR=>[
        'user.modify',
        'blog.show',
        'blog.article.add',
        'blog.article.modify',
        'blog.category.list',
        'blog.article.listadmin',
        'blog.article.changestatut',
        'blog.comment.changemoderatorstate',
        'blog.comment.listadmin',
        'shared.dashboard'
        ],
    ES_USER_ROLE_GESTIONNAIRE =>[
        'shared.dashboard',
        'user.modify',
        'user.list',
        'blog.show',
        'blog.article.add',
        'blog.article.modify',
        'blog.article.listadmin',
        'blog.article.changestatut',
        'blog.category.list',
        'blog.comment.changemoderatorstate',
        'blog.comment.listadmin'
        ]];

    private $_userConnect;

    public function __construct(UserConnect $userConnect)
    {
        $this->_userConnect=$userConnect;
    }

    public function valideAccessPage($page) :bool
    {
        $retour=true;
        if(isset($page)) {
            $userRole=ES_USER_ROLE_NOT_CONNECTED;

            if($this->_userConnect->isConnect()) {
                $userRole= $this->_userConnect->user->getUserRole() ;
            }
            if(!in_array($page,$this->_forAll ) ) {
                if(!in_array ($page,$this->_restrict[$userRole] )) {
                    $retour=false;
                }
            }
        }
        return $retour;
    }
}
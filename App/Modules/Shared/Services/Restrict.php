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
class Restrict
{
    private $_forAll=[
        'shared.accessdenied',
        'shared.show',
        'shared.errorcatch',
        'user.deconnexion',
        'blog.list',
        'blog.find',
        'blog.category.listnotempty',
        'blog.article.last'
        ];

    private $_restrict=[
    ES_NOT_CONNECTED =>[
        'user.connexion',
        'user.pwdforget',
        'user.pwdforgetchange',
        'user.signup',
        'user.validaccount'
        ],
    ES_VISITEUR =>[
        'user.modify',
        'blog.show'
        ],
    ES_REDACTEUR=>[
        'user.modify',
        'blog.show',
        'blog.article.add',
        'blog.category.list'
        ],
    ES_MODERATEUR=>[
        'user.modify',
        'blog.show',
        'blog.article.add',
        'blog.category.list',
        'blog.comment.moderate',
        'shared.dashboard'
        ],
    ES_GESTIONNAIRE =>[
        'shared.dashboard',
        'user.modify',
        'user.list',
        'blog.show',
        'blog.article.add',
        'blog.category.list',
        'blog.comment.moderate'
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
            $accreditation=ES_NOT_CONNECTED;

            if($this->_userConnect->isConnect()) {
                $accreditation= $this->_userConnect->user->getAccreditation() ;
            }
            if(!in_array($page,$this->_forAll ) ) {
                if(!in_array ($page,$this->_restrict[$accreditation] )) {
                    $retour=false;
                }
            }
        }
        return $retour;
    }
}
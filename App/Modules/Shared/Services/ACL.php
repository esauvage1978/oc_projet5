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
        'shared/accessdenied',
        'shared/accessdeniedmanyconnexion',
        'shared/show',
        'shared/contact',
        'shared/errorcatch',
        'user/deconnexion',
        'blog/article/list',
        'blog/article/show',
        'blog/article/find',
        'blog/comment/add',
        'blog/category/listnotempty',
        'blog/article/last',
        'ckeditor/ckeditor/js'
        ];

    private $_restrict=[];


    private $_userConnect;

    public function __construct(UserConnect $userConnect)
    {
        $this->_userConnect=$userConnect;

        $not_connected=[
        'user/connexion',
        'user/pwdforget',
        'user/pwdforgetchange',
        'user/signup',
        'user/user/validaccount'
        ];
        $visiteur=[
        'user/modify',
        'user/pwdchange',
        'blog/show'
        ];

        $redacteur=array_merge($visiteur, [
        'blog/article/add',
        'blog/article/modify',
        'blog/article/listadmin',
        'blog/article/changestatut',
        'blog/category/delete',
        'blog/category/add',
        'blog/category/modify',
        'blog/category/list'
        ]);
        $moderateur=array_merge($redacteur,[
        'blog/comment/changemoderatorstate',
        'blog/comment/listadmin',
        'shared/dashboard'
        ]);
        $gestionnaire=array_merge($moderateur,[
        'user/user/list',
        ]);


        $this->_restrict=[
        ES_USER_ROLE_NOT_CONNECTED =>$not_connected,
        ES_USER_ROLE_VISITEUR =>$visiteur,
        ES_USER_ROLE_REDACTEUR=>$redacteur,
        ES_USER_ROLE_MODERATEUR=>$moderateur,
        ES_USER_ROLE_GESTIONNAIRE=>$gestionnaire
        ];

    }

    public function valideAccessPage($page) :bool
    {
        $retour=true;


        if( isset($page)) {
            $userRole=ES_USER_ROLE_NOT_CONNECTED;

            if($this->_userConnect->isConnect()) {
                $userRole= $this->_userConnect->user->getUserRole() ;
            }

            $page=str_replace('.','/',$page);

            if(!in_array($page,$this->_forAll ) &&
                !in_array ($page,$this->_restrict[$userRole] )) {

                $retour=false;
            }
        }
        return $retour;
    }


}
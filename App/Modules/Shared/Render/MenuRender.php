<?php

namespace ES\App\Modules\Shared\Render;

use ES\App\Modules\Shared\Services\ACL;
use ES\App\Modules\User\Model\UserConnect;

/**
 * MenuRender short summary.
 *
 * MenuRender description.
 *
 * @version 1.0
 * @author ragus
 */
class MenuRender
{

    private $_userConnect;

    private $_liStart='<li class="nav-item">';
    private $_liEnd='</li>';

    private $_aStart='<a class="nav-link js-scroll " href="##INDEX##';
    private $_aStartActive='<a class="nav-link js-scroll active" href="##INDEX##';
    private $_aDropdownStart='<a class="dropdown-item" href="##INDEX##';
    private $_aMiddle='">';
    private $_aEnd='</a>';

    private $_dropdowDivideur='<div class="dropdown-divider"></div>';
    private $_dropdownStart='<li class="nav-item dropdown">' .
                            '<a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    private $_dropdownMiddle='</a>' .
                            '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
    private $_dropdownEnd='';
    private $_restrict;

    public function __construct(UserConnect $userConnect)
    {
        $this->_userConnect =$userConnect;
        $this->_restrict =new ACL($userConnect);
    }

    public function render() :string
    {
        $menu=$this->renderMenuApropos().
              $this->renderMenuContact().
              $this->renderMenuBlog();
        if (!$this->_userConnect->isConnect ()) {

            $menu.=$this->renderMenuDisconnet();
        } else {

            $menu.=$this->renderMenuRole($this->_userConnect->user->getUserRole()) .
                  $this->renderMenuUserConnet();
        }
        return $menu;
    }
    public function renderFooter() :string
    {

        if (!$this->_userConnect->isConnect ()) {

            $menu=$this->renderMenuDisconnet();
        } else {

            $menu=$this->renderMenuRole($this->_userConnect->user->getUserRole()) .
                  $this->renderMenuUserConnet();
        }
        return $menu;
    }
    private function renderMenuApropos()
    {
        return  $this->_liStart .
                $this->createMainLink('#about','A propos') .
                $this->_liEnd;
    }

    private function renderMenuBlog()
    {
        return  $this->_liStart .
                $this->createMainLink('blog/article/list#about','Blog') .
                $this->_liEnd;
    }
    private function renderMenuDisconnet()
    {
        return  $this->_liStart .
                $this->createMainLink('user/connexion','Connexion') .
                $this->_liEnd;
    }
    private function renderMenuContact()
    {
        return  $this->_liStart .
                $this->createMainLink('#contact','Contactez-moi') .
                $this->_liEnd;
    }

    private function renderMenuUserConnet()
    {
        $menu= $this->_dropdownStart .
                'Votre compte' .
               $this->_dropdownMiddle ;

        $bloc=$this->checkAndCreateDropDownLink('user/modify','Mon compte','user/modify/' . $this->_userConnect->user->getId());
        $bloc.= ($bloc!='')? $this->_dropdowDivideur:'';

        return $menu. $bloc .
               $this->checkAndCreateDropDownLink('user/deconnexion','Me déconnecter') .
               $this->_dropdownEnd ;

    }
    private function renderMenuRole($userRole)
    {
        if($userRole!=ES_USER_ROLE_VISITEUR) {
            $menu=$this->_dropdownStart .
                   ES_USER_ROLE[$userRole] .
                   $this->_dropdownMiddle ;
            $bloc=$this->checkAndCreateDropDownLink('shared/dashboard','Tableau de bord');
            if($bloc!='') {
                $bloc.= $this->_dropdowDivideur;
            }
            $menu.=$bloc;

            $bloc=$this->checkAndCreateDropDownLink('user/list','Liste des utilisateurs');
            if($bloc!='') {$bloc.= $this->_dropdowDivideur;}
            $menu.=$bloc;

            $bloc=$this->checkAndCreateDropDownLink('blog/article/add','Ajout d\'un article');
            $bloc.=$this->checkAndCreateDropDownLink('blog/category/list','Gestion des catégories');
            $bloc.=$this->checkAndCreateDropDownLink('blog/comment/listadmin','Modérer les commentaires');
            if($bloc!='') {$bloc.= $this->_dropdowDivideur;}
            $menu.=$bloc;

            return $menu . $this->_dropdownEnd;
        }
        return '';
    }


    private function checkAndCreateDropDownLink($page,$libelle,$url=null) :string
    {

        if ($this->_restrict->valideAccessPage ($page) ) {
            return $this->createDropDownLink (
                (isset($url)?$url:$page),
                $libelle);
        }
        return '';
    }

    private function createMainLink($link,$label,$active=false)
    {
        return ($active?$this->_aStartActive:$this->_aStart) . $link . $this->_aMiddle .
               $label .$this->_aEnd ;
    }
    private function createDropDownLink($link,$label)
    {
        return $this->_aDropdownStart . $link . $this->_aMiddle .
               $label .$this->_aEnd ;
    }
}
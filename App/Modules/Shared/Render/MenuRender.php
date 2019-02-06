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

    private $_aStart='<a class="nav-link js-scroll" href="##INDEX##';
    private $_aDropdownStart='<a class="dropdown-item" href="##INDEX##';
    private $_aMiddle='#">';
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
        $menu='';
        if (!$this->_userConnect->isConnect ()) {
            $menu=$this->renderMenuDisconnet();
        } else {

            $menu=$this->renderMenu($this->_userConnect->user->getUserRole());
            $menu.=$this->renderMenuConnet();

        }
        return $menu;
    }

    private function renderMenuDisconnet()
    {
        return  $this->_liStart .
                $this->createLink('user.connexion','Connexion') .
                $this->_liEnd;
    }
    private function renderMenuConnet()
    {
        $menu= $this->_dropdownStart .
                'Votre compte' .
               $this->_dropdownMiddle ;
        $bloc=$this->checkLink('user.modify','Mon compte','user.modify/' . $this->_userConnect->user->getId());
        if($bloc!='') {
            $bloc.= $this->_dropdowDivideur;
        }
        $menu.=$bloc;

        $bloc=$this->checkLink('user.deconnexion','Me déconnecter');
        $menu.=$bloc;
        return $menu . $this->_dropdownEnd;

    }
    private function renderMenu($userRole)
    {
        $menu=$this->_dropdownStart .
               ES_USER_ROLE[$userRole] .
               $this->_dropdownMiddle ;
        $bloc=$this->checkLink('shared.dashboard','Tableau de bord');
        if($bloc!='') {
            $bloc.= $this->_dropdowDivideur;
        }
        $menu.=$bloc;

        $bloc=$this->checkLink('user.list','Liste des utilisateurs');
        if($bloc!='') {$bloc.= $this->_dropdowDivideur;}
        $menu.=$bloc;

        $bloc=$this->checkLink('blog.article.add','Ajout d\'un article');
        $bloc.=$this->checkLink('blog.category.list','Gestion des catégories');
        $bloc.=$this->checkLink('blog.commentlist','Modérer les commentaires');
        if($bloc!='') {$bloc.= $this->_dropdowDivideur;}
        $menu.=$bloc;

        return $menu . $this->_dropdownEnd;
    }


    private function checkLink($page,$libelle,$url=null) :string
    {
        if ($this->_restrict->valideAccessPage ($page) ) {
            return $this->createDropDownLink (
                (isset($url)?$url:$page),
                $libelle);
        }
        return '';
    }

    private function createLink($link,$label)
    {
        return $this->_aStart . $link . $this->_aMiddle .
               $label .$this->_aEnd ;
    }
    private function createDropDownLink($link,$label)
    {
        return $this->_aDropdownStart . $link . $this->_aMiddle .
               $label .$this->_aEnd ;
    }
}
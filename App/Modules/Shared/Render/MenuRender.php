<?php

namespace ES\App\Modules\Shared\Render;

use \ES\App\Modules\User\Model\UserTable;
use \ES\App\Modules\User\Model\UserConnect;
use \ES\Core\Toolbox\Request;
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
    private $_request;
    private $_userConnect;
    private $_user;

    private $_liStart='<li class="nav-item">';
    private $_liEnd='</li>';

    private $_aStart='<a class="nav-link js-scroll" href="##INDEX##';
    private $_aDropdownStart='<a class="dropdown-item" href="##INDEX##';
    private $_aMiddle='">';
    private $_aEnd='</a>';

    private $_dropdowDivideur='<div class="dropdown-divider"></div>';
    private $_dropdownStart='<li class="nav-item dropdown">' .
                            '<a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    private $_dropdownMiddle='</a>' .
                            '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
    private $_dropdownEnd='';

    public function __construct(Request $request)
    {
        $this->_request=$request;
        $this->_userConnect =new UserConnect($this->_request);

    }

    public function render() :string
    {
        $menu='';
        if (!$this->_userConnect->isConnect ()) {
            $menu=$this->renderMenuDisconnet();
        } else {
            $this->_user=$this->_userConnect->getUserConnect();

            switch($this->_user->getAccreditation())
            {
                case ES_GESTIONNAIRE:
                    $menu=$this->renderMenuGestionnaire();
                    $menu.=$this->renderMenuConnet();
                    break;
                case ES_MODERATEUR:
                    $menu=$this->renderMenuModerateur();
                    $menu.=$this->renderMenuConnet();
                    break;
                case ES_REDACTEUR:
                    $menu=$this->renderMenuRedacteur();
                    $menu.=$this->renderMenuConnet();
                    break;
                case ES_VISITEUR:
                    $menu=$this->renderMenuConnet();
                    break;
                default:
                    $menu=$this->renderMenuConnet();
                    break;
            }
        }
        return $menu;
    }

    private function menuDashbord():string
    {
        return $this->createDropDownLink ('shared.dashboard','Tableau de bord');
    }

    private function renderMenuDisconnet()
    {
        return  $this->_liStart .
                $this->createLink('user.connexion','Connexion') .
                $this->_liEnd;
    }
    private function renderMenuConnet()
    {
        return $this->_dropdownStart .
                'Votre compte' .
               $this->_dropdownMiddle .
               $this->createDropDownLink ('user.modify/' . $this->_user->getId(),'Mon compte') .
                $this->_dropdowDivideur .
               $this->createDropDownLink ('user.deconnexion','Me déconnecter') .
               $this->_dropdownEnd;

    }
    private function renderMenuGestionnaire()
    {
        return $this->_dropdownStart .
                ES_ACCREDITATION[ES_GESTIONNAIRE] .
               $this->_dropdownMiddle .
               $this->menuDashbord() .
                $this->_dropdowDivideur .
               $this->createDropDownLink ('user.list','Liste des utilisateurs') .
               $this->_dropdownEnd;
    }
    private function renderMenuModerateur()
    {
        return $this->_dropdownStart .
                ES_ACCREDITATION[ES_MODERATEUR] .
               $this->_dropdownMiddle .
               $this->menuDashbord() .
                $this->_dropdowDivideur .

               $this->_dropdownEnd;
    }
    private function renderMenuRedacteur()
    {
        return $this->_dropdownStart .
                ES_ACCREDITATION[ES_REDACTEUR] .
               $this->_dropdownMiddle .
               $this->menuDashbord() .
                $this->_dropdowDivideur .

               $this->_dropdownEnd;
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
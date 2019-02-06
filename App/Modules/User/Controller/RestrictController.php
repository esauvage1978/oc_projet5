<?php

namespace ES\App\Modules\User\Controller;

trait RestrictController
{



    public function accueilView($exit=false)
    {
        header('Location: ' . ES_ROOT_PATH_WEB . '#topContent');
        if($exit) {exit;}
    }
    public function accessDeniedView($information,$exit=false)
    {
        $this->flash->writeError($information);
        header('Location: ' . ES_ROOT_PATH_WEB . 'shared.accessdenied');
        if($exit) {exit;}
    }
    public function errorCatchView($information,$exit=false)
    {
        $this->flash->writeError($information);
        header('Location: ' . ES_ROOT_PATH_WEB . 'shared.errorcatch');
        if($exit) {exit;}
    }
    public function valideAccessPage($MustBeConnec ,$userRole=1) :bool
    {
        $retour=true;


        if(!$MustBeConnec) {
            $retour = !$this->_userConnect->isConnect();
        } elseif(! $this->_userConnect->isConnect() ) {
            $retour=false;
        } elseif($this->_userConnect->user->getUserRole() < $userRole  ) {
            $retour=false;
        }
        if(!$retour)
        {
            $this->accessDeniedView(null,true);
        }
        return $retour;
    }
    public function valideAccessPageOwnerOrGestionnaire($id):bool
    {
        $retour=false;
        //si il est gestionnaire
        $user=$this->_userConnect->user;
        if( $user->getUserRole() == ES_USER_ROLE_GESTIONNAIRE ) {
            $retour=true;
        } else if ( $user->getId()==$id ) {
            $retour=true;
        } else {
            $this->accessDeniedView('Vous n\'êtes ni propriétaire ni gestionnaire',true);
        }
        return $retour;
    }
}
<?php

namespace ES\App\Modules\Shared\Controller;

use ES\App\Modules\User\Model\UserConnect;

trait restrictControler
{
    public function AccueilView($exit=false)
    {
        header('Location: ' . ES_ROOT_PATH_WEB . '#topsection');
        if($exit) {exit;}
    }

    public function valideAccessPage($MustBeConnec ,$accredication=1) :bool
    {
        $message='accès interdit à cette page';
        $retour=true;
        $userConnect=new UserConnect($this->_request );
        if(!$MustBeConnec) {
            $retour = !$userConnect->isConnect();
        } else if($userConnect->getUserConnect()->getAccreditation()< $accredication  ) {
                $retour=false;
        }
        if(!$retour)
        {
            $this->flash->writeError($message);
        }
        return $retour;
    }
    public function valideAccessPageOwnerOrGestionnaire($user, $id):bool
    {
        $retour=false;
        //si il est gestionnaire
        if( $user->getAccreditation() == ES_GESTIONNAIRE ) {
            $retour=true;
        } else if ( $user->getId()==$id ) {
            $retour=true;
        } else {
            $this->flash->writeError('Vous n\'êtes ni propriétaire ni gestionnaire');
            $retour=false;
        }
        return $retour;
    }
}
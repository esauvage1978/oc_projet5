<?php

namespace ES\App\Modules\Shared\Controller;

use \ES\Core\Controller\AbstractController;

/**
 * SharedController short summary.
 * Controller frontal de base
 * SharedController description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedController extends AbstractController
{
    static $module='Shared';

    const DECONNECTE='-1';
    const CONNECTE='1';
    const REDACTEUR='2';
    const MODERATEUR='3';
    const GESTIONNAIRE='4';

    public function homeShow()
    {
        $this->view('HomeView',
            [
                'title'=>'Page d\'accueil'
            ]);
    }

    public function AccueilView($exit=false)
    {
        header('Location: .');
        if($exit) {exit;}
    }

    public function valideAccessPage($minimalAccreditation) :bool
    {
        $message='accès interdit à cette page';
        $retour=true;
        switch ($minimalAccreditation)
        {
            case self::DECONNECTE:
                $retour=!$this->_request->hasSessionValue('user');
                break;
            case self::CONNECTE:
                $retour=$this->_request->hasSessionValue('user');
                break;
            default:
                throw new \InvalidArgumentException('Paramètre incorrecte d\{appel de la function');
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
        if( $user->getAccreditation() == self::GESTIONNAIRE) {
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
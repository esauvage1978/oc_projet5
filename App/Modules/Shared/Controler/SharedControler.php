<?php

namespace ES\App\Modules\Shared\Controler;


use \ES\Core\Controler\AbstractControler;
use \ES\App\Modules\User\Model\UserTable;
/**
 * SharedControler short summary.
 * Controler frontal de base
 * SharedControler description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedControler extends AbstractControler
{
    protected static $module='Shared';

    const DECONNECTE='-1';
    const CONNECTE='0';
    const MODERATEUR='1';
    const REDACTEUR='2';
    const ADMINISTRATEUR='3';

    public function homeShow()
    {
        $this->view('HomeView',
            [
                'title'=>'Page d\'accueil'
            ]);
    }

    public function AccueilView()
    {
        header('Location: .');
    }

    public function valideAccessPage($minimal_accreditation)
    {
        switch ($minimal_accreditation)
        {
            case self::DECONNECTE:
                if($this->_request->hasSessionValue('user'))
                {
                    throw new \InvalidArgumentException('Vous ne pouvez pas vous rendre sur cette page en étant connecté.');
                }
                break;
            case self::CONNECTE:
                if(!$this->_request->hasSessionValue('user'))
                {
                    throw new \InvalidArgumentException('Vous devez vous connecter.');
                }
                break;

        }
    }
    public function valideAccessPageOwnerOrAdmin($user, $id)
    {
        if ( $user->getId()!=$id && $user->getAccreditation() !=self::ADMINISTRATEUR)
        {
             throw new \InvalidArgumentException('Vous n\'êtes pas administrateur.');
        }
        else if ($user->getId()!=$id )
        {
             throw new \InvalidArgumentException('Vous n\'êtes pas le propriétaire.');
        }
    }
}
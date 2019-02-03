<?php

namespace ES\App\Modules\User\Model;

use ES\App\Modules\User\Model\UserTable;
use ES\App\Modules\User\Model\UserManager;
use ES\Core\Toolbox\Request;
/**
 * UserConnect short summary.
 *
 * UserConnect description.
 *
 * @version 1.0
 * @author ragus
 */
class UserConnect
{
    private $_request;
    public $user=null;
    const USER_KEY='user';

    public function __construct(Request $request)
    {
        $this->_request =$request;
        $this->user=$this->getUserConnect();
    }

    #region SESSION
    public function connect($user)
    {
        $this->_request->setSessionValue(self::USER_KEY,$user->getId());
    }
    public function disconnect()
    {
        $this->_request->unsetSessionValue(self::USER_KEY);
    }
    private function getUserConnect()
    {
        if($this->isConnect() ){
            if(!isset($this->user)){
                $userManager=new UserManager();
                return $userManager->findById($this->_request->getSessionValue(self::USER_KEY));
            } else {
                return $this->user;
            }
        } else {
            return null;
        }
    }
    public function isConnect():bool
    {
        return $this->_request->hasSessionValue(self::USER_KEY);
    }
    public function canAdministrator():bool
    {
        $retour =false;
        if(isset($this->user) && $this->user->getAccreditation() == ES_GESTIONNAIRE) {
                $retour=true;
        }
        return $retour;
    }
    public function canModerator():bool
    {
        $retour =false;
        if(isset($this->user) && $this->user->getAccreditation() >= ES_MODERATEUR) {
            $retour=true;
        }
        return $retour;
    }
    public function canRedactor():bool
    {
        $retour =false;
        if(isset($this->user) && $this->user->getAccreditation() >= ES_REDACTEUR) {
            $retour=true;
        }
        return $retour;
    }

    #endregion
}
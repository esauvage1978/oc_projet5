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
    private $_UserManager;
    const USER_KEY='user';

    public function __construct(Request $request)
    {
        $this->_request =$request;
        $this->_UserManager=new UserManager();
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
    public function getUserConnect() : userTable
    {
        return $this->_UserManager->findById($this->_request->getSessionValue(self::USER_KEY));
    }
    public function isConnect():bool
    {
        return $this->_request->hasSessionValue(self::USER_KEY);
    }
    #endregion
}
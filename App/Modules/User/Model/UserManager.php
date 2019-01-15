<?php

namespace ES\App\Modules\User\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\User\Model\UserTable;
/**
 * UserManager short summary.
 *
 * UserManager description.
 *
 * @version 1.0
 * @author ragus
 */
class UserManager extends AbstractManager
{
    protected static $table='ocp5_user';
    protected static $order_by;
    protected static $id='u_id';

    public function identifiantExist($identifiant,$id=null)
    {
        return parent::exist('u_identifiant', $identifiant, $id );
    }
    public function mailExist($mail,$id=null)
    {
        return parent::exist('u_mail', $mail, $id );
    }



}
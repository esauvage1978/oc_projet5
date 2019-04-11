<?php

namespace ES\App\Modules\User\Model;

use ES\App\Modules\User\Model\ConnexionTable;
use ES\Core\Model\AbstractManager;
/**
 * ConnexionManager short summary.
 *
 * ConnexionManager description.
 *
 * @version 1.0
 * @author ragus
 */
class ConnexionManager extends AbstractManager
{
    protected static $table='ocp5_connexion';
    protected static $order_by= ConnexionTable::ID. ' DESC; ';
    protected static $id=ConnexionTable::ID;
    protected static $classTable='ES\App\Modules\User\Model\ConnexionTable';

    public function IsBlackList($ip) :bool
    {
        return $this->query(
            $this->_queryBuilder
            ->select ($this->_queryBuilder::COUNT)
            ->from(self::$table)
            ->where(ConnexionTable::IP .'=:ip')
            ->where(ConnexionTable::DATE .'="' . \date(ES_NOW_SHORT) . '"')
            ->where(ConnexionTable::NBR_CONNEXION .'>' . ES_BRUTEFORCE_LIMITE_CONNEXION_KO )
            ->render(),['ip'=>$ip],true,false)[$this->_queryBuilder::COUNT] ;
    }

    public function addConnexion($ip, $userRef=null)
    {

        $connexion=$this->findByIp($ip,$userRef);

        if($connexion->hasId()) {
            $connexion->setNbrConnexion($connexion->getNbrConnexion()+1);
            $this->update ($connexion->getId(),$connexion->toArray());
        } else {
            $connexion= $this->NewConnexion($ip,$userRef);
            $this->create($connexion->ToArray());
        }
    }
    private function NewConnexion($ip, $userRef=null):ConnexionTable
    {
        $connexion = new ConnexionTable([]);
        $connexion->setIp($ip);
        $connexion->setUserRef($userRef );
        $connexion->setNbrConnexion(1);
        $connexion->setDate(\date(ES_NOW_SHORT)) ;
        return $connexion;
    }
    public function findByIp($ip, $userRef=null):ConnexionTable
    {


        $this->_queryBuilder
            ->select('*')
            ->from(self::$table);

        if(isset($userRef)) {
            $this->_queryBuilder
                ->where(ConnexionTable::IP .'=:ip', ConnexionTable::DATE .'="' . \date(ES_NOW_SHORT) . '"', ConnexionTable::USER_REF . '=:userRef' );
            $params=['ip'=>$ip,'userRef'=>$userRef];
        } else {
            $this->_queryBuilder
                ->where(ConnexionTable::IP .'=:ip', ConnexionTable::DATE .'="' . \date(ES_NOW_SHORT) . '"');
            $params=['ip'=>$ip];
        }
        return $this->query(
            $this->_queryBuilder
            ->render(),
            $params,
            true,true);
    }
}
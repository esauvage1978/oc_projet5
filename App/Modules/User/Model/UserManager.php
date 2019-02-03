<?php

namespace ES\App\Modules\User\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\User\Model\UserTable;
Use ES\Core\Toolbox\Auth;
use ES\Core\Mail\Mail;

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
    protected static $order_by= UserTable::IDENTIFIANT. ' ASC; ';
    protected static $id=UserTable::ID;
    protected static $classTable='ES\App\Modules\User\Model\UserTable';


    #region CONTROLE
    public function identifiantExist($identifiant, $id=null):bool
    {
        return parent::exist(UserTable::IDENTIFIANT, $identifiant, $id);
    }

    public function mailExist($mail,$id=null) :bool
    {
        return parent::exist(UserTable::MAIL, $mail,$id) ;
    }
    #endregion

    #region FIND
    public function findById($key) :UserTable
    {
        return $this->findByField(UserTable::ID,$key);
    }
    public function findUserByLogin($value):userTable
    {
        $retour= $this->query(
            $this->_selectAll . ' (' . UserTable::IDENTIFIANT . '=:params1 OR '. UserTable::MAIL .'=:params2) ;'
            , [
            'params1'=>$value,
            'params2'=>$value]
            ,
            true);
        return $this->createClassTable ($retour);
    }
    public function findByForgetHash($key) :UserTable
    {
            $retour= $this->query(
            $this->_selectAll . ' ' . UserTable::FORGET_HASH . '=:params1 AND DATEDIFF( NOW() ,' . UserTable::FORGET_DATE. ')<1;'
            , [
            'params1'=>$key
            ]
            ,
            true);
            return $this->createClassTable ($retour);
    }
    public function findByValidAccountHash($key):userTable
    {
        return $this->findByField(UserTable::VALID_ACCOUNT_HASH,$key);
    }
    #endregion

    #region get *
    public function getUsers($key=null,$value=null)
    {
        if($key==='validaccount' && $value===0) {
            $retour= $this->query($this->_selectAll . ' u_valid_account_date is null ORDER BY ' . static::$order_by . ';');
        } else if($key==='validaccount' && $value===1) {
            $retour= $this->query($this->_selectAll . ' u_valid_account_date is not null ORDER BY ' . static::$order_by . ';');
        } else if(isset($key) && isset($value)) {
                $retour= $this->query($this->_selectAll . 'u_' . $key .'=:value ORDER BY ' . static::$order_by . ';',['value'=>$value]);
        } else {
            $retour= $this->getAll();
        }
        return $retour;
    }
    #endregion
    #region count
    public function countUsers($key=null,$value=null)
    {
        if($key==='validaccount' && $value===0) {
            $retour= $this->query($this->_selectCount . ' u_valid_account_date is null ORDER BY ' . static::$order_by . ';',null, true)['count(*)'];
        } else if($key==='validaccount' && $value===1) {
            $retour= $this->query($this->_selectCount . ' u_valid_account_date is not null ORDER BY ' . static::$order_by . ';',true)['count(*)'];
        } else if(isset($key) && isset($value)) {
            $retour= $this->query($this->_selectCount . 'u_' . $key .'=:value ORDER BY ' . static::$order_by . ';',['value'=>$value],true)['count(*)'];
        } else {
            $retour= $this->Count();
        }
        return $retour;
    }
    #endregion

    public function updateUser(UserTable $user):bool
    {
        return $this->update($user->getId(),$user->ToArray());
    }
    public function createUser($identifiant, $mail,$secret):UserTable
    {
        $user= $this->NewUser($identifiant,$mail,$secret);

        if(!$this->identifiantExist($identifiant) &&
          ! $this->mailExist ($mail))
        {

            $retour= $this->create($user->ToArray());

            if(!$retour) {
                throw new \InvalidArgumentException('Erreur lors de la création de l\'utilisateur');
            }
            $user->setId($retour);
        }

        return $user;
    }



    public function NewUser($identifiant, $mail,$secret):UserTable
    {
        $user = new UserTable([]);
        $user->setIdentifiant($identifiant);
        $user->setMail($mail);
        $user->setPassword(Auth::passwordCrypt($secret));
        $user->setValidAccountHash(Auth::strRandom());
        $user->setAccreditation(ES_VISITEUR);
        $user->setActif(1);
        $user->setActifDate(\date(ES_NOW)) ;
        return $user;
    }



    public function validAccountReset(UserTable $user):bool
    {
        $user->setValidAccountHash(null);
        $user->setValidAccountDate(\date(ES_NOW));

        return $this->updateUser($user);
    }
    public function changeActifOfUser(UserTable $user):bool
    {

        $user->setActif(($user->getActif()=='1'?'0':'1'));
        $user->setActifDate(date(ES_NOW));

        return $this->updateUser($user);
    }
    public function forgetInit(UserTable $user):bool
    {
        $user->setForgetHash(Auth::strRandom());
        $user->setForgetDate(date(ES_NOW));

        if(! $this->updateUser($user)) {
            throw new \InvalidArgumentException('Erreur lors de la mise à jour');
        }

        return $this->sendMailPwdForget($user);

    }
    public function forgetReset(UserTable $user,$pwd):bool
    {
        $user->setForgetHash(null);
        $user->setForgetDate(date(ES_NOW));
        $user->setPassword (Auth::passwordCrypt($pwd));

        return $this->updateUser($user);
    }
    public function updatePassword(UserTable $user,$pwd):bool
    {
        $user->setPassword (Auth::passwordCrypt($pwd));

        return $this->updateUser($user);
    }
    #region MAIL
    public function sendMailSignup(UserTable $user):bool
    {
        $content='Bonjour,<br/><br/>
            Vous vous êtes récemment inscrit sur notre site.<br/>
            Afin de finaliser l\'inscription et de valider votre compte,
            <a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.validaccount/' . $user->getValidAccountHash() . '">cliquez ici</a>
            ou collez le lien suivant dans votre navigateur ' . ES_ROOT_PATH_WEB_INDEX . 'user.validaccount/' . $user->getValidAccountHash() . '
            <br/><br/>Merci d\'utiliser ' . ES_APPLICATION_NOM;
        $mail=new Mail();
        if(! $mail->send($user->getMail(),'Validation du compte',$content)) {
            throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail.');
        }
        return true;
    }
    public function sendMailPwdForget(UserTable $user) :bool
    {
        $content='Bonjour,<br/><br/>

                    Vous avez récemment sollicité une réinitialisation de votre mot de passe.<br/>
                    Pour modifier votre mot de passe de connexion, <a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.pwdforgetchange/' . $user->getForgetHash() . '">
                    cliquez ici</a> ou collez le lien suivant dans votre navigateur : ' . ES_ROOT_PATH_WEB_INDEX . 'user.pwdforgetchange/' . $user->getForgetHash(). '

                    <br/><br/>Le lien expirera dans 24 heures, assurez-vous de l\'utiliser bientôt.<br/><br/>
                    Si vous n\'êtes pas à l\'origine de cette demande, ignorez simplement ce mail.
                    Vos identifiants de connexion n\'ont pas été modifiés.<br/><br/>
                    Merci d\'utiliser ' . ES_APPLICATION_NOM;

        $mail=new Mail();
        if(! $mail->send($user->getMail(),'Réinitialisation du mot de passe',$content)) {
            throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail. ');
        }
        return true;
    }
    #endregion
}
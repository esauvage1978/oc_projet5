<?php

namespace ES\App\Modules\User\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\User\Model\UserTable;
Use ES\Core\Toolbox\Auth;

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
    protected static $order_by= self::IDENTIFIANT. ' ASC; ';
    protected static $id=self::ID;
    protected static $classTable='ES\App\Modules\User\Model\UserTable';

    const ID='u_id';
    const IDENTIFIANT='u_identifiant';
    const MAIL='u_mail';
    const SECRET='u_password';
    const FORGET_HASH='u_forget_hash';
    const FORGET_DATE='u_forget_date';
    const VALID_ACCOUNT_HASH='u_valid_account_hash';
    const VALID_ACCOUNT_DATE='u_valid_account_date';
    const ACCREDITATION='u_accreditation';
    const ACTIF='u_actif';
    const ACTIF_DATE='u_actif_date';



    #region CONTROLE
    public function identifiantExist($identifiant, $id=null):bool
    {
        return parent::exist(self::IDENTIFIANT, $identifiant, $id);
    }

    public function mailExist($mail,$id=null) :bool
    {
        return parent::exist(self::MAIL, $mail,$id) ;
    }
    #endregion

    #region FIND
    public function findById($key) :UserTable
    {
        return $this->findByField(self::ID,$key);
    }
    public function findUserByLogin($value):userTable
    {
        $retour= $this->query(
            $this->_selectAll . ' (' . self::IDENTIFIANT . '=:params1 OR '. self::MAIL .'=:params2) ;'
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
            $this->_selectAll . ' ' . self::FORGET_HASH . '=:params1 AND DATEDIFF( NOW() ,' . self::FORGET_DATE. ')<1;'
            , [
            'params1'=>$key
            ]
            ,
            true);
            return $this->createClassTable ($retour);
    }
    public function findByValidAccountHash($key):userTable
    {
        return $this->findByField(self::VALID_ACCOUNT_HASH,$key);
    }
    #endregion


    public function updateUser(UserTable $user):bool
    {
        return $this->update($user->getId(),$this->UserToArray($user));
    }
    public function createUser($identifiant, $mail,$secret):UserTable
    {

        $user= $this->NewUser($identifiant,$mail,$secret);

        if(!$this->identifiantExist($identifiant) &&
          ! $this->mailExist ($mail))
        {

            $retour= $this->create($this->UserToArray($user));

            if(!$retour) {
                throw new \InvalidArgumentException('Erreur lors de la création de l\'utilisateur');
            }
            $user->setId($retour);
        }

        return $user;
    }

    private function UserToArray($user):array
    {
        return [
                self::IDENTIFIANT=>$user->getIdentifiant(),
                self::MAIL=>$user->getMail(),
                self::SECRET=>$user->getPassword(),
                self::FORGET_HASH=>$user->getForgetHash(),
                self::FORGET_DATE=>$user->getForgetDate(),
                self::VALID_ACCOUNT_HASH=>$user->getValidAccountHash(),
                self::VALID_ACCOUNT_DATE=>$user->getValidAccountDate(),
                self::ACCREDITATION=>$user->getAccreditation(),
                self::ACTIF=>$user->getActif(),
                self::ACTIF_DATE=>$user->getActifDate()
            ];
    }

    public function NewUser($identifiant, $mail,$secret):UserTable
    {
        return new UserTable
                ([
                    self::IDENTIFIANT=>$identifiant,
                    self::MAIL=>$mail,
                    self::SECRET=>Auth::passwordCrypt($secret),
                    self::FORGET_HASH=>null,
                    self::FORGET_DATE=>null,
                    self::VALID_ACCOUNT_HASH=>Auth::strRandom(),
                    self::VALID_ACCOUNT_DATE=>null,
                    self::ACCREDITATION=>1,
                    self::ACTIF=>1,
                    self::ACTIF_DATE=>date(ES_NOW)
                ]);
    }

    public function isValidAccount(UserTable $user):bool
    {
        return !is_null( $user->getValidAccountDate() );
    }

    public function validAccountReset(UserTable $user):bool
    {
        $user->setValidAccountHash(null);
        $user->setValidAccountDate(date(ES_NOW));

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
        $content='<a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.validaccount/' . $user->getValidAccountHash() . '">Lien pour activer le compte</a>';
        if(! mail($user->getMail(),'Validation du compte',$content)) {
            throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail. Bon en attendant de trouver comment envoyer des mails, voici le lien :'.
            '<a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.validaccount/' . $user->getValidAccountHash() . '">Lien pour activer le compte</a>');
        }
        return true;
    }
    public function sendMailPwdForget(UserTable $user) :bool
    {
        $content='<a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.pwdforgetchange/' . $user->getForgetHash() . '">Lien pour modifier le mot de passe</a>';
        if(! mail($user->getMail(),'Réinitialisation du mot de passe',$content)) {
            throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail. Bon en attendant de trouver comment envoyer des mails, voici le lien :'.
            '<a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.pwdforgetchange/' . $user->getForgetHash() . '">Lien pour modifier le mot de passe</a>');
        }
        return true;
    }
    #endregion
}
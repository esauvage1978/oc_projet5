<?php

namespace ES\App\Modules\User\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\User\Model\UserTable;
Use ES\Core\ToolBox\Auth;
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
    protected static $id=self::ID;

    const ID='u_id';
    const IDENTIFIANT='u_identifiant';
    const MAIL='u_mail';
    const PWD='u_password';
    const FORGET_HASH='u_forget_hash';
    const FORGET_DATE='u_forget_DATE';
    const VALID_ACCOUNT_HASH='u_valid_account_hash';
    const VALID_ACCOUNT_DATE='u_valid_account_date';
    const ACCREDITATION='u_accreditation';

    #region SESSION
    public function connect($user,$request)
    {
        $request->setSessionValue('user',$user->getId());
    }
    public function disconnect($request)
    {
        $request->unsetSessionValue('user');
    }
    public function getUserConnect($request) : userTable
    {
        return $this->findById($request->getSessionValue('user'));
    }

    #endregion

    #region CONTROLE
    public function passwordCompare($pwd,$pwdCompare,$hash=false)
    {
        if(!isset($pwd) || !isset($pwdCompare) )
        {
            throw new \InvalidArgumentException('Le mot de passe ou sa confirmations est vide');
        }
        else if ($hash && !Auth::password_compare($pwd, $pwdCompare))
        {
            throw new \InvalidArgumentException('Erreur de mot de passe');
        }
        else if (!$hash && $pwd != $pwdCompare  )
        {
            throw new \InvalidArgumentException('Le mot de passe et sa confirmations sont différent');
        }
    }

    public function identifiantExist($identifiant,$id=null)
    {
        if( parent::exist(self::IDENTIFIANT, $identifiant,$id) )
        {
            throw new \InvalidArgumentException('L\'$identifiant existe déjà!!');
        }
    }
    public function mailExist($mail,$id=null)
    {
        if( parent::exist(self::MAIL, $mail,$id) )
        {
            throw new \InvalidArgumentException('Le mail existe déjà!!');
        }
    }
    #endregion
    public function findById($key)
    {
        if(isset($key))
        {
            $retour= $this->findByField(self::ID,$key);
            if(!$retour)
            {
                throw new \InvalidArgumentException('Le compte n\'a pass été trouvé');
            }
            else
            {
                return new UserTable($retour);
            }
        }
        throw new \InvalidArgumentException('Les paramètres sont incorrects');
    }
    public function findUserByLogin($login)
    {
        if(isset($login))
        {
            $retour= $this->query(
            $this->_selectAll . static::$table . ' WHERE (' . self::IDENTIFIANT . '=:params1 OR '. self::MAIL .'=:params2) ;'
            , [
            'params1'=>$login,
            'params2'=>$login]
            ,
            true);
            if(!$retour)
            {
                throw new \InvalidArgumentException('Le login n\'a pas été trouvé');
            }
            else
            {
             return new UserTable($retour);
            }
        }
        throw new \InvalidArgumentException('Le login est vide');
    }
    public function findByForgetHash($key)
    {
        if(isset($key))
        {
            $retour= $this->query(
            $this->_selectFromWhere . ' ' . self::FORGET_HASH . '=:params1 AND DATEDIFF( NOW() ,' . self::FORGET_DATE. ')<1;'
            , [
            'params1'=>$key
            ]
            ,
            true);
            if(!$retour)
            {
                throw new \InvalidArgumentException('Le compte n\'a pas été trouvé ou le lien est périmé');
            }
            else
            {
                return new UserTable($retour);
            }
        }
        throw new \InvalidArgumentException('L\'adresse est inccorecte');
    }
    public function findByValidAccountHash($key)
    {
        if(isset($key))
        {
            $retour= $this->findByField(self::VALID_ACCOUNT_HASH,$key);
            if(!$retour)
            {
                throw new \InvalidArgumentException('Le compte n\'a pas été trouvé');
            }
            else
            {
                return new UserTable($retour);
            }
        }
        throw new \InvalidArgumentException('L\'adresse est inccorecte');
    }

    public function updateUser(UserTable $user):bool
    {
        return $this->update($user->getId(),
            [
                self::IDENTIFIANT=>$user->getIdentifiant(),
                self::MAIL=>$user->getMail(),
                self::PWD=>$user->getPassword(),
                self::FORGET_HASH=>$user->getForgetHash(),
                self::FORGET_DATE=>$user->getForgetDate(),
                self::VALID_ACCOUNT_HASH=>$user->getValidAccountHash(),
                self::VALID_ACCOUNT_DATE=>$user->getValidAccountDate(),
                self::ACCREDITATION=>$user->getAccreditation()
            ]);
    }
    public function createUser(UserTable $user):UserTable
    {
        $id= $this->create(
            [
                self::IDENTIFIANT=>$user->getIdentifiant(),
                self::MAIL=>$user->getMail(),
                self::PWD=>$user->getPassword(),
                self::FORGET_HASH=>$user->getForgetHash(),
                self::FORGET_DATE=>$user->getForgetDate(),
                self::VALID_ACCOUNT_HASH=>$user->getValidAccountHash(),
                self::VALID_ACCOUNT_DATE=>$user->getValidAccountDate(),
                self::ACCREDITATION=>$user->getAccreditation()
            ]);
        if(!$id)
        {
            throw new \InvalidArgumentException('Erreur lors de la création de l\'utilisateur');
        }
        $user->setId($id);
        return $user;
    }
    public function NewUser($identifiant, $mail,$pwd):UserTable
    {
        return new UserTable
                ([
                    self::IDENTIFIANT=>$identifiant,
                    self::MAIL=>$mail,
                    self::PWD=>Auth::password_crypt($pwd),
                    self::FORGET_HASH=>null,
                    self::FORGET_DATE=>null,
                    self::VALID_ACCOUNT_HASH=>Auth::str_random(),
                    self::VALID_ACCOUNT_DATE=>null,
                    self::ACCREDITATION=>0
                ]);
    }

    public function isValidAccount(UserTable $user)
    {
        if(is_null( $user->getValidAccountDate() ))
        {
            throw new \InvalidArgumentException('Le compte n\'est pas validé');
        }
    }
    public function validAccountReset(UserTable $user)
    {
        $user->setValidAccountHash(null);
        $user->setValidAccountDate(date(ES_NOW));

        if(! $this->updateUser($user))
        {
            throw new \InvalidArgumentException('Erreur lors de la validation du compte');
        }
    }
    public function forgetInit(UserTable $user)
    {
        $user->setForgetHash(Auth::str_random());
        $user->setForgetDate(date(ES_NOW));

        if(! $this->updateUser($user))
        {
            throw new \InvalidArgumentException('Erreur lors de l\'initialisation du forget');
        }
    }
    public function forgetReset(UserTable $user,$pwd)
    {
        $user->setForgetHash(null);
        $user->setForgetDate(date(ES_NOW));
        $user->setPassword (Auth::password_crypt($pwd));

        if(! $this->updateUser($user))
        {
            throw new \InvalidArgumentException('Erreur lors de la réinitialisation du forget');
        }
    }

    #region MAIL
    public function sendMailSignup(UserTable $user)
    {
        $content='<a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.validaccount/' . $user->getValidAccountHash() . '">Lien pour activer le compte</a>';
        if(! mail($user->getMail(),'Validation du compte',$content))
        {
            throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail');
        }
    }
    public function sendMailPwdForget(UserTable $user)
    {
        $content='<a href="' . ES_ROOT_PATH_WEB_INDEX . 'user.pwdforgetchange/' . $user->getForgetHash() . '">Lien pour modifier le mot de passe</a>';
        if(!mail($user->getMail(),'Réinitialisation du mot de passe',$content))
        {
            throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail');
        }
    }
    #endregion
}
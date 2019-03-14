<?php

namespace ES\App\Modules\User\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\User\Model\UserTable;
Use ES\Core\Toolbox\Auth;
use ES\Core\Mail\Mail;
use ES\Core\Upload\JpgUpload;

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
    public function findUserByLoginOrMail($value):UserTable
    {
        return $this->query($this->_queryBuilder
            ->select('*')
            ->from(self::$table)
            ->where(' (' . UserTable::IDENTIFIANT . '=:params1 OR '. UserTable::MAIL .'=:params2)')
            ->render(),
             [
            'params1'=>$value,
            'params2'=>$value]
            ,
            true,true);
    }
    public function findByForgetHash($key) :UserTable
    {
        return $this->query($this->_queryBuilder
            ->select('*')
            ->from(self::$table)
            ->where(UserTable::FORGET_HASH . '=:params1 AND DATEDIFF( NOW() ,' . UserTable::FORGET_DATE. ')<1')
            ->render(),
              ['params1'=>$key],
            true,true);
    }
    public function findByValidAccountHash($key):UserTable
    {
        return $this->findByField(UserTable::VALID_ACCOUNT_HASH,$key);
    }
    #endregion

    #region get *
    public function getUsers($key=null,$value=null)
    {
        $this->_queryBuilder
             ->select('*')
             ->from(self::$table);

        $arguments=null;

        if($key=='validaccount') {
            $this->_queryBuilder->where(' u_valid_account_date is ' . ($value==1?'not':'') .' null');
        } else if(isset($key) && isset($value)) {
            $this->_queryBuilder->where('u_' . $key .'=:value ');
            $arguments=['value'=>$value];
        }
        return $this->query($this->_queryBuilder->orderBy(static::$order_by)->render(),$arguments);
    }
    #endregion
    #region count
    public function countUsers($key=null,$value=null)
    {
        $this->_queryBuilder
             ->select('count(*)')
             ->from(self::$table);

        $arguments=null;

        if($key=='validaccount' ) {
            $this->_queryBuilder->where(' u_valid_account_date is ' . ($value==1?'not':'') .' null ;');
        } else if(isset($key) && isset($value)) {
            $this->_queryBuilder->where('u_' . $key .'=:value ');
            $arguments=['value'=>$value];
        }

        return $this->query($this->_queryBuilder->render(),$arguments,true,false)['count(*)'];
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

            $imageSource=ES_ROOT_PATH_FAT . 'Public/images/avatar/model.jpg';
            $imageDestination=ES_ROOT_PATH_FAT . 'Public/images/avatar/' . $retour . '.jpg';
            \copy($imageSource,$imageDestination);

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
        $user->setUserRole(ES_USER_ROLE_VISITEUR);
        $user->setActif(1);
        $user->setActifDate(\date(ES_NOW)) ;
        return $user;
    }


    public function createPicture($key,$id)
    {
        $jpgUpload=new JpgUpload('avatar');
        return $jpgUpload->createMiniature($key,$id);
    }

    public function validAccountReset(UserTable $user):bool
    {
        $user->setValidAccountHash(null);
        $user->setValidAccountDate(\date(ES_NOW));

        return $this->updateUser($user);
    }
    public function changeActifOfUser(UserTable $user):bool
    {
        $value=$user->getActif()=='1'?'0':'1';
        $user->setActif($value);
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
        try {
            $content='Bonjour,<br/><br/>
                Vous vous êtes récemment inscrit sur notre site.<br/>
                Afin de finaliser l\'inscription et de valider votre compte,
                <a href="' . ES_ROOT_PATH_WEB_INDEX . 'user/validaccount/' . $user->getValidAccountHash() . '">cliquez ici</a>
                ou collez le lien suivant dans votre navigateur ' . ES_ROOT_PATH_WEB_INDEX . 'user/validaccount/' . $user->getValidAccountHash() . '
                <br/><br/>Merci d\'utiliser ' . ES_APPLICATION_NOM;
            $mail=new Mail();
            if(! $mail->send($user->getMail(),'Validation du compte',$content)) {
                throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail.');
            }
            return true;
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage());
            return false;
        }
    }
    public function sendMailPwdForget(UserTable $user) :bool
    {
        $content='Bonjour,<br/><br/>

                    Vous avez récemment sollicité une réinitialisation de votre mot de passe.<br/>
                    Pour modifier votre mot de passe de connexion, <a href="' . ES_ROOT_PATH_WEB_INDEX . 'user/pwdforgetchange/' . $user->getForgetHash() . '">
                    cliquez ici</a> ou collez le lien suivant dans votre navigateur : ' . ES_ROOT_PATH_WEB_INDEX . 'user/pwdforgetchange/' . $user->getForgetHash(). '

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
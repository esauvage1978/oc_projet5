<?php

namespace ES\App\Modules\User\Model;
use ES\Core\Mail\Mail;

/**
 * UserMail short summary.
 *
 * UserMail description.
 *
 * @version 1.0
 * @author ragus
 */
class UserMail
{
    private $_mail;
    public function __construct()
    {
        $this->_mail=new Mail();
    }
    #region MAIL
    public function sendMailSignup(UserTable $user):bool
    {
        try {
            $content='Bonjour,<br/><br/>
                Vous vous êtes récemment inscrit sur notre site.<br/>
                Afin de finaliser l\'inscription et de valider votre compte,
                <a href="' . ES_ROOT_PATH_WEB_INDEX . 'user/user/validaccount/' . $user->getValidAccountHash() . '">cliquez ici</a>
                ou collez le lien suivant dans votre navigateur ' . ES_ROOT_PATH_WEB_INDEX . 'user/user/validaccount/' . $user->getValidAccountHash() . '
                <br/><br/>Merci d\'utiliser ' . ES_APPLICATION_NOM;

            return $this->_mail->send($user->getMail(),'Validation du compte',$content);
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

        return  $this->_mail->send($user->getMail(),'Réinitialisation du mot de passe',$content);
    }

    #endregion
}
<?php

namespace ES\App\Modules\User\Controler;

use ES\App\Modules\Shared\Controler\SharedControler;
use ES\App\Modules\User\Model\UserManager;
use ES\App\Modules\User\Model\UserTable;
use ES\App\Modules\User\Form\UserForm;
Use ES\Core\ToolBox\Request;
Use ES\Core\ToolBox\Auth;

/**
 * userControler short summary.
 *
 * userControler description.
 *
 * @version 1.0
 * @author ragus
 */
class UserControler extends SharedControler
{
    protected static $module='User';

    public function connexion()
    {
        $datas=$this->_request;
        $form =new UserForm($datas);

        try
        {
            //Contrôle de l'accès à la page
            $this->valideAccessPage(parent::DECONNECTE);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView;
            exit;
        }
        try
        {

            if($datas->hasPost())
            {
                //récupération des paramètres
                $login=$datas->getPostValue('login');
                $pwd=$datas->getPostValue('pwd');

                //initialisation de la class manager
                $userManager=new UserManager ();

                //initialisation de la class UserTable
                $user=$userManager->findUserByLogin($login);

                //Vérification du mot de passe
                $userManager->passwordCompare($pwd,$user->getPassword(),true);

                //Contrôle si le compte est valide
                $userManager->isValidAccount($user);

                //Création de la variable de session
                $userManager->connect($user,$this->_request);

                $this->AccueilView();
                exit;
            }
            $this->connexionView($form);

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->connexionView($form);
            exit;
        }

    }
    public function deconnexion()
    {
        //initialisation de la class manager
        $userManager=new UserManager ();

        //deconection
        $userManager->disconnect($this->_request);

        //retour à la page d'accueil
        $this->AccueilView();
    }

    public function pwdforget()
    {
        $datas=$this->_request;
        $form =new UserForm($datas);

        try
        {
            //Contrôle de l'accès à la page
            $this->valideAccessPage(parent::DECONNECTE);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView;
            exit;
        }
        try
        {
            if($datas->hasPost())
            {
                //récupération des paramètres
                $login=$datas->getPostValue('login');

                //initialisation de la class manager
                $userManager=new UserManager ();

                //initialisation de la class UserTable
                $user=$userManager->findUserByLogin($login);

                //initialisation de la table pour forget
                $userManager->ForgetInit($user);

                //envoie du mail
                $userManager->sendMailPwdForget($user);

                //message pour l'utilisateur
                $this->flash->writeInfo( 'Un mail de réinitialisation a été envoyé');

                //Retour à la page d'accueil
                $this->AccueilView();

                exit;
            }

            $this->pwdForgetView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->pwdForgetView($form);
            exit;
        }

    }

    public function pwdforgetchange()
    {


        try
        {
            //Contrôle de l'accès à la page
            $this->valideAccessPage(parent::DECONNECTE);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView;
            exit;
        }
        try
        {

            //initialisation de la class manager
            $userManager=new UserManager ();

            $datas=$this->_request;

            if($datas->hasPost())
            {
                //récupération des paramètres
                $form =new UserForm($datas);
                $forgetHash=$datas->getPostValue('hash');
                $pwd=$datas->getPostValue('pwd');
                $pwdConfirmation=$datas->getPostValue('pwdConfirmation');

                //initialisation de la class UserTable
                $user=$userManager->findByForgetHash($forgetHash);

                //comparaison des mots de passe saisis
                $userManager ->passwordCompare ($pwd,$pwdConfirmation);

                //reset de la table utilisateur
                $userManager->forgetReset($user,$pwd);

                //message d'info à l'utilisateur
                $this->flash->writeSucces( 'Mot de passe modifié');

                //retour à la page de connexion
                $this->connexionView($form);

                exit;
            }
            else
            {
                //récupération des paramètres
                $hash=$this->_request->getGetValue('mot');

                //contrôle du hash
                if(!isset($hash) || empty($hash))
                {
                    $this->connexionView(new UserForm($datas));
                    exit;
                }

                //initialisation de la class UserTable
                $user=$userManager->findByForgetHash($hash);

                //initialisation du formulaire
                $form =new UserForm(['hash'=>$hash]);
            }
            $this->pwdForgetChangeView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->connexion();
            exit;
        }

    }

    public function modify()
    {
        $datas=$this->_request;
        //pour le moment, modification par le owner uniquement
        try
        {
            //Contrôle de l'accès à la page
            $this->valideAccessPage(parent::CONNECTE);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
            exit;
        }

        //Instanciation de la class UserManager
        $userManager=new UserManager ();

        //récupère le User connecté
        $userConnect=$userManager->getUserConnect($this->_request);

        try
        {
            if($datas->hasPost())
            {
                $form =new UserForm($datas);

                //récupération des paramètres
                $identifiant=$datas->getPostValue('identifiant');
                $mail=$datas->getPostValue('mail');
                $idHidden=$datas->getPostValue('idHidden');

                //vérification si user connecté ou administrateur
                $this->valideAccessPageOwnerOrAdmin($userConnect,$idHidden);

                //récupération de l'userTable
                $user=$userManager->findById ($idHidden);

                //Contrôle de l'unicité de l'identifiant
                $userManager->identifiantExist($identifiant,$user->getId());

                //Contrôle de l'unicité du mail
                $userManager->mailExist ($mail,$user->getId());

                //MAJ de l'utilisateur
                $user->setMail($mail);
                $user->setIdentifiant($identifiant);
                $user=$userManager->updateUser($user);

                //Information de l'utilisateur
                $this->flash->writeSucces ("Utilisateur mis à jour") ;

                //Retour page d'accueil
                $this->AccueilView();

                exit;
            }
            else
            {
                //récupération des paramètres
                $idHidden=$this->_request->getGetValue('p');

                //instanciation de la class UserTable
                if(!isset($idHidden) || empty($idHidden))
                {
                    //Si paramètre non présent, récupération de celui de l'utilisateur courant
                    $user=$userConnect;

                }
                else
                {
                    //vérification si user connecté ou administrateur
                    $this->valideAccessPageOwnerOrAdmin($userConnect,$idHidden);

                    //récupération de l'userTable
                    $user=$userManager->findById ($idHidden);
                }

                //Initialisation du formulaire
                $form =new UserForm([
                    'idHidden'=>$user->getId(),
                    'identifiant'=>$user->getIdentifiant(),
                    'mail'=>$user->getMail()
                    ]);

            }
            $this->modifyView($form);

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
            exit;
        }
    }

    public function validaccount()
    {
        try
        {
            //Contrôle de l'accès à la page
            $this->valideAccessPage(parent::DECONNECTE);

            //récupération des paramètres
            $hash=$this->_request->getGetValue('mot');

            //initialisation de la class manager
            $userManager=new UserManager();

            //initialisation de la class UserTable
            $user=$userManager->findByValidAccountHash($hash);

            //Validation du compte (si date présente et hash vide, compte validé)
            $userManager->validAccountReset($user);

            //affichage d'un message d'information
            $this->flash->writeInfo('Votre compte est validé');

            //Retour à la page de connexion
            $this->connexion();

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
            exit;
        }
    }

    public function signup()
    {
        $datas=$this->_request;
        $form =new UserForm($datas);
        try
        {
            //Contrôle de l'accès à la page
            $this->valideAccessPage(parent::DECONNECTE);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView;
            exit;
        }
        try
        {
            if($datas->hasPost())
            {
                //récupération des paramètres
                $identifiant=$datas->getPostValue('identifiant');
                $mail=$datas->getPostValue('mail',Request::TYPE_MAIL);
                $pwd=$datas->getPostValue('pwd');
                $pwdConfirmation=$datas->getPostValue('pwdConfirmation');

                //initialisation de la class manager
                $userManager=new UserManager ();

                //initialisation de la class UserTable
                $user=$userManager->NewUser ($identifiant,$mail,$pwd);

                //Comparaison des mots de passe
                $userManager->passwordCompare($pwd,$pwdConfirmation);

                //Contrôle de l'unicité de l'identifiant
                $userManager->identifiantExist($identifiant);

                //Contrôle de l'unicité du mail
                $userManager->mailExist ($mail);

                //création de l'utilisateur
                $user=$userManager->createUser($user);

                //Envoi du mail
                $userManager->sendMailSignup ($user);

                //Information de l'utilisateur
                $this->flash->writeSucces ("Utilisateur créé, un mail a été envoyé pour valider l'inscription") ;

                //Retour page d'accueil
                $this->AccueilView();

                exit;
            }
            $this->signupView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->signupView($form);
            exit;
        }
    }

    #region VIEW
    private function signupView($form)
    {
        $this->view('SignupView',
            [
                'title'=>'Inscription',
                'form'=>$form
            ]);
    }
    private function connexionView($form)
    {
        $this->view('ConnexionView',
            [
                'title'=>'Connexion',
                'form'=>$form
            ]);
    }
    private function modifyView($form)
    {
        $this->view('ModifyView',
            [
                'title'=>'Modification des informations d\'un utilisateur',
                'form'=>$form
            ]);
    }
    private function pwdForgetView($form)
    {
        $this->view('PwdForgetView',
            [
                'title'=>'Mot de passe oublié ?',
                'form'=>$form
            ]);
    }
    private function pwdForgetChangeView($form)
    {
        $this->view('PwdForgetChangeView',
        [
            'title'=>'Modification du mot de passe',
            'form'=>$form
        ]);
    }
    private function pwdChangeView($form)
    {
        $this->view('PwdChangeView',
        [
            'title'=>'Modification du mot de passe',
            'form'=>$form
        ]);
    }
    #endregion
}
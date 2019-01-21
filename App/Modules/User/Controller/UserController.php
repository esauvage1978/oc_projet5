<?php

namespace ES\App\Modules\User\Controller;

use ES\App\Modules\Shared\Controller\SharedController;
use ES\App\Modules\User\Model\UserManager;
use ES\App\Modules\User\Form\UserPwdChangeForm;
use ES\App\Modules\User\Form\UserConnexionForm;
use ES\App\Modules\User\Form\UserForgetForm;
use ES\App\Modules\User\Form\UserSignupForm;
use ES\App\Modules\User\Form\UserForgetChangeForm;
use ES\App\Modules\User\Form\UserModifyForm;
use ES\App\Modules\User\Form\WebControls\InputHash;
use ES\App\Modules\User\Form\WebControls\InputIdHidden;
use ES\App\Modules\User\Form\WebControls\InputIdentifiant;
use ES\App\Modules\User\Form\WebControls\InputMail;
use ES\App\Modules\User\Form\WebControls\SelectAccreditation;
use ES\App\Modules\User\Form\WebControls\CheckboxActif;
Use ES\Core\ToolBox\Auth;

/**
 * userController short summary.
 *
 * userController description.
 *
 * @version 1.0
 * @author ragus
 */
class UserController extends SharedController
{
    static $module='User';
    private $_userManager;

    public function __construct()
    {
        parent::__construct ();
        $this->_userManager=new UserManager();
    }

    public function connexion()
    {

        $form =new UserConnexionForm($this->_request);

        try
        {

            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::DECONNECTE)) {
                $this->AccueilView (true);
            }


            if($this->_request->hasPost()) {


                //contrôle si les champs du formulaire sont renseignés
                $form->check() ||
                     $this->connexionView ($form,true);

                //Vérification
                $user=$this->_userManager->findUserByLogin($form->text($form::LOGIN));

                    //Recherche du login et du mot de passe
                if( !$user->hasId() ||
                    !Auth::passwordCompare(
                        $form->text($form::SECRET),
                        $user->getPassword(),true)) {

                    $this->flash->writeError('Informations utilisateurs incorrectes');
                    $this->connexionView ($form,true);
                }

                //Contrôle si le compte est valide
                if (!$this->_userManager->isValidAccount($user))  {

                    $this->_userManager->sendMailSignup($user);
                    $this->flash->writeWarning('Vous n\'avez pas validé votre compte. Le mail d\'activation est renvoyé.');
                    $this->AccueilView(true);
                }
                else if ($user->getActif()=='0')  {

                    $this->flash->writeWarning('Votre compte a été suspendu par un gestionnaire.');
                    $this->AccueilView(true);
                } else {
                    //Création de la variable de session
                    $this->_userManager->connect($user,$this->_request);
                    $this->AccueilView();
                    return;
                }
            }

            $this->connexionView ($form);

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
            return;
        }

    }

    public function list()
    {

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::CONNECTE)) {
                $this->AccueilView(true) ;
            }

            //récupère le User connecté
            $userConnect=$this->_userManager->getUserConnect($this->_request);

            $list=$this->_userManager->getAll();


            $this->listView($userConnect,$list,true);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }

    public function deconnexion()
    {
        //deconection
        $this->_userManager->disconnect($this->_request);

        //retour à la page d'accueil
        $this->AccueilView();
    }

    public function pwdforget()
    {
        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::DECONNECTE)) {
                $this->AccueilView(true) ;
            }


            $form =new UserForgetForm($this->_request);



            if($this->_request->hasPost())
            {

                //contrôle si les champs du formulaire sont renseignés
                if(!$form->check()) {
                    $this->pwdForgetView($form,true);
                }


                //initialisation de la class manager

                //récupération de l'utilisateur par rapport au login, si non trouvé $user vide
                $user=$this->_userManager->findUserByLogin($form->text($form::LOGIN));

                if ($user->hasId() &&
                    $this->_userManager->forgetInit($user)) {

                    //message pour l'utilisateur
                    $this->flash->writeInfo( 'Un mail de réinitialisation a été envoyé');

                    //Retour à la page d'accueil
                    $this->connexion();

                } else {

                    $form->controls[$form::LOGIN]->setIsInvalid('Ces informations sont incorrectes');
                    $this->pwdForgetView($form);

                }
            } else {

                $this->pwdForgetView($form);

            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
            return;
        }

    }
    public function modify()
    {
        //pour le moment, modification par le owner uniquement
        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::CONNECTE)) {
                $this->AccueilView(true) ;
            }

            //récupère le User connecté
            $userConnect=$this->_userManager->getUserConnect($this->_request);

            if($this->_request->hasPost())
            {
                $form =new UserModifyForm($this->_request);

                if( !$this->valideAccessPageOwnerOrGestionnaire($userConnect,$form->text($form::ID_HIDDEN))) {

                    $this->AccueilView (true);
                }

                //vérification si user connecté ou administrateur
                if (!$form->check() ) {

                    $this->modifyView($form,$userConnect,true);
                }

                //récupération de l'userTable
                $user=$this->_userManager->findById ($form->text($form::ID_HIDDEN));


                if(!$user->hasId() ||
                $this->_userManager->identifiantExist($form->text($form::IDENTIFIANT),
                                                            $user->getId()) ||
                $this->_userManager->mailExist ($form->text($form::MAIL),
                                                            $user->getId()))
                {
                    $this->flash->writeError('L\'identifiant ou le mail existe déjà.');
                    //récupération de l'userTable

                } else {
                    $user->setMail($form->text($form::MAIL));
                    $user->setIdentifiant($form->text($form::IDENTIFIANT));
                    if($userConnect->getAccreditation()=='4' ) {
                        $user->setAccreditation ($form->text($form::ACCREDITATION));

                        if(($user->getActif()=='0' && $form->text($form::ACTIF)=='on') ||
                            ($user->getActif()=='1' && $form->text($form::ACTIF)==null))
                            $this->_userManager->changeActifOfUser ($user);
                    }

                    $user=$this->_userManager->updateUser($user);

                    //Information de l'utilisateur
                    $this->flash->writeSucces ("Utilisateur mis à jour") ;

                }
                $this->modifyView($form,$userConnect,true);
            }
            else
            {
                //récupération des paramètres
                if ($this->_request->hasGetValue('p')) {
                    $idHidden=$this->_request->getGetValue('p');
                    if (!$this->valideAccessPageOwnerOrGestionnaire($userConnect,$idHidden)) {

                        //récupération de l'userTable
                        $this->AccueilView(true);
                        die();

                    }
                    $user=$this->_userManager->findById ($idHidden);

                } else {
                    $user=$userConnect;
                }

                //Initialisation du formulaire
                $form =new UserModifyForm([
                    InputIdHidden::NAME=>$user->getId(),
                    InputIdentifiant::NAME=>$user->getIdentifiant(),
                    InputMail::NAME=>$user->getMail(),
                    SelectAccreditation::NAME=>$user->getAccreditation(),
                    CheckboxActif::NAME=>$user->getActif()
                    ]);

                $form->controls[$form::ACTIF]->setLabel($user->getActifLabel());

                $this->modifyView($form,$userConnect,true);
            }


        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }

    public function pwdforgetchange()
    {

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::DECONNECTE)) {
                $this->AccueilView(true) ;
            }


            if($this->_request->hasPost())
            {
                //récupération des paramètres
                $form =new UserForgetChangeForm($this->_request);

                //contrôle si les champs du formulaire sont renseignés
                if(!$form->check())
                {
                    $this->pwdForgetChangeView($form,true);
                }

                //initialisation de la class UserTable
                $user=$this->_userManager->findByForgetHash($form->text($form::HASH));

                if($user->hasId() &&
                    $this->_userManager->forgetReset($user,$form->text($form::SECRET_NEW))) {

                    //message d'info à l'utilisateur
                    $this->flash->writeSucces( 'Mot de passe modifié');

                    //retour à la page de connexion
                    $this->connexion();
                    exit;
                } else {
                    $this->flash->writeError( 'Données incorrectes');
                    $this->AccueilView (true);
                }

            }
            else
            {
                //récupération des paramètres
                $hash=$this->_request->getGetValue('mot');

                //contrôle du hash
                if( empty($hash))
                {
                    $this->connexion();
                    return;
                }

                //initialisation de la class UserTable
                $user=$this->_userManager->findByForgetHash($hash) ||
                    $this->AccueilView (true);

                //initialisation du formulaire
                $form =new UserForgetChangeForm([InputHash::NAME=>$hash]);
            }
            $this->pwdForgetChangeView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->accueil();
            exit;
        }

    }

    public function pwdchange()
    {

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::CONNECTE)) {
                $this->AccueilView(true);
            }


            //initialisation de la form

            $form =new UserPwdChangeForm($this->_request);


            //récupère le User connecté
            $userConnect=$this->_userManager->getUserConnect($this->_request);


            if($this->_request->hasPost())
            {
                if(!$form->check())
                {
                    $this->pwdChangeView ($form,true);
                }

                //comparaison des mots de passe saisis
                if(!Auth::passwordCompare ($form->text($form::SECRET_OLD) ,
                    $userConnect->getPassword()))
                {
                    $form->controls[$form::SECRET_OLD]->setIsInvalid( 'L\'ancien mot de passe est incorrect');
                    $this->pwdChangeView ($form,true);
                }

                //reset de la table utilisateur
                $this->_userManager->updatePassword($userConnect,
                   $form->text($form::SECRET_NEW));

                //message d'info à l'utilisateur
                $this->flash->writeSucces( 'Mot de passe modifié');

                //retour à la page de connexion
                $this->AccueilView(true);

            }

            $this->pwdChangeView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView ();
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


            //initialisation de la class UserTable
            $user=$this->_userManager->findByValidAccountHash($hash);

            if($user->hasId()) {
                //Validation du compte (si date présente et hash vide, compte validé)
                $this->_userManager->validAccountReset($user);

                //affichage d'un message d'information
                $this->flash->writeInfo('Votre compte est validé');

                //Retour à la page de connexion
                $this->connexion();
            } else {
                $this->flash->writeInfo('Lien invalide.');
                $this->AccueilView();
            }

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

        $form =new UsersignupForm($this->_request);

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(parent::DECONNECTE)) {
                $this->AccueilView(true) ;
            }

            if($this->_request->hasPost()) {

                //Contrôle de la saisie de l'utilisateur
                if (!$form->check()) {

                    $this->signupView($form,true);

                }


                //initialisation de la class UserTable
                $user=$this->_userManager->createUser(
                    $form->text($form::IDENTIFIANT),
                    $form->text($form::MAIL),
                    $form->text($form::SECRET_NEW));



                if($user->hasId() &&                     //Envoi du mail
                    $this->_userManager->sendMailSignup ($user)) {
                    //Information de l'utilisateur
                    $this->flash->writeSucces ("Utilisateur créé, un mail a été envoyé pour valider l'inscription") ;

                    //Retour page d'accueil
                    $this->AccueilView(true);

                } else {

                    $this->flash->writeError ('Erreur lors de la création. Identifiant ou mail déjà enregistré.');
                    $this->signupView($form);
                }

            } else {
            $this->signupView($form);
            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }

    #region VIEW
    private function signupView($form,$exit=false)
    {
        $this->userView('Inscription',
            'SignupView',
            $form,
            $exit);
    }
    private function connexionView($form,$exit=false)
    {
        $this->userView('Connexion',
            'ConnexionView',
            $form,
            $exit);
    }
    private function listView($userConnect,$list,$exit=false)
    {
        $this->userConnectView('Liste des utilisateurs',
            'ListView',
            $list,
            $userConnect,
            $exit);
    }
    private function modifyView($form,$userConnect,$exit=false)
    {
        if($userConnect->getAccreditation()!='4' ) {
            $form->controls[$form::ACCREDITATION]->setDisabled();
            $form->controls[$form::ACTIF]->setDisabled();
         }

        $this->userConnectView('Modification des données de l\'utilisateur',
            'ModifyView',
            $form,
            $userConnect,
            $exit);
    }
    private function pwdForgetView($form,$exit=false)
    {
        $this->userView('Mot de passe oublié ?',
            'PwdForgetView',
            $form,
            $exit);
    }
    private function pwdForgetChangeView($form,$exit=false)
    {
        $this->userView('Récupération du mot de passe',
            'PwdForgetChangeView',
            $form,
            $exit);
    }
    private function pwdChangeView($form,$exit=false)
    {
        $this->userView('Modification du mot de passe',
            'PwdChangeView',
            $form,
            $exit);
    }
    private function  userView($title,$view,$form,$exit)
    {
        $this->view($view,
        [
            'title'=>$title,
            'form'=>$form
        ]);
        if($exit){exit;}
    }
    private function  userConnectView($title,$view,$form,$userConnect,$exit)
    {
        $this->view($view,
        [
            'title'=>$title,
            'form'=>$form,
            'userConnect'=>$userConnect
        ]);
        if($exit){exit;}
    }
    #endregion
}
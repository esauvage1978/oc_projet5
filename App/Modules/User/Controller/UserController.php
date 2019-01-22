<?php

namespace ES\App\Modules\User\Controller;

use ES\App\Modules\User\Model\UserManager;
use ES\App\Modules\User\Model\UserConnect;
use ES\App\Modules\Shared\Controller\restrictControler;
use \ES\Core\Controller\AbstractController;
Use ES\Core\Toolbox\Auth;

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


/**
 * userController short summary.
 *
 * userController description.
 *
 * @version 1.0
 * @author ragus
 */
class UserController extends AbstractController
{
    static $module='User';
    private $_userManager;
    private $_userConnect=null;
    private $_userConnected;

    use restrictControler;

    public function __construct()
    {
        parent::__construct ();
        $this->_userManager=new UserManager();
        $this->_userConnect=new UserConnect($this->_request);
        if($this->_userConnect->isConnect () ) {
            $this->_userConnected=$this->_userConnect->getUserConnect();
        }

    }

    #region CONNEXION

    public function connexion()
    {

        $form =new UserConnexionForm($this->_request);

        try
        {

            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(false)) {
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
                    $this->_userConnect->connect($user);
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
    private function connexionView($form,$exit=false)
    {
        $this->userView('ConnexionView','Connexion',$form,false,$exit);
    }

    #endregion

    #region MODIFY
    private function modifyView($form,$exit=false)
    {
        // restriction des contrôle si l'utilisateur n'est pas le gestionnaire
        if($this->_userConnected->getAccreditation()!=ES_GESTIONNAIRE ) {
            $form->controls[$form::ACCREDITATION]->setDisabled();
            $form->controls[$form::ACTIF]->setDisabled();
        }

        $this->userView('ModifyView','Modification des données de l\'utilisateur',
            $form,
            true,
            $exit);
    }
    public function modify($paramId=null)
    {

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(true,ES_VISITEUR)) {
                $this->AccueilView(true) ;
            }


            if($this->_request->hasPost())
            {
                $this->modifyAfterPost();
            }
            else
            {
                $this->modifyBeforePost ($paramId);
            }


        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }
    public function modifyAfterPost()
    {
        $form =new UserModifyForm($this->_request);

        if( !$this->valideAccessPageOwnerOrGestionnaire($this->_userConnected,
                            $form->text($form::ID_HIDDEN))) {

            $this->AccueilView (true);
        }

        //vérification si user connecté ou administrateur
        if (!$form->check() ) {

            $this->modifyView($form,$this->_userConnected,true);
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
            if($this->_userConnected->getAccreditation()=='4' ) {
                $user->setAccreditation ($form->text($form::ACCREDITATION));

                if(($user->getActif()=='0' && $form->text($form::ACTIF)=='on') ||
                    ($user->getActif()=='1' && $form->text($form::ACTIF)==null)) {
                    $this->_userManager->changeActifOfUser ($user);
                }
            }

            $user=$this->_userManager->updateUser($user);

            //Information de l'utilisateur
            $this->flash->writeSucces ("Utilisateur mis à jour") ;

        }
        $this->modifyView($form,true);
    }
    public function modifyBeforePost($paramId=null)
    {
        //récupération des paramètres
        if(isset($paramId)) {

            if (!$this->valideAccessPageOwnerOrGestionnaire($this->_userConnected,$paramId)) {

                //récupération de l'userTable
                $this->AccueilView(true);
                die();

            }
            $user=$this->_userManager->findById ($paramId);

            if(!$user->hasId())
            {
                $this->flash->writeError ("Erreur au niveau de l'adresse") ;
                $this->AccueilView ();
            }


        } else {
            $user=$this->_userConnected;
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

        $this->modifyView($form,true);

    }
    #endregion

    #region VIEW







    private function  userView($view,$title,$form,$user,$exit)
    {
        $params=['title'=>$title,'form'=>$form];
        if($user)
        {
            $params['userConnect']=$this->_userConnected;
        }

        $this->view($view,$params);
        if($exit){exit;}
    }
    #endregion

    #region LIST
    public function list($filtre=null,$number=null)
    {

        try
        {
            //Accès aux personnes connectées et gestionnaire sinon TCHAO
            if(!$this->valideAccessPage(true, ES_GESTIONNAIRE)) {
                $this->AccueilView(true) ;
            }



            $list=null;
            if(isset($filtre) && isset($number)) {
                $filtreOK=['accreditation','actif'];
                if( in_array ($filtre,$filtreOK)) {
                        $list=$this->_userManager->getAll($filtre,$number);
                } else {
                        $list=$this->_userManager->getAll();
                }
            } else {
                $list=$this->_userManager->getAll();
            }



            $this->listView($list,true);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }
    }
        private function listView($list,$exit=false)
    {
        $this->userView('ListView','Liste des utilisateurs',
            $list,
            true,
            $exit);
    }

    #endregion

    #region DECONNEXION
    public function deconnexion()
    {
        //deconection
        $this->_userConnect->disconnect();

        //retour à la page d'accueil
        $this->AccueilView();
    }
    #endregion

    #region FORGET VIEW
    public function pwdforget()
    {
        try
        {
            //accessible aux utilisateurs non connecté
            if(!$this->valideAccessPage(false)) {
                $this->AccueilView(true) ;
            }

            $form =new UserForgetForm($this->_request);

            if($this->_request->hasPost())
            {

                //contrôle si les champs du formulaire sont renseignés
                if(!$form->check()) {
                    $this->pwdForgetView($form,true);
                }


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
    private function pwdForgetView($form,$exit=false)
    {
        $this->userView('PwdForgetView','Mot de passe oublié ?',

            $form,false,
            $exit);
    }
    #endregion

    #region FORGET CHANGE
    public function pwdforgetchange($hash=null)
    {

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(false)) {
                $this->AccueilView(true) ;
            }


            if($this->_request->hasPost())
            {
                //récupération des paramètres
                $form =new UserForgetChangeForm($this->_request);
                $this->pwdforgetchangeAfterPost($form);
            }
            else
            {
                $form=$this->pwdforgetchangeBeforePost($hash);
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
    public function pwdforgetchangeBeforePost($hash)
    {
        //contrôle du hash
        if( empty($hash)) {
            $this->flash->writeError( 'Adresse incorrectes');
            $this->AccueilView (true);
        }

        //initialisation de la class UserTable
        $user=$this->_userManager->findByForgetHash($hash) ;

        if(!$user->hasId()) {
            $this->flash->writeError( 'Les données sont incorrectes');
            $this->AccueilView (true);
        }

        //initialisation du formulaire
        return new UserForgetChangeForm([InputHash::NAME=>$hash]);
    }
    public function pwdforgetchangeAfterPost($form)
    {

        //contrôle si les champs du formulaire sont renseignés
        if(!$form->check()) {
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

    private function pwdForgetChangeView($form,$exit=false)
    {
        $this->userView('PwdForgetChangeView','Récupération du mot de passe',
            $form,false,$exit);
    }
    #endregion

    #region PWD CHANGE
    public function pwdchange()
    {

        try
        {
            //tout utilisateur peut changer son mot de passe
            if(!$this->valideAccessPage(true, ES_VISITEUR)) {
                $this->AccueilView(true);
            }

            $form =new UserPwdChangeForm($this->_request);


            if($this->_request->hasPost()) {
                $this->pwdChangeAfterPost($form);
            }

            $this->pwdChangeView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView ();
        }

    }
    public function pwdChangeAfterPost($form)
    {
        if(!$form->check())
        {
            $this->pwdChangeView ($form,true);
        }

        //comparaison des mots de passe saisis
        if(!Auth::passwordCompare ($form->text($form::SECRET_OLD) ,
            $this->_userConnected->getPassword()))
        {
            $form->controls[$form::SECRET_OLD]->setIsInvalid( 'L\'ancien mot de passe est incorrect');
            $this->pwdChangeView ($form,true);
        }

        //reset de la table utilisateur
        $this->_userManager->updatePassword($this->_userConnected,
           $form->text($form::SECRET_NEW));

        //message d'info à l'utilisateur
        $this->flash->writeSucces( 'Mot de passe modifié');

        //retour à la page de connexion
        $this->AccueilView(true);
    }
    private function pwdChangeView($form,$exit=false)
    {
        $this->userView('PwdChangeView','Changement du mot de passe',
            $form,true,
            $exit);
    }
    #endregion

    #region VALIDATION DU COMPTE
    public function validaccount($hash)
    {
        try
        {
            if(!$this->valideAccessPage(false)) {
                $this->AccueilView(true);
            }

            //recherche de l'utilisateur par rapport à la clé
            $user=$this->_userManager->findByValidAccountHash($hash);

            if($user->hasId()) {
                //Validation du compte (si date présente et hash vide, compte validé)
                $this->_userManager->validAccountReset($user);

                //affichage d'un message d'information
                $this->flash->writeInfo('Votre compte est validé');

                //Retour à la page de connexion
                $this->connexion();
            } else {
                $this->flash->writeError('Lien invalide.');
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
    #endregion

    #region CREATE ACCOUNT
    public function signup()
    {

        $form =new UsersignupForm($this->_request);

        try
        {
            //Un utilisateur connecté ne peut pas se reconnecté
            if(!$this->valideAccessPage(false)) {
                $this->AccueilView(true) ;
            }

            if($this->_request->hasPost()) {

                $this->signupAfterPost ($form);

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
    public function signupAfterPost($form)
    {
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

    }
    private function signupView($form,$exit=false)
    {
        $this->userView('SignupView','Inscription',$form,false,$exit);
    }

    #endregion


}
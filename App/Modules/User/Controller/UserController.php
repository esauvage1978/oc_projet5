<?php

namespace ES\App\Modules\User\Controller;

use ES\App\Modules\User\Model\ConnexionManager;
use ES\App\Modules\User\Model\UserManager;
Use ES\Core\Toolbox\Auth;

use ES\App\Modules\User\Form\UserPwdChangeForm;
use ES\App\Modules\User\Form\UserConnexionForm;
use ES\App\Modules\User\Form\UserForgetForm;
use ES\App\Modules\User\Form\UserSignupForm;
use ES\App\Modules\User\Form\UserForgetChangeForm;
use ES\App\Modules\User\Form\UserModifyForm;

use \ES\Core\Controller\AbstractController;
Use ES\Core\Toolbox\Request;
use ES\App\Modules\User\Model\UserConnect;

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


    public function __construct(UserConnect $userConnect,Request $request)
    {
        parent::__construct($userConnect,$request);
        $this->_userManager=new UserManager();
    }

    public function getWidgetDashboard():string
    {
        $users=[];

        $users[0]=[
            ES_DASHBOARD_TITRE=>'Total',
            ES_DASHBOARD_ICONE=>'ion-ios-people',
            ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers(),
            ES_DASHBOARD_CONTENT=>'Nombre total d\'utilisateur inscrit',
            ES_DASHBOARD_LINK=>'user.list'
        ];

        $users[1]=[
           ES_DASHBOARD_TITRE=>'non activé',
           ES_DASHBOARD_ICONE=>'ion-locked',
           ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers('validaccount',0),
           ES_DASHBOARD_CONTENT=>'Utilisateur n\'ayant pas validé leur compte',
           ES_DASHBOARD_LINK=>'user.list/validaccount/0'
       ];

        $users[2]=[
           ES_DASHBOARD_TITRE=>'Suspendu',
           ES_DASHBOARD_ICONE=>'ion-pause',
           ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers ('actif',0),
           ES_DASHBOARD_CONTENT=>'Utilisateur suspendu par un gestionnaire',
           ES_DASHBOARD_LINK=>'user/list/actif/0'
       ];

        $users[3]=[
           ES_DASHBOARD_TITRE=>ES_USER_ROLE[ES_USER_ROLE_GESTIONNAIRE],
           ES_DASHBOARD_ICONE=>'ion-university',
           ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers ('user_role',ES_USER_ROLE_GESTIONNAIRE),
           ES_DASHBOARD_CONTENT=>'Gestionnaire du site',
           ES_DASHBOARD_LINK=>'user/list/user_role/' .ES_USER_ROLE_GESTIONNAIRE
       ];
        $data=[
            'users'=>$users
            ];

        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboard.php';
        return $this->renderView->genererFichier($fichier, $data);
    }

    #region CONNEXION
    public function connexion()
    {
        //Si ip blacklisté -> accès denied et fin du programme
        $this->UserIsBlackList();

        $form =new UserConnexionForm($this->_request->getPost() );
        $connexion=new ConnexionManager();
        try
        {
            if($this->_request->hasPost()) {
                //contrôle si les champs du formulaire sont renseignés
                $form->check() || $this->connexionView ($form,true);

                //Vérification
                $user=$this->_userManager->findUserByLoginOrMail($form->getText($form::LOGIN));

                //si login non trouvé ou mauvais mot de passe
                if( !$user->hasId() ||
                !Auth::passwordCompare(
                    $form->getText($form::SECRET),
                    $user->getPassword(),true)) {

                    $this->flash->writeError(MSG_USER_BAD_DATA);

                    //traçage de la connexion ko
                    $connexion->addConnexion ( $_SERVER[ES_IP]);
                    $this->connexionView ($form,true);
                    // si compte non validé
                } elseif (!$user->isValidAccount()) {
                    $this->_userManager->sendMailSignup($user);
                    $this->flash->writeWarning(MSG_USER_NOT_ACTIVATE);
                //si compte désactivé par un gestionnaire
                } elseif ($user->getActif()=='0') {
                    $this->flash->writeWarning(MSG_USER_SUSPEND);
                } else {
                    //Création de la variable de session
                    $this->_userConnect->connect($user);
                }

                $connexion->addConnexion( $_SERVER[ES_IP],$user->getId());
                $this->AccueilView(true);
            }

            $this->connexionView($form);

        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);
        }

    }


    private function connexionView($form,$exit=false)
    {
        $this->userView('ConnexionView','Connexion',$form,false,$exit);
    }
    #endregion

    private function UserIsBlackList()
    {
        $connexionManager =new ConnexionManager();
        if ($connexionManager->IsBlackList( $_SERVER[ES_IP])) {
            header('location: ' .ES_ROOT_PATH_WEB . 'shared/accessdeniedmanyconnexion');
            exit;
        }
    }

    #region FORGET VIEW
    public function pwdforget()
    {
        $this->UserIsBlackList();
        try
        {
            $form =new UserForgetForm($this->_request->getPost() );

            if($this->_request->hasPost())
            {
                //contrôle si les champs du formulaire sont renseignés
                if(!$form->check()) {
                    $this->pwdForgetView($form,true);
                }

                //récupération de l'utilisateur par rapport au login, si non trouvé $user vide
                $user=$this->_userManager->findUserByLoginOrMail($form[$form::LOGIN]->getText());

                if ($user->hasId() &&
                    $this->_userManager->forgetInit($user)) {

                    //message pour l'utilisateur
                    $this->flash->writeInfo( 'Un mail de réinitialisation a été envoyé');

                    //Retour à la page d'accueil
                    $this->accueilView();

                } else {

                    $form[$form::LOGIN]->setIsInvalid(MSG_FORM_NOT_GOOD);
                    $this->pwdForgetView($form);

                }
            } else {

                $this->pwdForgetView($form);

            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);
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
            if($this->_request->hasPost())
            {
                //récupération des paramètres
                $form =new UserForgetChangeForm($this->_request->getPost());
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
            $this->errorCatchView($e->getMessage(),true);
        }

    }
    public function pwdforgetchangeBeforePost($hash)
    {
        //contrôle du hash
        if( empty($hash)) {
            $this->accessDeniedView('Adresse incorrectes',true);
        }

        //initialisation de la class UserTable
        $user=$this->_userManager->findByForgetHash($hash) ;

        if(!$user->hasId()) {
            $this->accessDeniedView('Les données sont incorrectes',true);
        }

        //initialisation du formulaire
        return new UserForgetChangeForm([UserForgetChangeForm::HASH=>$hash],false);
    }
    public function pwdforgetchangeAfterPost(UserForgetChangeForm $form)
    {

        //contrôle si les champs du formulaire sont renseignés
        if(!$form->check()) {
            $this->pwdForgetChangeView($form,true);
        }

        //initialisation de la class UserTable
        $user=$this->_userManager->findByForgetHash($form[$form::HASH]->getText());

        if($user->hasId() &&
            $this->_userManager->forgetReset($user,$form[$form::SECRET_NEW]->getText())) {

            //message d'info à l'utilisateur
            $this->flash->writeSucces( 'Mot de passe modifié');

            //retour à la page de connexion
            $this->accueilView();
            exit;
        } else {
            $this->errorCatchView('Données incorrectes',true);
        }

    }

    private function pwdForgetChangeView($form,$exit=false)
    {
        $this->userView('PwdForgetChangeView','Récupération du mot de passe',
            $form,false,$exit);
    }
    #endregion

    #region MODIFY
    private function modifyView(UserModifyForm $form,$exit=false)
    {
        // restriction des contrôle si l'utilisateur n'est pas le gestionnaire
        if(!$this->_userConnect->canAdministrator() ) {
            $form[$form::USER_ROLE]->disabled=true;
            $form[$form::ACTIF]->disabled=true;
        }

        $this->userView('ModifyView','Les données de l\'utilisateur',
            $form,
            true,
            $exit);
    }
    public function modify($paramId=null)
    {
        try {
            if($this->_request->hasPost())  {

                $this->modifyAfterPost();
            } else {
                $this->modifyBeforePost ($paramId);
            }
        } catch(\InvalidArgumentException $e) {
            $this->errorCatchView($e->getMessage(),true);
        }
    }
    public function modifyAfterPost()
    {

        $form =new UserModifyForm($this->_request->getPost());

        if( !$this->valideAccessPageOwnerOrGestionnaire($this->_userConnect->user,
                            $form[$form::ID_HIDDEN]->getText())) {

            $this->AccueilView (true);
        }

        //vérification si user connecté ou administrateur
        if (!$form->check() ) {

            $this->modifyView($form,$this->_userConnect->user,true);
        }

        //récupération de l'UserTable
        $user=$this->_userManager->findById ($form[$form::ID_HIDDEN]->getText());


        if(!$user->hasId() ||
        $this->_userManager->identifiantExist($form[$form::IDENTIFIANT]->getText(),
                                                    $user->getId()) ||
        $this->_userManager->mailExist ($form[$form::MAIL]->getText(),
                                                    $user->getId()))
        {
            $this->flash->writeError('L\'identifiant ou le mail existe déjà.');
            //récupération de l'UserTable

        } else {
            $user->setMail($form[$form::MAIL]->getText());
            $user->setIdentifiant($form[$form::IDENTIFIANT]->getText());
            if($this->_userConnect->user->getUserRole()=='4' ) {
                $user->setUserRole($form[$form::USER_ROLE]->getText());

                if(($user->getActif()=='0' && $form[$form::ACTIF]->getText()=='on') ||
                    ($user->getActif()=='1' && $form[$form::ACTIF]->getText()==null)) {
                    $this->_userManager->changeActifOfUser ($user);
                }
            }

            $retour=$this->_userManager->createPicture(
                $form[$form::FILE]->getName() ,$user->getId()) ;
            if(!empty($retour)) {
                $this->flash->writeError ($retour);
            }

            $this->_userManager->updateUser($user);

            $form[$form::ACTIF]->label=$user->getActifLabel();

            //Information de l'utilisateur
            $this->flash->writeSucces ("Utilisateur mis à jour") ;

        }
        $this->modifyView($form,true);
    }
    public function modifyBeforePost($paramId=null)
    {
        //récupération des paramètres
        if(isset($paramId)) {

            $this->valideAccessPageOwnerOrGestionnaire($paramId);

            $user=$this->_userManager->findById ($paramId);

            if(!$user->hasId())
            {
                $this->flash->writeError ("Erreur au niveau de l'adresse") ;
                $this->AccueilView ();
            }


        } else {
            $user=$this->_userConnect->user;
        }

        //Initialisation du formulaire
        $form =new UserModifyForm([
            UserModifyForm::ID_HIDDEN=>$user->getId(),
            UserModifyForm::IDENTIFIANT=>$user->getIdentifiant(),
            UserModifyForm::MAIL=>$user->getMail(),
            UserModifyForm::USER_ROLE=>$user->getUserRole(),
            UserModifyForm::ACTIF=>$user->getActif()
            ],false);

        $form[$form::ACTIF]->label=$user->getActifLabel();

        $this->modifyView($form,true);

    }
    #endregion

    #region VIEW

    private function  userView($view,$title,$form,$user,$exit)
    {
        $params=['title'=>$title,'form'=>$form];
        if($user)
        {
            $params['userConnect']=$this->_userConnect->user;
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
            $list=null;
            if(isset($filtre) && isset($number)) {
                $filtreOK=['user_role','actif','validaccount'];
                if( in_array ($filtre,$filtreOK)) {
                    $list=$this->_userManager->getUsers($filtre,$number);
                } else {
                    $list=$this->_userManager->getUsers();
                }
            } else {
                $list=$this->_userManager->getUsers();
            }

            $this->listView($list,true);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView( $e->getMessage(),true);
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



    #region PWD CHANGE
    public function pwdchange()
    {

        try
        {

            $form =new UserPwdChangeForm($this->_request->getPost());


            if($this->_request->hasPost()) {
                $this->pwdChangeAfterPost($form);
            }

            $this->pwdChangeView($form);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);

        }

    }
    public function pwdChangeAfterPost($form)
    {
        if(!$form->check())
        {
            $this->pwdChangeView ($form,true);
        }

        //comparaison des mots de passe saisis
        if(!Auth::passwordCompare ($form[$form::SECRET_OLD]->getText() ,
            $this->_userConnect->user->getPassword()))
        {
            $form[$form::SECRET_OLD]->setIsInvalid( 'L\'ancien mot de passe est incorrect');
            $this->pwdChangeView ($form,true);
        }

        //reset de la table utilisateur
        $this->_userManager->updatePassword($this->_userConnect->user,
           $form[$form::SECRET_NEW]->getText());

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
    public function validaccountvalid($hash)
    {
        $this->UserIsBlackList();

        try
        {
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
            $this->errorCatchView($e->getMessage(),true);
        }
    }
    #endregion

    #region CREATE ACCOUNT
    public function signup()
    {
        $this->UserIsBlackList();

        $form =new UsersignupForm($this->_request->getPost());

        try
        {
            if($this->_request->hasPost()) {
                $this->signupAfterPost ($form);
            } else {
                $this->signupView($form);
            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage());
        }
    }
    public function signupAfterPost($form)
    {
        try {
            //Contrôle de la saisie de l'utilisateur
            if (!$form->check()) {

                $this->signupView($form,true);

            }


            //initialisation de la class UserTable
            $user=$this->_userManager->createUser(
                $form[$form::IDENTIFIANT]->getText(),
                $form[$form::MAIL]->getText(),
                $form[$form::SECRET_NEW]->getText());



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
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage());
        }

    }
    private function signupView($form,$exit=false)
    {
        $this->userView('SignupView','Inscription',$form,false,$exit);
    }

    #endregion


}
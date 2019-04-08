<?php

namespace ES\App\Modules\User\Controller;

use ES\App\Modules\User\Model\ConnexionManager;
use ES\App\Modules\User\Model\UserManager;
use ES\App\Modules\User\Model\UserMail;
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


    public function __construct(UserConnect $userConnect,Request $request,$flash, $renderView)
    {
        parent::__construct($userConnect,$request,$flash, $renderView);
        $this->_userManager=new UserManager();
    }

    public function getWidgetDashboard():string
    {
        $users=[];
        $urlList='user/user/list';

        $users[0]=[
            ES_DASHBOARD_TITLE=>'Total',
            ES_DASHBOARD_ICONE=>'ion-ios-people',
            ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers(),
            ES_DASHBOARD_CONTENT=>'Nombre total d\'utilisateur inscrit',
            ES_DASHBOARD_LINK=>$urlList
        ];

        $users[1]=[
           ES_DASHBOARD_TITLE=>'non activé',
           ES_DASHBOARD_ICONE=>'ion-locked',
           ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers('validaccount',0),
           ES_DASHBOARD_CONTENT=>'Utilisateur n\'ayant pas validé leur compte',
           ES_DASHBOARD_LINK=>$urlList .'/validaccount/0'
       ];

        $users[2]=[
           ES_DASHBOARD_TITLE=>'Suspendu',
           ES_DASHBOARD_ICONE=>'ion-pause',
           ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers ('actif',0),
           ES_DASHBOARD_CONTENT=>'Utilisateur suspendu par un gestionnaire',
           ES_DASHBOARD_LINK=>$urlList .'/actif/0'
       ];

        $users[3]=[
           ES_DASHBOARD_TITLE=>ES_USER_ROLE[ES_USER_ROLE_GESTIONNAIRE],
           ES_DASHBOARD_ICONE=>'ion-university',
           ES_DASHBOARD_NUMBER=>$this->_userManager->countUsers ('user_role',ES_USER_ROLE_GESTIONNAIRE),
           ES_DASHBOARD_CONTENT=>'Gestionnaire du site',
           ES_DASHBOARD_LINK=>$urlList .'/user_role/' .ES_USER_ROLE_GESTIONNAIRE
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


        $form =new UserConnexionForm($this->request->getPost() );
        $connexion=new ConnexionManager();
        try
        {

            if($this->request->hasPost() && $form->check()) {
                //Vérification
                $user=$this->_userManager->findUserByLoginOrMail($form[$form::LOGIN]->getText());

                //si login non trouvé ou mauvais mot de passe
                if( !$user->hasId() ||
                !Auth::passwordCompare(
                $form[$form::SECRET]->getText(),
                $user->getPassword(),true)) {

                    $this->flash->writeError(MSG_USER_BAD_DATA);

                    //traçage de la connexion ko
                    $connexion->addConnexion ( $_SERVER[ES_IP]);

                    $this->connexionView ($form);

                } else {
                    $connexion->addConnexion( $_SERVER[ES_IP],$user->getId());

                    if (!$user->isValidAccount()) {
                        $mail=new UserMail();
                        $mail->sendMailSignup ($user);
                        $this->flash->writeWarning(MSG_USER_NOT_ACTIVATE);
                        ////si compte désactivé par un gestionnaire
                    } elseif ($user->getActif()=='0') {
                        $this->flash->writeWarning(MSG_USER_SUSPEND);
                    } else {
                        //Création de la variable de session
                        $this->userConnect->connect($user);
                        if($user->getUserRole()== ES_USER_ROLE_GESTIONNAIRE ||
                            $user->getUserRole()== ES_USER_ROLE_REDACTEUR ||
                            $user->getUserRole()== ES_USER_ROLE_MODERATEUR ) {
                            header('location: ' .ES_ROOT_PATH_WEB . 'shared/dashboard');
                            exit;
                        }
                    }
                    $this->AccueilView();
                }
            } else {
                $this->connexionView($form);
            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);
        }

    }


    public function connexionView($form)
    {
        $this->userView('ConnexionView','Connexion',$form,false,false);
    }
    #endregion



    #region FORGET VIEW
    public function pwdforget()
    {

        try
        {
            $form =new UserForgetForm($this->request->getPost() );

            if($this->request->hasPost() && $form->check())
            {
                //récupération de l'utilisateur par rapport au login, si non trouvé $user vide
                $user=$this->_userManager->findUserByLoginOrMail($form[$form::LOGIN]->getText());

                if ($user->hasId() &&
                    $this->_userManager->forgetInit($user,new UserMail())) {

                    //message pour l'utilisateur
                    $this->flash->writeInfo( 'Un mail de réinitialisation a été envoyé');

                    //Retour à la page d'accueil
                    $this->accueilView();

                } else {
                    $this->flash->writeError(MSG_USER_BAD_DATA);
                    $form[$form::LOGIN]->setIsInvalid(MSG_FORM_NOT_GOOD);
                    $this->pwdForgetView($form);

                }
            } else {

                $this->pwdForgetView($form);

            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage());
        }

    }
    private function pwdForgetView($form)
    {
        $this->userView('PwdForgetView','Mot de passe oublié ?',

            $form,false,
            false);
    }
    #endregion

    #region FORGET CHANGE
    public function pwdforgetchange($hash)
    {

        try
        {
            if($this->request->hasPost())
            {
                //récupération des paramètres
                $form =new UserForgetChangeForm($this->request->getPost());

                //contrôle si les champs du formulaire sont renseignés
                if($form->check()) {

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
                        $this->flash->writeError('Données incorrectes');
                        $this->accessDeniedView('Adresse incorrectes');
                    }
                } else {
                    $this->pwdForgetChangeView($form);
                }
            }
            else
            {
                $form= new UserForgetChangeForm([UserForgetChangeForm::HASH=>$hash],false);

                if( !$this->_userManager->findByForgetHash($hash)->hasId() ) {
                    $this->accessDeniedView('Adresse incorrectes');
                } else {
                    $this->pwdForgetChangeView($form);
                }
            }


        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);
        }

    }


    private function pwdForgetChangeView($form,$exit=false)
    {
        $this->userView('PwdForgetChangeView','Récupération du mot de passe',
            $form,false,$exit);
    }
    #endregion

    #region MODIFY
    private function modifyView(UserModifyForm $form)
    {
        // restriction des contrôle si l'utilisateur n'est pas le gestionnaire
        if(!$this->userConnect->canAdministrator() ) {
            $form[$form::USER_ROLE]->disabled=true;
            $form[$form::ACTIF]->disabled=true;
        }

        $this->userView('ModifyView','Les données de l\'utilisateur',
            $form,
            true,
            true);
    }
    public function modify($paramId=null)
    {
        try {
            if($this->request->hasPost())  {

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

        $form =new UserModifyForm($this->request->getPost());

        if( !$this->valideAccessPageOwnerOrGestionnaire($this->userConnect->user,
                            $form[$form::ID_HIDDEN]->getText())) {
            $this->AccueilView ();
        } else {

            //vérification si user connecté ou administrateur
            if ($form->check() ) {

                //récupération de l'UserTable
                $user=$this->_userManager->findById ($form[$form::ID_HIDDEN]->getText());


                if(!$user->hasId() ||
                $this->_userManager->identifiantExist($form[$form::IDENTIFIANT]->getText(),
                                                            $user->getId()) ||
                $this->_userManager->mailExist ($form[$form::MAIL]->getText(),
                                                            $user->getId()))
                {
                    $this->flash->writeError('L\'identifiant ou le mail existe déjà.');

                } else {
                    $user->setMail($form[$form::MAIL]->getText());
                    $user->setIdentifiant($form[$form::IDENTIFIANT]->getText());
                    if($this->userConnect->user->getUserRole()==ES_USER_ROLE_GESTIONNAIRE ) {
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
            }
            $this->modifyView($form);
        }
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
            $user=$this->userConnect->user;
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

        $this->modifyView($form);

    }
    #endregion

    #region VIEW

    private function  userView($view,$title,$form,$user)
    {
        $params=['title'=>$title,'form'=>$form];
        if($user)
        {
            $params['userConnect']=$this->userConnect->user;
        }

        $this->view($view,$params);
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

            $this->listView($list);
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView( $e->getMessage(),true);
        }
    }
    private function listView($list)
    {
        $this->userView('ListView','Liste des utilisateurs',
            $list,
            true,
            true);
    }

    #endregion

    #region DECONNEXION
    public function deconnexion()
    {
        //deconection
        $this->userConnect->disconnect();

        //retour à la page d'accueil
        $this->AccueilView();
    }
    #endregion



    #region PWD CHANGE
    public function pwdchange()
    {

        try
        {

            $form =new UserPwdChangeForm($this->request->getPost());


            if($this->request->hasPost()) {
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
        if($form->check() && Auth::passwordCompare ($form[$form::SECRET_OLD]->getText() ,
            $this->userConnect->user->getPassword()))
        {

            //reset de la table utilisateur
            $this->_userManager->updatePassword($this->userConnect->user,
               $form[$form::SECRET_NEW]->getText());

            //message d'info à l'utilisateur
            $this->flash->writeSucces( 'Mot de passe modifié');

            //retour à la page de connexion
            $this->AccueilView(true);
        } else {
            $this->pwdChangeView ($form);
        }

    }
    private function pwdChangeView($form)
    {
        $this->userView('PwdChangeView','Changement du mot de passe',
            $form,true,
            true);
    }
    #endregion

    #region VALIDATION DU COMPTE
    public function validaccount($hash)
    {

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


        $form =new UsersignupForm($this->request->getPost());

        try
        {
            if($this->request->hasPost()) {
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
            if ($form->check()) {



                //initialisation de la class UserTable
                $user=$this->_userManager->createUser(
                    $form[$form::IDENTIFIANT]->getText(),
                    $form[$form::MAIL]->getText(),
                    $form[$form::SECRET_NEW]->getText());

                $mail=new UserMail();

                if($user->hasId() &&                     //Envoi du mail
                    $mail->sendMailSignup ($user)) {
                    //Information de l'utilisateur
                    $this->flash->writeSucces ("Utilisateur créé, un mail a été envoyé pour valider l'inscription") ;

                    //Retour page d'accueil
                    $this->AccueilView();

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
            $this->errorCatchView($e->getMessage());
        }

    }
    private function signupView($form)
    {
        $this->userView('SignupView','Inscription',$form,false,false);
    }

    #endregion


}
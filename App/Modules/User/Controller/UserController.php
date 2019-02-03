<?php

namespace ES\App\Modules\User\Controller;

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
        $numberTotal=$this->_userManager->countUsers();
        $numberNotActive=$this->_userManager->countUsers('validaccount',0);
        $numberSuspendu=$this->_userManager->countUsers ('actif',0);
        $numberGestionnaire=$this->_userManager->countUsers ('accreditation',4);
        $data=[
            'numberTotal'=>$numberTotal,
            'numberNotActive'=>$numberNotActive,
            'numberSuspendu'=>$numberSuspendu,
            'numberGestionnaire'=>$numberGestionnaire
            ];
        $fichier=ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/Partial/WidgetDashboard.php';
        return $this->renderView->genererFichier($fichier, $data);
    }

    #region CONNEXION
    public function connexion()
    {
        $form =new UserConnexionForm($this->_request->getPost() );

        try
        {
            if($this->_request->hasPost()) {

                //contrôle si les champs du formulaire sont renseignés
                $form->check() || $this->connexionView ($form,true);

                //Vérification
                $user=$this->_userManager->findUserByLogin($form->text($form::LOGIN));

                //si login non trouvé ou mauvais mot de passe
                if( !$user->hasId() ||
                !Auth::passwordCompare(
                    $form->text($form::SECRET),
                    $user->getPassword(),true)) {

                    $this->flash->writeError('Informations utilisateurs incorrectes');
                    $this->connexionView ($form,true);
                    // si compte non validé
                } elseif (!$user->isValidAccount())  {

                    $this->_userManager->sendMailSignup($user);
                    $this->flash->writeWarning('Vous n\'avez pas validé votre compte. Le mail d\'activation est renvoyé.');
                    $this->AccueilView(true);
                    //si compte désactivé par un gestionnaire
                } elseif ($user->getActif()=='0')  {

                    $this->flash->writeWarning('Votre compte a été suspendu par un gestionnaire.');
                    $this->AccueilView(true);
                } else {
                    //Création de la variable de session
                    $this->_userConnect->connect($user);
                    $this->AccueilView();
                    return;
                }
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

    #region FORGET VIEW
    public function pwdforget()
    {
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
                $user=$this->_userManager->findUserByLogin($form->text($form::LOGIN));

                if ($user->hasId() &&
                    $this->_userManager->forgetInit($user)) {

                    //message pour l'utilisateur
                    $this->flash->writeInfo( 'Un mail de réinitialisation a été envoyé');

                    //Retour à la page d'accueil
                    $this->accueilView();

                } else {

                    $form[$form::LOGIN]->setIsInvalid('Ces informations sont incorrectes');
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
        $user=$this->_userManager->findByForgetHash($form[$form::HASH]->text);

        if($user->hasId() &&
            $this->_userManager->forgetReset($user,$form[$form::SECRET_NEW]->text)) {

            //message d'info à l'utilisateur
            $this->flash->writeSucces( 'Mot de passe modifié');

            //retour à la page de connexion
            $this->connexion();
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
            $form[$form::ACCREDITATION]->disabled=true;
            $form[$form::ACTIF]->disabled=true;
        }

        $this->userView('ModifyView','Les données de l\'utilisateur',
            $form,
            true,
            $exit);
    }
    public function modify($paramId=null)
    {

        try
        {

            if($this->_request->hasPost())  {
                $this->modifyAfterPost();
            } else {
                $this->modifyBeforePost ($paramId);
            }


        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);
        }
    }
    public function modifyAfterPost()
    {
        $form =new UserModifyForm($this->_request->getPost());

        if( !$this->valideAccessPageOwnerOrGestionnaire($this->_userConnect->user,
                            $form->text($form::ID_HIDDEN))) {

            $this->AccueilView (true);
        }

        //vérification si user connecté ou administrateur
        if (!$form->check() ) {

            $this->modifyView($form,$this->_userConnect->user,true);
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
            if($this->_userConnect->user->getAccreditation()=='4' ) {
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
            UserModifyForm::ACCREDITATION=>$user->getAccreditation(),
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
                $filtreOK=['accreditation','actif','validaccount'];
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
        if(!Auth::passwordCompare ($form->text($form::SECRET_OLD) ,
            $this->_userConnect->user->getPassword()))
        {
            $form[$form::SECRET_OLD]->setIsInvalid( 'L\'ancien mot de passe est incorrect');
            $this->pwdChangeView ($form,true);
        }

        //reset de la table utilisateur
        $this->_userManager->updatePassword($this->_userConnect->user,
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
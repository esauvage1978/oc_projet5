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

    public function pwdforget()
    {
        $datas=$this->_request;
        $form =new UserForm($datas);

        if($datas->hasPost())
        {
            $login=$datas->getPostValue('login');

            $user_manager=new UserManager ();

            try
            {
                $user=$user_manager->findUserByLogin($login);
            }
            catch(\InvalidArgumentException $e)
            {
                $this->flash->writeError( $e->getMessage());
                $this->pwdForgetView($form);
                exit;
            }
            $user=$user_manager->updateUserForPwdForget($user);
            $user_manager->sendMailPwdForget($user);
            $this->flash->writeSucces( 'Un mail de réinitialisation a été envoyé');
            $this->connexionView($form);
            exit;
        }

        $this->pwdForgetView($form);
    }
    public function pwdforgetchange()
    {
        $datas=$this->_request;


        if($datas->hasPost())
        {
            $form =new UserForm($datas);
            $pwdForget=$datas->getPostValue('pwdForget');


            $user_manager=new UserManager ();

            try
            {
                $user=$user_manager->findByPasswordForget($pwdForget);
            }
            catch(\InvalidArgumentException $e)
            {
                $this->flash->writeError( $e->getMessage());
                $this->pwdConnexionView($form);
                exit;
            }
            $user_manager->updateUserForPwdForget($user,false);
            $user->setPassword =Auth::password_crypt($datas->getPostValue('pwd'));
            $user=$user_manager->updatePwd($user);
            $this->flash->writeSucces( 'Mot de passe modifié');
            $this->connexionView($form);
            exit;
        }
        else
        {
            $pwdForget=$this->_request->getGetValue('mot');
            if(!isset($pwdForget) || empty($pwdForget))
            {
                $this->connexionView(new UserForm($datas));
                exit;
            }
            $form =new UserForm(['pwdForget'=>$pwdForget]);
        }
        $this->pwdForgetChangeView($form);
    }
    public function connexion()
    {
        $datas=$this->_request;
        $form =new UserForm($datas);

        if($datas->hasPost())
        {
            $login=$datas->getPostValue('login');
            $pwd=$datas->getPostValue('pwd');

            $user_manager=new UserManager ();

            try
            {
                $user=$user_manager->findUserByLogin($login);
            }
            catch(\InvalidArgumentException $e)
            {
                $this->flash->writeError( $e->getMessage());
                $this->connexionView($form);
                exit;
            }
            if (!Auth::password_compare($pwd, $user->getPassword()) )
            {
                $this->flash->writeError ('L\'identifiant ou le mot de passe sont incorrects. Veuillez réessayer.');
            }
            else
            {
                $this->flash->writeSucces ($user->getIdentifiant());
                $_SESSION['user']=$user;
                header('Location: index.php');
            }

        }
        $this->connexionView($form);

    }
    public function deconnexion()
    {
        $this->_request->unsetSessionValue('user');
        header('Location: index.php');
    }

    public function signup()
    {
        $datas=$this->_request;
        $form =new UserForm($datas);

        if($datas->hasPost())
        {
            $identifiant=$datas->getPostValue('identifiant');
            $mail=$datas->getPostValue('mail',Request::TYPE_MAIL);
            $pwd=$datas->getPostValue('pwd');
            $pwdConfirmation=$datas->getPostValue('pwdConfirmation');

            try
            {
                $user=new UserTable
                    ([
                    'u_identifiant'=>$identifiant,
                    'u_mail'=>$mail,
                    'u_password'=>Auth::password_crypt($pwd)
                    ]);
            }
            catch(\InvalidArgumentException $e)
            {
                $this->flash->writeError( $e->getMessage());
                $this->signupView($form);
                exit;
            }

            $userManager=new UserManager ();

            if ($pwd  != $pwdConfirmation)
            {
                $this->flash->writeError ('Le mot de passe et sa confirmation sont différents');
            }
            else if($userManager->identifiantExist ($identifiant))
            {
                $this->flash->writeError ('Cet identifiant est déjà utilisé.');
            }
            else if($userManager->mailExist ($mail))
            {
                $this->flash->writeError ('Cet e-mail est déjà utilisé.');
            }
            else
            {
                $id= $userManager->create([
                    'u_identifiant'=>$user->getIdentifiant,
                    'u_mail'=>$user->getMail,
                    'u_password'=>$user->getPassword
                    ]);
                if(!$id)
                {
                    $this->flash->writeError ('Erreur lors de l\'ajout de l\'utilisateur.');
                    $this->signupView($form);
                    exit;
                }
                $user->setId($id);
                $userManager->sendMailSignup ($user);
                $this->flash->writeSucces ("Utilisateur créé, un mail a été envoyé pour valider l'inscription") ;


                header('Location: index.php');
                exit;
            }
        }

        $this->signupView($form);

    }

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
}
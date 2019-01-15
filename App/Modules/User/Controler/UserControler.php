<?php

namespace ES\App\Modules\User\Controler;

use ES\Core\Controler\AbstractControler;
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
class UserControler extends AbstractControler
{
    protected static $module='User';

    public function connexion()
    {
        $form =new UserForm($this->_request);

        $this->renderView->render(
            ES_ROOT_PATH_FAT_MODULES . 'User\\View\\ConnexionView.php',
            array(
                'title'=>'Connexion',
                'form'=>$form
            )
        );
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
                $this->flash->writeSucces ("Utilisateur créé, un mail a été envoyé pour valider l'inscription") ;
                $userManager->create([
                    'u_identifiant'=>$user->getIdentifiant,
                    'u_mail'=>$user->getMail,
                    'u_password'=>$user->getPassword
                    ]);
                header('Location: index.php');
                exit;
            }
        }

        $this->signupView($form);

    }
    private function signupView($form)
    {
        $this->renderView->render(
            ES_ROOT_PATH_FAT_MODULES . 'User\\View\\SignupView.php',
            [
                'title'=>'Inscrivez-vous',
                'form'=>$form
            ]
        );
    }


}
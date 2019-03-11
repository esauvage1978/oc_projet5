<?php

namespace ES\App\Modules\Shared\Controller;

use \ES\Core\Controller\AbstractController;
use \ES\App\Modules\User\Controller\UserController;
use \ES\App\Modules\Blog\Controller\BlogController;
use \ES\App\Modules\Shared\Form\ContactForm;
use \ES\App\Modules\Shared\Model\SharedManager;

/**
 * SharedController short summary.
 * Controller frontal de base
 * SharedController description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedController extends AbstractController
{
    static $module='Shared';

    public function Show()
    {
        $form =new ContactForm($this->_request->getPost() );
        $this->ShowView($form);
    }
    public function contact()
    {
        $form =new ContactForm($this->_request->getPost() );
        try
        {
            if($this->_request->hasPost()) {

                //contrôle si les champs du formulaire sont renseignés
                $form->check() || $this->ShowView ($form,'0',true);

                $recaptcha=$form[$form::RECAPTCHA]->getName();
                if ($this->_request->hasPostValue($recaptcha )) {

                    // Build POST request:
                    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
                    $recaptcha_secret = ES_RECAPTCHA_SECRET_BACK;
                    $recaptcha_response = $this->_request->getPostValue($recaptcha );  

                    // Make and decode POST request:
                    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
                    $recaptcha = json_decode($recaptcha);

                    // Take action based on the score returned:
                    if ($recaptcha->score < 0.5) {
                        $this->ShowView ($form,'2',true);
                    }

                }

                //envoi du mail
                $sharedManager=new SharedManager();
                $nom=$form[$form::NAME]->getText();
                $mail=$form[$form::MAIL]->getText();
                $subject=$form[$form::SUBJECT]->getText();
                $message=$form[$form::MESSAGE]->getText();

                $sharedManager->sendMailContact ($nom,$mail,$subject,$message  );

                //réinitialisation du formulaire
                $form =new ContactForm([]);

                $this->ShowView($form,'1');


            } else {
                $this->ShowView($form);
            }
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage(),true);
        }
    }
    public function ShowView($form,$mailSend=0,$exit=false)
    {
        $datas=['title'=>'Page d\'accueil','form'=>$form];
        if($mailSend=='1') {
            $datas['mailSend']="Votre message a été envoyé, Merci.";
        } elseif($mailSend=='2') {
            $datas['mailSend']="Erreur de recaptcha, vous êtes un robot !!";
        }
        $this->view('HomeView',$datas);
        if($exit){exit;}
    }
    public function accessdenied()
    {
        $this->view('AccessDenied',['title'=>'Accès interdit']);
    }
    public function accessdeniedmanyconnexion()
    {
        $this->view('AccessDeniedManyConnexion',['title'=>'Accès bloqué']);
    }
    public function errorcatch()
    {
        $this->view('ErrorCatch',['title'=>'Erreur sur le site']);
    }

    public function dashboard()
    {
        $contentDashboard='';
        if($this->_userConnect->canRedactor() )
        {
            $blogController=new BlogController($this->_userConnect,$this->_request);
            $contentDashboard.=$blogController->getWidgetDashboard ();
        }
        if($this->_userConnect->canAdministrator() ) {
            $userController=new UserController($this->_userConnect,$this->_request );
            $contentDashboard.=$userController->getWidgetDashboard ();
        }

        $this->view('DashboardView',
        ['title'=>'Tableau de bord',
        'contentDashboard'=>$contentDashboard]);
    }

}
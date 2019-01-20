<?php

use PHPUnit\Framework\TestCase;
use ES\App\Modules\User\Form\UserForm;
use ES\Core\Toolbox\Request;

require './Config/constantes.php';

class UserFormTest extends TestCase
{
    protected $form;
    protected $request;

    public function setUp()
    {
        $_SESSION=[];
        $this->request=new Request();
        $this->form=new UserForm($this->request);
    }

    public function testIdentifiant()
    {
        //$retour=$this->form->RenderIdentifiant();
        //$this->assertNotContains('is-invalid',$retour);
        $this->form->isInvalid (UserForm ::$formIdentifiant);
        $retour=$this->form->RenderIdentifiant();
        $this->assertContains('is-invalid',$retour);
    }
}
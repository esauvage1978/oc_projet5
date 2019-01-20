<?php

use PHPUnit\Framework\TestCase;
use ES\App\Modules\User\Model\UserTable;
use ES\App\Modules\User\Model\UserManager;
use ES\Core\Toolbox\Auth;

require './Config/constantes.php';

class UserManagerTest extends TestCase
{
    protected $userManager;
    protected $userTest;

    public function setUp()
    {
        $this->userManager=new UserManager();
    }

    public function testUserManager()
    {
        $this->assertNotNull($this->userManager);
    }

    public function testIdentifiantExist()
    {
        $value='Manuso';
        $num=9;
        $this->assertSame(true,$this->userManager->identifiantExist($value));
        $this->assertSame(false,$this->userManager->identifiantExist($value,$num));
        $value=Auth::strRandom(15);
        $this->assertSame(false,$this->userManager->identifiantExist($value));
    }
    public function testMailExist()
    {
        $value='emmanuel.sauvage@live.fr';
        $num=9;
        $this->assertSame(true,$this->userManager->mailExist($value));
        $this->assertSame(false,$this->userManager->mailExist($value,$num));
        $value=Auth::strRandom(15);
        $this->assertSame(false,$this->userManager->mailExist($value));
    }

    public function testFindById()
    {
        $num=9;
        $user=$this->userManager->findById ($num);
        $this->assertSame('Manuso',$user->getIdentifiant());

    }

}
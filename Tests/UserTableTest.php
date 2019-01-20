<?php

use PHPUnit\Framework\TestCase;
use ES\App\Modules\User\Model\UserTable;
use ES\App\Modules\User\Model\UserManager;
use ES\Core\Toolbox\Auth;

require './Config/constantes.php';

class UserTableTest extends TestCase
{
    protected $es_now;
    public function setUp()
    {
        $this->es_now='Y-m-d H:i:s';
    }
    public function testUserInstanceVide()
    {
        $user=new UserTable([]);
        $this->assertNotNull($user);
    }
    #region IDENTIFIANT
    public function testIdentifiantBySetter()
    {
        $user=new UserTable([]);
        $value='test';
        $user->setIdentifiant($value);
        $this->assertSame($value,$user->getIdentifiant());
    }
    public function testIdentifiantByConstructor()
    {
        $value='test';
        $user=new UserTable([UserManager::IDENTIFIANT=>$value]);
        $user->setIdentifiant($value);
        $this->assertSame($value,$user->getIdentifiant());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIdentifiantValue60()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(60);
        $user->setIdentifiant($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIdentifiantValue3()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(3);
        $user->setIdentifiant($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIdentifiantValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setIdentifiant($value);
    }
    #endregion
    #region MAIL
    public function testMailBySetter()
    {
        $user=new UserTable([]);
        $value='emmanuel.sauvage@live.fr';
        $user->setMail($value);
        $this->assertSame($value,$user->getMail());
    }
    public function testMailByConstructor()
    {
        $value='emmanuel.sauvage@live.fr';
        $user=new UserTable([UserManager::MAIL=>$value]);
        $user->setMail($value);
        $this->assertSame($value,$user->getMail());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testMailValue100()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(100) . '@live.fr';
        $user->setMail($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testMailValueNotMail()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(10);
        $user->setMail($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testMailValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setMail($value);
    }
    #endregion
    #region ID
    public function testIDBySetter()
    {
        $user=new UserTable([]);
        $value=9;
        $user->setId($value);
        $this->assertSame($value,$user->getId());
    }
    public function testIdByConstructor()
    {
        $value=9;
        $user=new UserTable([UserManager::ID=>$value]);
        $user->setID($value);
        $this->assertSame($value,$user->getId());
    }
    public function testHasId()
    {
        $value=9;
        $user=new UserTable([UserManager::ID=>$value]);
        $user->setID($value);
        $this->assertSame(true,$user->hasId());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIdValueNotId()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(10);
        $user->setId($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIdValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setId($value);
    }
    #endregion
    #region Secret
    public function testSecretBySetter()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(60);
        $user->setPassword($value);
        $this->assertSame($value,$user->getPassword());
    }
    public function testSecretByConstructor()
    {
        $value=Auth::strRandom(60);
        $user=new UserTable([UserManager::SECRET=>$value]);
        $user->setPassword($value);
        $this->assertSame($value,$user->getPassword());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSecretValue100()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(100);
        $user->setPassword($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSecretValue3()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(3);
        $user->setPassword($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSecretValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setPassword($value);
    }
    #endregion
    #region ForgetHash
    public function testForgetHashBySetter()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(60);
        $user->setForgetHash($value);
        $this->assertSame($value,$user->getForgetHash());
    }
    public function testForgetHashByConstructor()
    {
        $value=Auth::strRandom(60);
        $user=new UserTable([UserManager::FORGET_HASH=>$value]);
        $user->setForgetHash($value);
        $this->assertSame($value,$user->getForgetHash());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testForgetHashValue100()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(100);
        $user->setForgetHash($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testForgetHashValue3()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(3);
        $user->setForgetHash($value);
    }

    public function testForgetHashValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setForgetHash($value);
        $this->assertNull($user->getForgetHash());
    }
    #endregion
    #region ForgetDate
    public function testForgetDateBySetter()
    {
        $user=new UserTable([]);
        $value=date($this->es_now);
        $user->setForgetDate($value);
        $this->assertSame($value,$user->getForgetDate());
    }
    public function testForgetDateByConstructor()
    {
        $value=date($this->es_now);
        $user=new UserTable([UserManager::FORGET_DATE=>$value]);
        $user->setForgetDate($value);
        $this->assertSame($value,$user->getForgetDate());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testForgetDateValueNotDate()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(10);
        $user->setForgetDate($value);
    }
    public function testForgetDateValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setForgetDate($value);
        $this->assertNull($user->getForgetDate());
    }
    #endregion
    #region ValidAccountHash
    public function testValidAccountHashBySetter()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(60);
        $user->setValidAccountHash($value);
        $this->assertSame($value,$user->getValidAccountHash());
    }
    public function testValidAccountHashByConstructor()
    {
        $value=Auth::strRandom(60);
        $user=new UserTable([UserManager::VALID_ACCOUNT_HASH=>$value]);
        $user->setValidAccountHash($value);
        $this->assertSame($value,$user->getValidAccountHash());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidAccountHashValue100()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(100);
        $user->setValidAccountHash($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidAccountHashValue3()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(3);
        $user->setValidAccountHash($value);
    }

    public function testValidAccountHashValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setValidAccountHash($value);
        $this->assertNull($user->getValidAccountHash());
    }
    #endregion
    #region ValidAccountDate
    public function testValidAccountDateBySetter()
    {
        $user=new UserTable([]);
        $value=date($this->es_now);
        $user->setValidAccountDate($value);
        $this->assertSame($value,$user->getValidAccountDate());
    }
    public function testValidAccountDateByConstructor()
    {
        $value=date($this->es_now);
        $user=new UserTable([UserManager::VALID_ACCOUNT_DATE=>$value]);
        $user->setValidAccountDate($value);
        $this->assertSame($value,$user->getValidAccountDate());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidAccountDateValueNotDate()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(10);
        $user->setValidAccountDate($value);
    }
    public function testValidAccountDateValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setValidAccountDate($value);
        $this->assertNull($user->getValidAccountDate());
    }
    #endregion
    #region Accreditation
    public function testAccreditationBySetter()
    {
        $user=new UserTable([]);
        $value=1;
        $user->setAccreditation($value);
        $this->assertSame($value,$user->getAccreditation());
    }
    public function testAccreditationByConstructor()
    {
        $value=1;
        $user=new UserTable([UserManager::ACCREDITATION=>$value]);
        $user->setAccreditation($value);
        $this->assertSame($value,$user->getAccreditation());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAccreditationValueNotInt()
    {
        $user=new UserTable([]);
        $value=Auth::strRandom(10);
        $user->setAccreditation($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAccreditationValueInt6()
    {
        $user=new UserTable([]);
        $value=6;
        $user->setAccreditation($value);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAccreditationValueNull()
    {
        $user=new UserTable([]);
        $value=Null;
        $user->setAccreditation($value);
    }
    #endregion
}

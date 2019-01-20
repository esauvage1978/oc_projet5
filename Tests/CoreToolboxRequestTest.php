<?php

use PHPUnit\Framework\TestCase;
use ES\Core\Toolbox\Request;

/**
 * CoreToolboxRequest short summary.
 *
 * CoreToolboxRequest description.
 *
 * @version 1.0
 * @author ragus
 */
class CoreToolboxRequestTest extends TestCase
{
    protected $request;

    public function setUp()
    {
        $_SESSION=[];
        $this->request=new Request();
    }

    public function testHasRequest()
    {

        $this->assertNotEmpty($this->request);
    }

    #region POST
    public function testHasPost()
    {
        $this->assertSame(false, $this->request->hasPost());
    }
    public function testHasPostValue()
    {
        $this->assertSame(false, $this->request->hasPostValue('vide'));
    }
    public function testGetPostValue()
    {
        $this->assertSame(null, $this->request->getPostValue('vide'));
    }
    #endregion
    #region Get
    public function testHasGetValue()
    {
        $this->assertSame(false, $this->request->hasGetValue('vide'));
    }
    public function testGetGetValue()
    {
        $this->assertSame(null, $this->request->getGetValue('vide'));
    }
    #endregion


    #region Session
    public function testHasSession()
    {
        $this->assertSame(true, $this->request->hasSession());
    }

    public function testHasSessionValue()
    {
        $this->assertSame(false, $this->request->hasSessionValue('test'));
    }

    public function testGetSessionValue()
    {
        $this->assertSame(null, $this->request->getSessionValue('test'));
    }

    public function testSetSessionValueString()
    {
        $this->assertSame(false, $this->request->hasSessionValue('test'));
        $this->request->setSessionValue('test','montest');
        $this->assertSame(true, $this->request->hasSession());
        $this->assertSame(true, $this->request->hasSessionValue('test'));
        $this->assertSame('montest', $this->request->getSessionValue('test',Request::TYPE_STRING));
        $this->request->unsetSessionValue('test');
        $this->assertSame(false, $this->request->hasSessionValue('test'));
    }
    public function testSetSessionValueInt()
    {
        $value=2;
        $this->request->setSessionValue('test',$value);
        $this->assertSame($value, $this->request->getSessionValue('test',Request::TYPE_INT));
        $value='a';
        $this->request->setSessionValue('test',$value);
        $this->assertSame(false, $this->request->getSessionValue('test',Request::TYPE_INT));
        $value='2';
        $this->request->setSessionValue('test',$value);
        $this->assertSame(2, $this->request->getSessionValue('test',Request::TYPE_INT));
    }
    public function testSetSessionValueMail()
    {
        $value='emmanuel.sauvage@live.fr';
        $this->request->setSessionValue('test',$value);
        $this->assertSame($value, $this->request->getSessionValue('test',Request::TYPE_MAIL));
        $value='a';
        $this->request->setSessionValue('test',$value);
        $this->assertSame(false, $this->request->getSessionValue('test',Request::TYPE_MAIL));
    }
    public function testSetSessionValueBoolean()
    {
        $value=true;
        $this->request->setSessionValue('test',$value);
        $this->assertSame($value, $this->request->getSessionValue('test',Request::TYPE_BOOLEAN));
        $value='a';
        $this->request->setSessionValue('test',$value);
        $this->assertSame(false, $this->request->getSessionValue('test',Request::TYPE_BOOLEAN));
    }
    public function testSetSessionValueArray()
    {
        $value=['mon'=>'test'];
        $this->request->setSessionValue('test',$value);
        $this->assertSame($value, $this->request->getSessionValue('test',Request::TYPE_ARRAY));
        $value='a';
        $this->request->setSessionValue('test',$value);
        $this->assertSame(false, $this->request->getSessionValue('test',Request::TYPE_ARRAY));
    }
    public function testSetSessionValueHtmlEntity()
    {
        $value='mon';
        $this->request->setSessionValue('test',$value);
        $this->assertSame($value, $this->request->getSessionValue('test',Request::TYPE_HTMLENTITY));
        $value='a<br>';
        $this->request->setSessionValue('test',$value);
        $this->assertSame('a&lt;br&gt;', $this->request->getSessionValue('test',Request::TYPE_HTMLENTITY));
    }
    #endregion
}
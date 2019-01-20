<?php

use PHPUnit\Framework\TestCase;
use ES\Core\Toolbox\Auth;

/**
 * AuthTest short summary.
 *
 * AuthTest description.
 *
 * @version 1.0
 * @author ragus
 */
class CoreToolboxAuthTest extends TestCase
{
    public function setup()
    {
        $this->assertTrue(True);
    }
    public function testGenerateRandom()
    {
        $hash='';
        $this->assertSame(0, strlen($hash));
        $hash=Auth::strRandom();
        $this->assertSame(60, strlen($hash));
    }

    public function testPwdHashCompare()
    {
        $value='a';
        $hash=Auth::passwordCrypt($value);
        $this->assertSame(60, strlen($hash));
        $this->assertSame(true, Auth::passwordCompare($value,$hash));
        $this->assertSame(false, Auth::passwordCompare('b',$hash));
        $this->assertSame(true, Auth::passwordCompare($value,$hash,true));
        $this->assertSame(false, Auth::passwordCompare('b',$hash,true));
        $this->assertSame(false, Auth::passwordCompare($value,$hash,false));
    }

    public function testPwdNotHashCompare()
    {
        $value='a';
        $value2='b';
        $this->assertSame(false, Auth::passwordCompare($value,$value2,false));
        $this->assertSame(true, Auth::passwordCompare($value,$value,false));
    }

}
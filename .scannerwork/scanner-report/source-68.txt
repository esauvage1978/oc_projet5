<?php


require_once '../Core/Toolbox/Flash.php';

use PHPUnit\Framework\TestCase;
use ES\Core\ToolBox\Flash;

class FlashTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $flash=new Flash();
        $this->assertInstanceOf(
            Flash::class,
            $flash
        );
    }

    public function testWrite()
    {
        $flash =new Flash();
        //$this->assertNotFalse ($flash->writeError('test'));
    }

}
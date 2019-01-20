<?php
use \PHPUnit\Framework\TestCase;
use \ES\Core\Toolbox\Flash;
use \ES\Core\Toolbox\Request;

/**
 * CoreToolboxToolboxTest short summary.
 *
 * CoreToolboxToolboxTest description.
 *
 * @version 1.0
 * @author ragus
 */
class CoreToolboxFlashTest extends TestCase
{
    protected $flash;

    public function setUp()
    {
        $_SESSION=[];
        $this->flash=new Flash();
    }

    public function testHasFlash()
    {
        $this->assertSame(false,$this->flash->hasFlash() );
    }

    public function testReadEmpty()
    {
        $this->assertSame(null,$this->flash->read() );
    }

    public function testWriteSuccess()
    {
        $valeur='message de succès';
        $this->flash->writeSucces($valeur);
        $this->assertArrayHasKey(Flash::SUCCES,$this->flash->read());
    }
    public function testWriteInfoAndError()
    {
        $valeur='message 12345';
        $this->flash->writeInfo($valeur);
        $valeur='message 67890';
        $this->flash->writeError($valeur);
        $retour=$this->flash->read();

        $this->assertArrayHasKey (Flash::INFO,$retour);
        $this->assertArrayHasKey (Flash::ERROR,$retour);
        $this->assertSame(2, count($retour));
        $this->assertSame($valeur, $retour[Flash::ERROR]);
    }
    public function testWriteWarning()
    {
        $valeur='message de danger';
        $this->flash->writeWarning($valeur);
        $this->assertArrayHasKey(Flash::WARNING,$this->flash->read());
    }
}
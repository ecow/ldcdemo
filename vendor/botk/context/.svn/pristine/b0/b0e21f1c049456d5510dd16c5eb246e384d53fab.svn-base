<?php
namespace BOTK\Context;

use PHPUnit_Framework_TestCase;
use BOTK\Context\ContextNameSpace;
use BOTK\Context\Context as CX;



/**
 * @covers BOTK\Context\ContextNameSpace
 * @covers BOTK\Context\Context
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        // create a temp ini file
        file_put_contents ('sample.ini',
'
; This is a comment
; Section shoud be ignored
[THIS_IS_A_SECTION]
var1 = "This is a string"
var2 = 2 ; an iteger
var3[] = 1  ; array
var3[] = 2  ; array

[ANOTHER_SECTION]
; blank lines ignored
var3[] = 3  ; array

');
        $_ENV['BOTK_CONFIGDIR'] = '.';
    }
    
    public static  function tearDownAfterClass()
    {
        // delete temp ini file
        unlink('sample.ini');
    }
    
    
    public function testVarDefault() {
        $v = CX::factory()->ns('sample')->getValue('novar','ok');
        $this->assertEquals($v,'ok');
    }
    
    
    public function testVarDefaultMandatoryExists() {
        $v = CX::factory()->ns('sample')->getValue('var1');
        $this->assertEquals($v,"This is a string");
    }

    
    
    public function testVarArray() {
        $v = CX::factory()->ns('sample')->getValue('var3');
        $this->assertEquals($v,array(1,2,3));
    }

   
     /**
     * @expectedException \Exception
     * 
     */      
    public function testVarDefaultMandatoryNo() {
        $v = CX::factory()->ns('sample')->getValue('novar');
    }


   
     /**
     * @expectedException \Exception
     * 
     */      
    public function testNoNamespace() {
        $v = CX::factory()->ns('nosample')->getValue('novar',1);
    }      
}

<?php
namespace BOTK\Context;

use PHPUnit_Framework_TestCase;
use BOTK\Context\ContextNameSpace;
use BOTK\Context\Context as CX;



/**
 * @covers BOTK\Context\ContextNameSpace
 * @covers BOTK\Context\Context
 */
class SanitizeTest extends PHPUnit_Framework_TestCase
{
    public $ns;

    public function setUp()
    {
        $this->ns = new ContextNameSpace( array( 
            'p1'    => '-string1',
            'p2'    => 10,
            'p3'    => array(1,2,3),    // same type array
            'p4'    => array(1,2,'ff'), // mixed type array ( unsupported by std filter, need custom validator)
            'p5'    => new \stdClass,   // unsupported both by ini file and ContextNameSpace, but...
        ));
    }

    public function testSimpleSanitize()
    {
        $p = $this->ns->getValue('p1',null,null, FILTER_SANITIZE_NUMBER_INT);
        $this->assertEquals(-1,$p);
    }


    public function testSanitizeAndValidate()
    {
        $p = $this->ns->getValue('p1', null ,FILTER_VALIDATE_INT, FILTER_SANITIZE_NUMBER_INT);
        $this->assertEquals(-1,$p);
    }


    public function testSanitizeAndValidateOnDefault()
    {
        $p = $this->ns->getValue('unesistent', 'thisis2' ,FILTER_VALIDATE_INT, FILTER_SANITIZE_NUMBER_INT);
        $this->assertEquals(2,$p);
    }

   

    public function testSanitizeWithCallable()
    {
        $p = $this->ns->getValue('unesistent', 'plain' , null, 'ucfirst');
        $this->assertEquals('Plain',$p);
    }
}

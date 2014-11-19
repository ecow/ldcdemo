<?php
namespace BOTK\Context;

use PHPUnit_Framework_TestCase;
use BOTK\Context\ContextNameSpace;
use BOTK\Context\Context as CX;



/**
 * @covers BOTK\Context\ContextNameSpace
 * @covers BOTK\Context\Context
 */
class LocalContextTest extends PHPUnit_Framework_TestCase
{    
    public function testLocalVar() {
        $myvar = 'ok'; // define a variable in local scope
        $v = CX::factory(get_defined_vars())->ns(CX::LOCAL)->getValue('myvar');
        $this->assertEquals($v,'ok');
    }
    
}

<?php
namespace BOTK\Context;

use PHPUnit_Framework_TestCase;
use BOTK\Context\ContextNameSpace as V;

class ValidatorStubOK
{
    function assert($data) { return true;}
}

class ValidatorStubKO
{
    function assert($data) { throw new \Exception("Error Processing Request", 1);}
}


/**
 * @covers BOTK\Context\ContextNameSpace
 */
class ValidationFiltersTest extends PHPUnit_Framework_TestCase
{
    public $ns;

    public function setUp()
    {
        $this->ns = new ContextNameSpace( array( 
            'p1'    => 'stringa',
            'p2'    => 10,
            'p3'    => array(1,2,3),    // same type array
            'p4'    => array(1,2,'ff'), // mixed type array ( unsupported by std filter, need custom validator)
            'p5'    => new \stdClass,   // unsupported both by ini file and ContextNameSpace, but...
        ));
    }

    public function testDefaultValidator()
    {
        $p = $this->ns->getValue('p1');
        $this->assertEquals($p,'stringa');
    }


    public function testDefaultValidatorWithArray()
    {
        $p = $this->ns->getValue('p4');
        $this->assertEquals($p,array(1,2,'ff'));
    }
       
    /**
     * @expectedException \Exception
     */   
    public function testDefaultValidatorWithUnsupportedTypeKO()
    {
        $p = $this->ns->getValue('p5',null,FILTER_VALIDATE_INT);
    }
 
  
    public function testENUM()
    {
         $p = $this->ns->getValue('p1',null, V::ENUM('stringa|string'));
         $this->assertEquals($p,'stringa');       
    }
  
     /**
     * @expectedException \Exception
     */    
    public function testENUMKO()
    {
         $p = $this->ns->getValue('p1',null, V::ENUM('stringx|string'));
    }


    public function testWithCustomValidator()
    {
         $validator = new ValidatorStubOK;
         $p = $this->ns->getValue('p1',null, $validator );
         $this->assertEquals($p,'stringa');       
    }
    
    
     /**
     * @expectedException \Exception
     */  
    public function testWithCustomValidatorKO()
    {
         $validator = new ValidatorStubKO;
         $p = $this->ns->getValue('p1',null, $validator );      
    }
 
    
    public function testSimpleFilterValidator()
    {
        $p = $this->ns->getValue('p2',null, FILTER_VALIDATE_INT);
        $this->assertEquals($p,10);
    }
    
     /**
     * @expectedException \Exception
     */    
    public function testSimpleFilterValidatorKO()
    {
        $p = $this->ns->getValue('p1',null, FILTER_VALIDATE_INT);
    }


    public function testValidatorWithOptions()
    {
        $p = $this->ns->getValue('p2',null,
            array('filter'=>FILTER_VALIDATE_INT,'options'=> array('min_range' => 5, 'max_range' => 15)));
        $this->assertEquals($p,10);
    }
    
    
    /**
     * @expectedException \Exception
     */  
    public function testValidatorWithOptionsKO()
    {
        $p = $this->ns->getValue('p2',null,
            array('filter'=>FILTER_VALIDATE_INT,'options'=> array('min_range' => 11, 'max_range' => 15)));

    }
    /**
     * @expectedException \Exception
     * 
     * Unsupported
     */  
    public function testSimpleFilterValidatorArray()
    {
        $p = $this->ns->getValue('p3',null, FILTER_VALIDATE_INT);
        //$this->assertEquals($p,array(1,2,3));
    }

    /**
     * @expectedException \Exception
     * 
     * Unsupported
     */   
    public function testValidatorWithOptionsArray()
    {
        $p = $this->ns->getValue('p3',null,
            array('filter'=>FILTER_VALIDATE_INT,'options'=> array('min_range' => 0, 'max_range' => 5)));
        //$this->assertEquals($p,array(1,2,3));
    }


    public function testOptionalPar()
    {
        $p = $this->ns->getValue('pnotexists',20 ,FILTER_VALIDATE_INT);
        $this->assertEquals($p,20);
    }
      

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing mandatory 
     */    
    public function testMandatoryPar()
    {
        $p1 = $this->ns->getValue('pnotexists',null);
    }

}

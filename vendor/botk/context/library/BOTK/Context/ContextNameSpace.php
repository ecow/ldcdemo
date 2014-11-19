<?php
namespace BOTK\Context;

use \InvalidArgumentException;

/**
 * Sanitize and validate a variables 
 * 
 * Usage example:
 *  $serverNameSpace = Context::factory()->ns(INPUT_SERVER);
 *  $ip     = $serverNameSpace->getValue('SERVER_ADDR', Context::MANDATORY, FILTER_VALIDATE_IP);
 *  $port   = $serverNameSpace->getValue('SERVER_PORT', '8080', array (
 *      'filter' => FILTER_VALIDATE_INT,
 *      'flags'  => FILTER_REQUIRE_SCALAR,
 *      'options'   => array('min_range' => 8080, 'max_range' => 8084)
 *  )
 * 
 * You can also use Respect\Validation library for complex validator:
 *  $port = $serverNameSpace->getValue('SERVER_PORT', '8080',v::int()->between(8080,8084))
 * 
 * You can validate and sanitize in one call:
 * 
 *  $port = $serverNameSpace->getValue('SERVER_PORT', '8080', v::int()->between(8080,8084),FILTER_SANITIZE_NUMBER_INT);
 * 
 * You can use Context helper hortcuts:
 *  $method = $serverNameSpace->getValue('REQUEST_METHOD', 'GET', $c->ENUM('GET|POST'))
 * 
 * Note that reurned value can be a scalar or an array.
 */
class ContextNameSpace
{
    const MANDATORY = null;                 // just syntactic sugar for null value
       
    protected $varStore = array();
    
    /**
     * 
     * @param $theArray can be an array or a reference to a predefinite array (es. $_POST)
     */
    public function __construct( $theArray )
    {
        $this->varStore = $theArray;
    }
    
    
    /**
     * Sanitize and validate a variable contained in a name space
     *
     * @param string $varName       the name of a variable defined in the namespace
     * @param mixed  $default       a default value if variable is not set. NULL means that
     *                              the variable is mandatory.
     * @param mixed  $validator     Can be:
     *                                1) null use default validation
     *                                2) an integer representing a simple Validate filter without flags and options
     *                                3) an array representing a Validate filter with flags and/or options
     *                                4) an instance of an object that expose function 'assert'.
     *                                   you can use istance of Respect\Validation\Validator

     * @param mixed  $sanitizer     Can be:
     *                                A) null if no sanitization is required
     *                                B) an integer representing a simple Sanitize filter without flags and options
     *                                C) an array representing a Sanitize filter with flags and/or options
     *                                D) a callable that accept an argument (the source) and return sanitized one 
     * 
     * @throws InvalidArgumentException if $varName is not defined and $default not provided
     * @throws Exception if validator fails to validate variable (depending from the validator)
     * @throws InvalidArgumentException if sanitize fails
     * 
     * @return mixed the sanitized value associated to varName in namespace
     * 
     * LIMITS:
     *  sanitizer are supported just on scalar values
     *  non scalar value require a custom validator like Respect\Validator
     * 
     */
    public function getValue (
        $varName,
        $default = null,
        $validator=null,
        $sanitizer=null
    )
    {
        // prepare a static array to validate validators and sanitizer filters   
        static $validFilters = null;  //load once runtime
        if (is_null($validFilters)){
            // prepare a list of valid filters: do once 
            $validFilters= array();
            $filters = filter_list(); 
            foreach($filters as $filter_name) { 
                $validFilters[filter_id($filter_name)] = $filter_name; 
            }
            // Remove unsupported filters:
            foreach( array(FILTER_VALIDATE_BOOLEAN,FILTER_CALLBACK ) as $badFilter){
                unset($validFilters[$badFilter]);
            }
        }
        
        // Ensure var exists or a default value is present
        if (!$varName
            || (is_null($default) && !isset($this->varStore[$varName])))  {
            throw new InvalidArgumentException("Missing mandatory parameter $varName.",400);                
        }
     
        $sourceValue = isset($this->varStore[$varName])?$this->varStore[$varName]:$default;
        $scalarValue = is_scalar($sourceValue);

        
        // sanitize the variable value (if requested )
        if (is_null($sanitizer)) {
            // CASE A: sanitizing not required
            $sanitizedValue = $sourceValue;
        } elseif (is_int($sanitizer) && array_key_exists($sanitizer,$validFilters) && $scalarValue) {          
            // CASE B: Use a simple sanitize filter
            $sanitizedValue = filter_var($sourceValue, $sanitizer);
        } elseif (is_array($sanitizer) 
            && isset($sanitizer['filter']) 
            && array_key_exists($sanitizer['filter'],$validFilters)
            && $scalarValue
        ) {
            // CASE C: use sanitizer filter with flags and/or options
            $filter = $sanitizer['filter'];
            $sanitizedValue = filter_var($sourceValue, $filter, $sanitize); // should I unset($sanitizer['filter']) ?
        } elseif (is_callable($sanitizer)) {
            $sanitizedValue = $sanitizer($sourceValue);
        } else {
            throw new InvalidArgumentException("Invalid sanitizer filter on $varName.",400);            
        }
 
        assert(isset($sanitizedValue));
        //Now we are sure that variable has a sanitized value: validate it (if requested )
        
        if (is_null($validator)) {
            // CASE 1: validation not required
            $valid = $scalarValue?filter_var($sanitizedValue):$sanitizedValue;
            $validatorName='DEFAULT';
        } elseif (is_int($validator) 
            && array_key_exists($validator,$validFilters)
            && $scalarValue ) {
            // CASE 2: Use a simple validation filter
            $valid = filter_var($sanitizedValue, $validator);
            $validatorName=$validFilters[$validator];
        } elseif (is_array($validator) 
            && isset($validator['filter']) 
            && array_key_exists($validator['filter'],$validFilters)
            && $scalarValue ){
            // CASE 3: use validation filter with flags and/or options
            $filter = $validator['filter'];
            $valid = filter_var($sanitizedValue, $filter, $validator); // should unset($validator['filter'])?
            $validatorName=$validFilters[$filter];
        } elseif (is_object($validator)) {
            // CASE 4: the object must expose assert() method (like in Respect\Validation library )
            try {
                $validator->assert($sanitizedValue);  // N.B. is up to custom validator test the value type
                $valid=true;
            } catch ( Exception $e){
                $validatorName='OBJECT_VALIDATOR';
                $valid = false;
                $errorMsg=$e->getMessage();
            }
        } else {
            $validatorName='unsupported';
            $valid=false;
        }
        
        // thrown exception if not validate value
        if (false===$valid) {
            if (empty($errorMsg)) {
                $val=$scalarValue?$sanitizedValue:'structured';
                $errorMsg = "Invalid value($val) for $validatorName filter on [$varName]";
            }
            throw new InvalidArgumentException($errorMsg,400);
        }
        
        return $sanitizedValue;
    }

    /*
     * Here are some helpers for  shortcuts
     */
    public function getString($varName,$default = null)
    {
        return $this->getValue($varName,$default, null, FILTER_SANITIZE_STRING);
    }
    
    public function getURI($varName,$default = null)
    {
        return $this->getValue($varName,$default, self::STRING('/.+/'), FILTER_SANITIZE_URL);
    }


         
    /*
     * Here are some helpers for validator shortcuts
     */
     public static function ENUM($wlist)
     {
         return  array(
                'filter'    => FILTER_VALIDATE_REGEXP,
                'options'   => array('regexp' => "/^($wlist)$/")
         );
     }
     
     public static function STRING($pattern)
     {
         return  array(
                'filter'    => FILTER_VALIDATE_REGEXP,
                'options'   => array('regexp' => $pattern)
         );
     }

     public static function FILENAME()
     {
         return  array(
                'filter'    => FILTER_VALIDATE_REGEXP,
                'options'   => array('regexp' => '/^[\w,\s-]+\.[A-Za-z]+$/')
         );
     }  


     public static function POSITIVE_INT()
     {
         return  array(
                'filter'    => FILTER_VALIDATE_INT,
                'options'   => array("min_range"=>1)
         );
     }


     public static function NON_NEGATIVE_INT()
     {
         return  array(
                'filter'    => FILTER_VALIDATE_INT,
                'options'   => array("min_range"=>0)
         );
     }  
}

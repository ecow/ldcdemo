<?php
namespace BOTK\Context;

use BOTK\Context\ContextNameSpace;


class Context
{
    const LOCAL = 'LOCAL_VARS';
    
    
    protected $configDir = '../configs';    // default place where to search for ini file
    protected $nameSpaces = array();        // contains namespaces

    
    public static function factory(array $localVars = array())
    {
        return new static($localVars);
    }
    
    
    public function __construct( array $localVars = array())
    {
        // register predefined name space
        $this->nameSpaces = array(
            INPUT_POST      => new ContextNameSpace($_POST),
            INPUT_GET       => new ContextNameSpace($_GET),
            INPUT_ENV       => new ContextNameSpace($_ENV),
            INPUT_SERVER    => new ContextNameSpace($_SERVER),
            self::LOCAL     => new ContextNameSpace($localVars)
            // This is supposed a RESTfull toolkit ... no COOKIE, no SESSION no GLOBALS by design :-) 
        );
        
        // initialize external namespace technology
        $this->configureIniStore();         // this is for namespace maintained in ini file
    }
    
    
    /*
     * Where to find config files
     * Fall back priorities
     *  case 1) use the one provided as paramether
     *  case 2) check the one provided in BOTK_CONFIGDIR environment variable
     *  case 3) use ../configs relative to application script file
     */
    public function configureIniStore( $configDir=null)
    {
        $this->configDir = $configDir                                           // case 1)
            ? $configDir
            : ( isset($_ENV['BOTK_CONFIGDIR'])                                    // case 2
                ? $_ENV['BOTK_CONFIGDIR'] 
                : (dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/configs')
               ); // case 3
                
        return $this;
    }
    

    private function loadArrayFromIniFile($name)
    {
        static $inifilesDB = array();   // a database of variables loaded from .ini files
        
        if (!isset($inifilesDB[$name])) {
            // Let error management take care of errors...
            $iniFile = "$this->configDir/$name.ini";
            $inifilesDB[$name]= file_exists($iniFile)
                ?parse_ini_file($iniFile, true)
                :array();
            if (!is_array($inifilesDB[$name])){
                throw new \InvalidArgumentException("Invalid configuration file in $iniFile.",404);                
            }            
        }
        
        return $inifilesDB[$name];
    }
    
    /*
     * $nameSpace can be:
     *  . a predefined name space idenfifier (INPUT_POST, INPUT_GET, INPUT_ENV,INPUT_SERVER )
     *  . a string containing a file name that mst exists in configDir with .ini extension
     * 
     */
    public function ns($nameSpace)
    {
        // If ! exist try to load a configuration file 
        if ( !isset($this->nameSpaces[$nameSpace]) ) {
            // short sanitize of nameSpace
            if (!filter_var( $nameSpace,FILTER_VALIDATE_REGEXP, 
                array('options'=>array('regexp' => '/^[\w]+$/')))) {
                    throw new InvalidArgumentException("Invalid configuration name.",400);
            }
            $this->nameSpaces[$nameSpace] = new ContextNameSpace($this->loadArrayFromIniFile($nameSpace));
        }
        
        return $this->nameSpaces[$nameSpace];
    } 
    
   /*
     * This helper tryes to return the canonical uri of current http requests.
     * It is inspired by Joomla JURI:base() implementation
     * 
     * Tested with apache server.
     */
    public function guessRequestCanonicalUri()
    {
        $server = $this->ns(INPUT_SERVER);
        
        // Determine if the request was over SSL (HTTPS).
        if( (strtolower($server->getValue('HTTPS','off')) != 'off')) {
            $schema = 'https://';
            $defaultPort=443;
        } else {
            $schema = 'http://';
            $defaultPort=80;           
        }
        
        $port = ($server->getValue('SERVER_PORT',$defaultPort, ContextNameSpace::NON_NEGATIVE_INT()));
        $http_host = $server->getValue('HTTP_HOST','localhost');        
        $authority = ($port===$defaultPort)
            ?($http_host)
            :($http_host . ':'. $port);
                
        return $schema.$authority.$this->guessRequestRelativeUri();
    }


   /*
     * This helper tryes to return the relative uri of current http requests.
     * We need this since in IIS we do not have REQUEST_URI to work with, we will assume we are
     * and so will therefore need to work some magic with the SCRIPT_NAME 
     * 
     * Tested with apache server.
     */
    public function guessRequestRelativeUri()
    {
        $server = $this->ns(INPUT_SERVER);
        
        $php_self = $server->getValue('PHP_SELF','');
        $request_uri = $server->getValue('REQUEST_URI','');
         
        if (!empty($php_self) && !empty($request_uri))
        {
            $theURI = $request_uri;
        }
        else
        {
            // Since we do not have REQUEST_URI to work with, we will assume we are
            // running on IIS and will therefore need to work some magic with the SCRIPT_NAME and
            // QUERY_STRING environment variables.
            $script_name = $server->getValue('SCRIPT_NAME','');
            $query_string = $server->getValue('QUERY_STRING','');
            

            // IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
            $theURI = $script_name;

            // If the query string exists append it to the URI string
            if (!empty($query_string))
            {
                $theURI .= '?' . $query_string;
            }
        }
        
        return $theURI;
    }
}

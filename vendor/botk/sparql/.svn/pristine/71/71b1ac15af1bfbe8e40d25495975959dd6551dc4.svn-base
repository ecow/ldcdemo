<?php
namespace BOTK\Sparql;

use EasyRdf_Http,
    EasyRdf_Http_Response,
    EasyRdf_Exception,
    EasyRdf_Http_Client;
use Guzzle\Http\Client;


class EasyRdfUtils
{
    public static function makeHttpClient($options=array())
    {
        $client = new HttpClient;
        $client->setGuzzleClientOptions($options);
        
        return $client;
    }

    
    public static function useGuzzleClient($options=array())
    {
        return EasyRdf_Http::setDefaultHttpClient(
            EasyRdfUtils::makeHttpClient($options)
        );        
    }
 
    
    public static function useIdentity($username,$password)
    {
        $options = array(
            'request.options' => array(
                'auth'  => array('username', 'password', 'Basic'),
            )
        );
        return EasyRdfUtils::useGuzzleClient($options);   
    }
}


class HttpClient extends EasyRdf_Http_Client
{
    protected $guzzleOptions = array();
    protected $guzzleClient = null;

    protected function getGuzzleClient()
    {
        if (!isset($this->guzzleClient)) {
            $this->guzzleClient = New Client(null,$this->guzzleOptions);
        }
        
        return $this->guzzleClient;
    }
    
    
    public function setGuzzleClientOptions(array $options)
    {
        $this->guzzleOptions = $options;
        
        // reset guzzle client
        unset($this->guzzleClient);
        
        return $this;
    }
     
    
    /**
     * Send the HTTP request and return an HTTP response object.
     * This rewrite EasyRdf implementation using Guzzle Client as 
     * request transport.
     *
     * @return EasyRdf_Http_Response
     * @throws EasyRdf_Exception
     */
    public function request($method = null)
    {
        $uri=$this->getUri();
        if (!$uri) {
            throw new EasyRdf_Exception(
                "Set URI before calling EasyRdf_Http_Client->request()"
            );
        }

        if ($method) {
            $this->setMethod($method);
        }
        $this->redirectCounter = 0;
        $response = null;
        
        $request = $this->getGuzzleClient()->createRequest(
            $this->getMethod(),
            $uri,
            isset($this->headers)?$this->headers:null,
            isset($this->rawPostData)?$this->rawPostData:null
        );
        
        if (!empty($this->paramsGet)) {
            $request->getQuery()->merge($this->paramsGet);
        }

        // return response as EasyRdf_response
        return EasyRdf_Http_Response::fromString( $request->send()->getMessage() );
    }
    
}

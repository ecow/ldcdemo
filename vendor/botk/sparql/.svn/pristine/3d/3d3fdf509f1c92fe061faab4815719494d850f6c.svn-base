<?php
namespace BOTK\Sparql;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Plugin\Backoff\BackoffPlugin,
    Guzzle\Plugin\Backoff\TruncatedBackoffStrategy,
    Guzzle\Plugin\Backoff\HttpBackoffStrategy,
    Guzzle\Plugin\Backoff\CurlBackoffStrategy,
    Guzzle\Plugin\Backoff\ExponentialBackoffStrategy;


class SparqlClient extends Client
{
    const   DEFAULT_PAGE_SIZE = 350,
            DEFAULT_PAYLOAD_SIZE = 1000000; // two MB
          
    /**
     * Creates a backoff retry plugin to be used as default.
     */
    public static function getDefaultBackoff()
    {
        static $backoff = null;
        
        // lazy creation a default backoff plugin
        if (is_null($backoff)) {
            $backoff = new BackoffPlugin(
                // Retry failed requests up to 3 times if it is determined that the request can be retried
                new TruncatedBackoffStrategy(3,
                    // Retry failed requests with 500-level responses
                    new HttpBackoffStrategy(array(500, 503, 509),
                        // Retry failed requests due to transient network or cURL problems
                        new CurlBackoffStrategy(null,
                            // Retry requests that failed due to expired credentials
                            new ExponentialBackoffStrategy()
                        )
                    )
                )
            );
        }
        
        return $backoff;
    }
       
    /**
     * Creates and configures a Sparql Service Client
     *  Config can be an array or a string with sparql ep uri
     */
    public static function factory($config=array())
    {        
        $config = Collection::fromConfig(
            $config, 
            array(),                // defauls
            array('endpoint_url')   // mandatory
        );

        // Create a new client
        $client = new self($config->get('endpoint_url'), $config);
        
        // Make sure the user agent is prefixed by the BOTK version
        $client->setUserAgent('BOTK/4', true);

        // setup default backoff plugin;
        $backoffFactory=$config->get('default_backoff_factory');
        if (is_null($backoffFactory)) {
            $backoff = static::getDefaultBackoff();
        } elseif (is_callable($backoffFactory)) {
            $backoff = call_user_func($backoffFactory);
        }
        
        if( $backoff instanceof BackoffPlugin ) {
            $client->addSubscriber($backoff);
        }
        
        return $client;
    }

    /*****************************************
     * Helpers
     *****************************************/
    public static function _($url)
    {
        return static::factory(array('endpoint_url'=>$url));
    } 
               
     public function rawQuery($query, $accept='text/turtle')
     {
         return $this->getCommand('rawQueryCmd', get_defined_vars())->execute();
     }
    
    
     public function query($query)
     {
        return $this->getCommand('queryCmd', get_defined_vars())->execute();
     }
     
     
     public function update($query)
     {
        return $this->getCommand('updateCmd', get_defined_vars())->execute();
     }
     
          
     public function iQuery($query, $limit=null, $page_size=null)
     {
        if (is_null($limit)) $limit = 0;
        if (is_null($page_size)) $page_size = self::DEFAULT_PAGE_SIZE;
        $cmdOptions = array( 'query' => $query);
        $iteratorOptions = array(
            'limit'     => $limit,
            'page_size' => $page_size,
        );
        return $this->getIterator('queryCmd', $cmdOptions, $iteratorOptions);
     }
}


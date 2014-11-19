<?php
namespace BOTK\Sparql\Command;

use Guzzle\Service\Command\AbstractCommand;
use \EasyRdf_Namespace,
    \EasyRdf_Format;


Abstract class AbstractSparqlCmd extends AbstractCommand
{
    /**
     * Prepend prefixes to the query and url encode it according SPARQL 1.1 protocol
     */ 
    protected function prepareQuery($query=null)
    {
        if (is_null($query)) $query ='';
        
        // build sparql preamble with known prefixes
        $namespaces = array_merge(
            EasyRdf_Namespace::namespaces(),
            is_array($this['namespaces'])?$this['namespaces']:array()
        );
        $prefixes = '';
        foreach ($namespaces as $prefix => $uri) {
            if (strpos($query, "$prefix:") !== false and
                strpos($query, "PREFIX $prefix:") === false) {
                $prefixes .=  "PREFIX $prefix: <$uri>\n";
            }
        }      
        
        return 'query='.urlencode($prefixes . $query);        
    }
    
    
    /**
     * Set request Accept Header according SPARQL 1.1 protocol best practices or use
     * the preferences given in client configuration
     */
    protected function setContentNegotiation($accept=null)
    {
        if (is_null($accept)) {
            $accept = EasyRdf_Format::getHttpAcceptHeader(
                array(
                  'application/sparql-results+json' => 1.0,
                  'application/sparql-results+xml' => 0.8
                )
            );
        }
        $this->request->setHeader('Accept', $accept);   
    }


    /**
     * Sets reasonin profile in sparql query. This is still a legacy feature
     */
    protected function setReasonigProfile($reasoningProfile)
    {
        switch ($reasoningProfile) {
            case 'NONE':
            case 'RDFS':
            case 'QL':
            case 'RL':
            case 'EL':
            case 'DL':
            case 'SL':
                $this->request->setHeaders('SD-Connection-String', 'reasoning='.$reasoningProfile);                
                break;
        }
    }
}

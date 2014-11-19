<?php
namespace BOTK\Sparql\Command;

class RawQueryCmd extends AbstractSparqlCmd
{   
    /**
     * Build and configure an http request processing a Sparql query
     */
    protected function build()
    {   
        $encodedQuery = $this->prepareQuery($this['query']);

        // Use GET if the query is less than 2kB
        // 2046 = 2kB minus 1 for '?' and 1 for NULL-terminated string on server
        if (strlen($encodedQuery) + strlen($this->client->getBaseUrl()) <= 2046) {
            // Create the request property of the command
            $this->request = $this->client->get('?'.$encodedQuery);
        } else {
            // Fall back to POST instead (which is un-cacheable)
            $this->request = $this->client->post()
                 ->setBody($encodedQuery, 'application/x-www-form-urlencoded');
        }
        
        $this->setContentNegotiation($this['accept']);
        $this->setReasonigProfile($this['reasoning_profile']);
    }
    
    protected function process()
    {
        $this->result = $this->getResponse()->getBody();
    }
}

<?php
namespace BOTK\Sparql\Command;

use EasyRdf_Utils,
    EasyRdf_Sparql_Result,
    EasyRdf_Graph,
    EasyRdf_Exception;
    
    
class QueryCmd extends RawQueryCmd
{
    
    /**
     * Parse sparql respons accorning SPARQL specification.
     * It implements same algorithm in EasyRdf_Sparql_Client
     */   
    protected function process()
    {      
        $response = $this->getResponse();
        
        if ($response->getStatusCode() == 204) {
            // No content
            $this->result = '';
        } elseif ($response->isSuccessful()) {
            list($type, $params) = EasyRdf_Utils::parseMimeType($response->getContentType());
            if (strpos($type, 'application/sparql-results') === 0) {
                $this->result = new EasyRdf_Sparql_Result($response->getBody(true), $type);
            } else {
                $this->result = new EasyRdf_Graph($response->getEffectiveUrl(), $response->getBody(true), $type);
            }
        } else {
            throw new EasyRdf_Exception(
                "HTTP request for SPARQL query failed: ".$response->getBody(true)
            );
        }
    }
}

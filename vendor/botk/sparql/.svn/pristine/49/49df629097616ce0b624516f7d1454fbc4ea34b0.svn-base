<?php
namespace BOTK\Sparql\Iterator\BOTK\Sparql\Command;

use Guzzle\Service\Resource\ResourceIterator;
use BOTK\Sparql\SparqlClient;
use EasyRdf_Sparql_Result,
    EasyRdf_Graph,
    EasyRdf_Exception;

/**
 * Iterate over a queryCMD command
 */
class QueryCmdIterator extends ResourceIterator
{
    // Iteration state variables
    protected $sparqlLimit  = SparqlClient::DEFAULT_PAGE_SIZE,
              $payloadSize  = 0;
    
    // Query qualification          
    protected $queryHasLimitOffsetClause = null;
    
    // Iteraror statistics:
    protected $totalPayloadSize = 0,
              $totalItems       = 0,
              $maxPayloadSize   = 0,
              $maxPageSize      = 0,
              $initialTime      = null,
              $initialMemory    = null;
    
    public function getIterationStats() 
    {
        return array(
            'bytes_received'                    => $this->totalPayloadSize,
            'number_of_sparql_items_requested'  => $this->totalItems,
            'max_payload_size'                  => $this->maxPayloadSize,
            'max_page_size'                     => $this->maxPageSize,
            'request_count'                     => $this->getRequestCount(),
            'memory_footprint'                  => memory_get_usage() - $this->initialMemory,
            'time'                              => microtime(true) - $this->initialTime,
        );
    }
    
    
    protected function optimizeSparqlLimit()
    {
        $pageSize = $this->get('page_size');
        $autotune = !$this->get('noautotune_page_size');
        if (!$payloadSize = $this->get('payload_size')){
             $payloadSize=SparqlClient::DEFAULT_PAYLOAD_SIZE;
        }
                
        if ($this->totalItems && $autotune ){
            // Optimize  page size looking to statistics
            $avgSizeOfElement = $this->totalPayloadSize / $this->totalItems;
            $pageSize = floor($payloadSize/$avgSizeOfElement);
        }
        
        // disallow non positive values
        if ($pageSize < 1) $pageSize = SparqlClient::DEFAULT_PAGE_SIZE;
        
        return $pageSize;
    }
    
    
    protected function updateStats()
    {
        $this->payloadSize = intval($this->command->getResponse()->getBody()->getContentLength());
        
        $this->totalPayloadSize += $this->payloadSize;
        $this->totalItems += $this->sparqlLimit;  // Last iteration this is wrong
        $this->maxPayloadSize= max($this->maxPayloadSize,$this->payloadSize);
        $this->maxPageSize= max($this->maxPageSize,$this->sparqlLimit);
   
        return $this;
    }
  
        
    protected function sendRequest()
    {
        $this->nextToken = intval($this->nextToken); // 0 for first interation
        
        if (!$this->nextToken){
            // Things to do just before the first iteration:

            // test if sparql query contains LimitOffsetClause
            $this->queryHasLimitOffsetClause = preg_match('/\s(limit|offset)\s/i', $this->command['query']);
            
            // Initialize memory and time counter
            $this->initialMemory = memory_get_usage();
            $this->initialTime   = microtime(true);
        }

        // If LimitOffsetClause if not present rewrite query adding it
        // Note that if LimitOffsetClause is present, the usage of an iterator does not make sense
        if (!$this->queryHasLimitOffsetClause) {
            $this->sparqlLimit=$this->optimizeSparqlLimit();
            $this->command['query'] .= ' LIMIT '. $this->sparqlLimit . ' OFFSET ' . $this->nextToken;
        }
        
        // Execute the command 
        $result = $this->command->execute();
        $this->updateStats();

        // Decide if iterate another time setting nextToken
        // There are some reasons to stop interation:
        //  .) If original command query contains LimitOffsetClause 
        //  .) query return less than $this->sparqlLimit elements
        //  .) If result is an ASK query result (i.e. $result->getBoolean() returns a boolean)
        //  .) If result is an CONSTRUCT or DESCRIBE query result that return empty graph.
        //  .) Sparql query offset exceeds   iterator hard limit (if any)
        //
        // Note that If selec query returns exactly $this->sparqlLimit result,
        // guess that another page is available..
        $resultIsGraph = ($result instanceof EasyRdf_Graph);
        $hardLimit=intval($this->get('limit')); // 0 if not set..
        $this->nextToken = (
                $this->queryHasLimitOffsetClause || 
                (!$resultIsGraph && $this->sparqlLimit != count($result)) ||
                (!$resultIsGraph && !is_null($result->getBoolean())) || 
                ($resultIsGraph  && $result->isEmpty() ) ||
                ($hardLimit && $this->nextToken > $hardLimit)
            )
            ?false
            :$this->nextToken+$this->sparqlLimit;

        //echo "{$this->command['query']} - payload size {$this->payloadSize}<br>";
        
        // Iterator must always return an array
        return $resultIsGraph?array($result):$result;
    }
}
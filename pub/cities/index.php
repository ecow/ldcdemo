<?php
require '../../vendor/autoload.php';

use BOTK\Core\EndPointFactory,          // Creates end-point
    BOTK\Core\ErrorManager,             // Controls errors
    BOTK\Core\EndPoint,					// Routes requests
    BOTK\Context\Context,				// get config vars and other inputs
	BOTK\Context\ContextNameSpace as V, // Input validation
	BOTK\Sparql\SparqlClient;			// sparql client

    
class Demo extends EndPoint
{

    protected function setRoutes()
    {
        $this->get('/', function(){
        	
			// fetch and validate inputs
			$ns = Context::factory()->ns(INPUT_GET);		
			$term	= $ns->getValue('term', '', V::STRING('/.{2,}/'), FILTER_SANITIZE_STRING);
			$lang	= $ns->getValue('lang', '*', null, FILTER_SANITIZE_STRING);
			$list	= $ns->getValue('list', 10, V::POSITIVE_INT(),FILTER_SANITIZE_NUMBER_INT);
																		
			// Create a new sparql client:
			$sparql = SparqlClient::factory (array (
			  'endpoint_url' 	=> 'https://hub1.linkeddata.center/ekb/demo/sparql',
			  'request.options' => array(
			      'auth' => array('demo', 'demo', 'Basic')			// enable http basic autentication
			  )
			));
			
			// define sparql query
			$query="
				SELECT ?label WHERE {
					GRAPH ?g {
						?s a <http://dbpedia.org/class/yago/Municipality108626283> ;
						   <http://www.w3.org/2000/01/rdf-schema#label> ?label .
					}
					FILTER(LANGMATCHES(LANG(?label), '$lang') && STRSTARTS(LCASE(?label), '$term'))
				} LIMIT $list
			";
			$solutions = $sparql->query($query);
			
			// create a simple json array from retrived solutions
			$result = array();
			foreach ($solutions as $row) {
				$result[] = $row->label->getValue();
			}
			
			return json_encode($result,JSON_PRETTY_PRINT);
		});
    }
}


try {                                                      
    echo EndPointFactory::make('Demo')->run();
} catch ( Exception $e) {
    echo ErrorManager::getInstance()->render($e); 
}

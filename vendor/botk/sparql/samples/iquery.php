<?php
/**
 * This examples shows the usage of Guzzle Iterator applied
 * to sparql command.
 * This iterates on dbpedia resource slicing response in payloads
 * of about 1MB each.
 *
 * @copyright  Copyright (c) 2013 Enrico Fagnoni 
 * @license    http://unlicense.org/
 */
require '../vendor/autoload.php';

use BOTK\Sparql\SparqlClient;

set_time_limit (60);

// create iterator
$results =$sparql = SparqlClient::_('http://dbpedia.org/sparql')
    ->iQuery('select * where { ?s ?p ?o }', 10000);

// fetch all results
$i=0;foreach($results as $result) { $i++;}

echo "<h1>Retrived $i triples</h1>";
echo "<pre>",print_r($results->getIterationStats(),true),"</pre>";

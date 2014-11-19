<?php
/**
 * BOTK agent to do SPARQL queries to a remote SPARQL end point. 
 *
 * @copyright  Copyright (c) 2013 Enrico Fagnoni 
 * @license    http://unlicense.org/
 */
require '../vendor/autoload.php';

use BOTK\Sparql\SparqlClient;

echo SparqlClient::_('http://dbpedia.org/sparql')
    ->query('select * where { ?s ?p ?o } LIMIT 10','text/turtle')
    ->dump('html');

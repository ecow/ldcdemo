<?php
/**
 * BOTK agent to do SPARQL queries to a remote SPARQL end point. 
 *
 * @copyright  Copyright (c) 2013 Enrico Fagnoni 
 * @license    http://unlicense.org/
 */
require '../vendor/autoload.php';

use BOTK\Sparql\EasyRdfUtils;

EasyRdfUtils::useGuzzleClient();

$sparql = new EasyRdf_Sparql_Client('http://dbpedia.org/sparql');
echo $sparql->query('select * where { ?s ?p ?o } LIMIT 10')->dump('html');

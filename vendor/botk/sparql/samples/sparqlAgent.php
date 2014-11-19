<?php
/**
/**
 * BOTK agent to do SPARQL queries to a remote SPARQL end point. 
 * It accepts variable content negotiation policies depending from the passed query content.
 *
 * @copyright  Copyright (c) 2013 Enrico Fagnoni 
 * @license    http://unlicense.org/
 */
require '../vendor/autoload.php';

use BOTK\Sparql\SparqlClient;

echo SparqlClient::factory(array(
  'endpoint_url' => $_REQUEST['endpoint'],
  'request.options' => array(
      'auth' => array($_REQUEST['username'], $_REQUEST['password'], 'Basic')
  ),
  'command.options' => array(
      'reasoning_profile'   => $_REQUEST['reasoner']
  )
))
->query($_REQUEST['query'])
->dump('html');

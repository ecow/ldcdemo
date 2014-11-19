<?php
namespace BOTK\Sparql\Command;


class UpdateCmd extends AbstractSparqlCmd
{   
    protected function build()
    {   
        $encodedQuery = $this->prepareQuery($this['query']);
        $this->request = $this->client->post()
                 ->setBody($encodedQuery, 'application/x-www-form-urlencoded');
        $this->setReasonigProfile($this['reasoning_profile']);
    }
}

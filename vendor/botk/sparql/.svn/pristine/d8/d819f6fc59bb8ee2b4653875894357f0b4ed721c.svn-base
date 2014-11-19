<?php
require '../vendor/autoload.php';
?>
<html>
    <body>
      <form method="get" action="sparqlAgent.php">
        <h2>Use sparql client identity</h2>
        <label for="username" id="label_for_username">Username: </label>
        <input type="text" name="username" id="username" size="40" />
        <label for="password" id="label_for_password">Password: </label>
        <input type="password" name="password" id="password" value="" />
        <h2>Sparq query</h2>
        <label for="endpoint" id="label_for_endpoint">Endpoint: </label>
        <input type="text" name="endpoint" id="endpoint" value="http://dbpedia.org/sparql" size="80" />
        <br/>
        <label for="reasoner" id="label_for_reasoner">Try reasoning profile:</label>
        <select name="reasoner" id="reasoner">
          <option selected="selected" value="NONE">No reasoning features requested</option>
          <option value="RDFS">RDF schema axioms</option>
          <option value="QL">OWL 2 QL axioms</option>
          <option value="RL">OWL 2 RL axioms</option>
          <option value="EL">OWL 2 EL axioms</option>
          <option value="DL">OWL 2 DL axioms</option>
          <option value="SL">Stardog legacy reasoner</option>
        </select>
        <details><summary>predefined namespaces</summary>
          <code>
<?php
            foreach(EasyRdf_Namespace::namespaces() as $prefix => $uri) {
                print "\tPREFIX $prefix: &lt;".htmlspecialchars($uri)."&gt;<br />\n";
            }
?>             
          </code>
        </details>
        <textarea name="query" id="query" cols="80" rows="10">select * where { ?s ?p ?o } LIMIT 10</textarea>
        <br />
        <input type="reset" value="Reset" /><input type="submit" value="Submit" />
      </form>
    </body>
</html>
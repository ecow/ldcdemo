#ldcdemo project
----------------

The objective of this project is to show how easy is to build an autocomplete form based on a knownedge base hosted by [LinkedData.Center](http://linkeddata.center/).

The html/javascript part is based on [jQueryUI autocomplete](http://jqueryui.com/autocomplete/), the server script is based on [BOTK](http://ontology.it/tools/botk) toolkit.

##Background
[linkeddata.center](http://linkeddata.center/) allows you to create and host a knoledge base populated from Linked (Open) data, from  private data or from any combination of both.

The knowledge base exposes a [sparql end-point](http://www.w3.org/TR/sparql11-query/) full compliant with the last [W3C semantic web standards](http://www.w3.org/standards/semanticweb/)

Because is not always easy to understend the power of SPARQL and Semantic Web technologies in day-by-day programming, I provided a simple example that solves a 
very general and frequent problem: autocomplete an input field selecting data from a large dataset.

Suppose that you want use jQueryUi autocomplete feature allowing the user to select one city from a list of all municipalities in the world, and suppose that you want this list available
in different languages. You got a big problem: to populate and maintain the big data set needed by the autocomplete script.
 
Here is where the Semantic Web do the magic: you can use [Dbpedia](http://dbpedia.org) to access the full knowledge contained in Wikipedia!

Unfortunately dbpedia is a great public service that does non ensure any SLA, very often the services is down for maintenance and you can't know when this happens.
This is not acceptable if you want to build a solid application.

A reasonable solution is to copy the data you need from dbpedia to your own knowlege base system, so you can safely use it in your application. 

This is where [linkeddata.center](http://linkeddata.center/) service plays its role.

It gives you an easy way to populate and host your private
RDF knowledge base getting and updating data from any public and private source (dbpedia in this case). 
You can create data mashup, apply rules, data inferences and many other features.

In this project I use the **demo** knowledge base that is available at 
https://hub1.linkeddata.center/ekb/demo/sparql endpoint.
The *demo* knoledge base is populated starting form a very simple data set described in a 
[simple html file](http://demo.hub1.linkeddata.center/data/abox.html).
For more information about how to populate a knowledge base, please refer to LinkedData.Center site. 

##Requirements and test
All *pub* directory must be published in a web server that supports php 5 (with curl extension ).
Beside this, in order to run the code you need:

  - the [composer](https://getcomposer.org/) php dependency manager.
  - git and subversion to clone required componens
  
If you like devop approach :

  - install [vagrant](https://docs.vagrantup.com/v2/installation/) and [virtual box](https://www.virtualbox.org/) on your workstation.
  - clone this project in directory of your workstation and cwd in it
  - open a shell and type the command `vagrant up`. A new virtual machine with all needed tools will be ready and running in few minutes.
  - point your browser to http://localhosts:8080/demo to call your first demo api.
  - to destroy your virtual host just type `vagrant destroy` in shell windows

##The server side script
[jQueryUi remote autocomplete] (http://jqueryui.com/autocomplete#remote) requires a 
server script file. 
The script that search municipalities in wikipedia is provided in pub/cities/index.php file. Here is the script usage:

```
 http://your_endpoint_path/cities?term=[&list=10][&lang=*]
```

Mandatory parameters:
  - **term**: filter for auto complention. Search is enabled if you provide at least two chars. 

Optional parameters:

  - **list**: maximum number of items returned. Default 10, max 100, min 1
  - **lang**: preferred language using the two chars international coding standard. Default is * (means everything).

Example:

the resource:

`http://localhost:8080/demo/cities?term=am&list=3&lang=en` 

will return:

```json
[ "American River, South Australia", "Amatenango de la Frontera", "Amatenango del Valle" ]
```

##The client side html code
The client Html and javascript is contatined in pub/index.html file.

##Reuse this approach
Please note that you can extend this approach to query any data in billons of linked data sources
(public or private) in just three steps:

 1. add the required dataset to the abox list in your linkeddata.center endpoint;
 2. start a learn job; 
 3. create your domain specific api to allow your application to access data
 
 To improve performances you can add cache at server side script.

##Support
Where possible, I will try and provide support for this project, you can get in touch with me via email [e.fagnoni at e-artspace.com]
Or feel free to open an issue and I'll do my best to help.

##Credits
lcddemo makes use of a *lot* of awesome open source projects. I started out trying to list all of them but it was taking way too much time so check out
[botk](http://ontology.it/tools/botk).

##License
This project is licensed under the GPL v3, see here for the full license in LICENSE file.


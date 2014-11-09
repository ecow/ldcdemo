#ldcdemo project
----------------

The objective of this project is to show how easy is to build an autocomplention systems based on a semantic search on a automatically 
populated knownedge base hosted by [LinkedData.Center](http://linkeddata.center/) services.

The server side implementation is based on [BOTK](http://ontology.it/tools/botk) toolkit, the client part is based on [jQueryUI autocomplete](http://jqueryui.com/autocomplete/)

##Background
[linkeddata.center](http://linkeddata.center/) allows you to create and host a knoledge base populated from Linked (Open) data, from  private data or from any combination of both.

The knowledge base exposes a [sparql end-point](http://www.w3.org/TR/sparql11-query/) full compliant with the last [W3C semantic web standards](http://www.w3.org/standards/semanticweb/)

Because SPARQL and Semantic Web technologies require some backgrond knowledge, I provided an easy-to-understand example that solves a 
very general and frequent problem: autocomplete an input field selecting data from a large dataset.

Suppose that you just want use jQueryUi autocomplete feature allowing the user to select his/her city from a list of all municipalities in the world using his/her preferred language.
In this case, the big problem is to populate and maintain the big data set needed by the autocomplete script.
 
Here is where Linked Open Data do the magic: you can use [Dbpedia](http://dbpedia.org) to access the full knowledge contained in Wikipedia!

Unfortunately dbpedia is a great public service that does non ensure any SLA, very often the services is down for maintenance and you can't know when this happens.
This is not acceptable if you want to build a solid application.

A reasonable solution is to copy the data you need from dbpedia to your own knowlege base system, so you can safely use it in your application. 

This is where [linkeddata.center](http://linkeddata.center/) service plays its role.

It give you an easy way to populate and host your private
knowledge base getting and updating data from any public and private source (for instance dbpedia), 
create data mashup, apply rules, data inferences and many other features.

In this project I use the **demo** knowledge base that is available at 
https://hub1.linkeddata.center/ekb/demo/sparql endpoint.
The *demo* knoledge base is populate starting form a very simple data set described in a 
pulic [simple html file](http://demo.hub1.linkeddata.center/data/abox.html).
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
[jQueryUi remote autocomplete] (http://jqueryui.com/autocomplete/#remote) requires a 
server script file. 
The script that search municipalities in wikipedia is provided in pub/cities/index.php. Here is the script usage:

```
 http://your_endpoint_path/cities?term=[&list=10][&lang=*]
```

Mandatory parameters:
  - **term**: filter for auto complention. Search is enabled if you provide at least two chars.  Default empty.

Optional parameters:

  - **list**: maximum number of items returned. Default 10, max 100, min 1
  - **lang**: preferred language using the two chars international coding standard. Default en.

Example call:

`http://localhost:8080/demo/cities?search=am&list=3&lang=en` 

will return:

```json
[ "American River, South Australia", "Amatenango de la Frontera", "Amatenango del Valle" ]
```

##jquery example
Point jour browser to `http://localhost:8080/demo/`. Have a look to html source.

##Reuse this approach
Please note that you can extend this approach to query any data in other billons of linked data sources
(public or private) in just three steps:

  1 add the required dataset to the abox list in your linkeddata.center endpoint;
  2 start a learn job; 
  3 create your domain specific api to allow your application to access data.

##Support
Where possible I will try and provide support for this project, you can get in touch with me via email [e.fagnoni at e-artspace.com]
Or feel free to open an issue and I'll do my best to help.

##Credits
lcddemo makes use of a *lot* of awesome open source projects that have saved me a lot of time in its development. I started out trying to list all of them but it was taking way too much time so check out
[botk](http://ontology.it/tools/botk).

##License
This project is licensed under the GPL v3, [see here for the full license](https://github.com/ecow/lcddemo/LICENSE)


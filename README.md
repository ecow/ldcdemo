#ldcdemo project
----------------

The objective of this project is to show how easy is to build an autocomplention systems based on a semantic search on a automatically 
populated knownedge base hosted by [LinkedData.Center](http://linkeddata.center/) services.

This project provides RESTful web services based on [BOTK](http://ontology.it/tools/botk) toolkit.

##Background
[linkeddata.center](http://linkeddata.center/) allows you to create and host a knoledge base populated from (Linked (Open)) data,
freely available on the web, from  private data or from any conbination of both source types.

The knowledge base exposes a [sparql end-point](http://www.w3.org/TR/sparql11-query/) full compliant with the last [W3C semantic web standards](http://www.w3.org/standards/semanticweb/)

Because SPARQL and Semantic Web technologies require some backgrond knowledge, I provided some easy-to-understand examples that solve a 
very general and frequent problem: autocomplete an input field using from a specific dataset.

Ok boy, what's the problem? 
The main problem is to populate and maintain the data set needed for the autocomplention, without any effort!

Suppose that you just want to get a list of all countries in the world. 
Using a standard approach you need create  some records in a relational db and create a SQL query.
This could be quite easy if just need english names, but if you would like to get all country 
names expressed in any language (english, italian, arab, chinese, etc) the db become hard to  manage.

...and if you want to autocomplete a field from a list of all municipalities in the word using the preferred user language?

Here is where Linked Open Data do the magic! You can use [Dbpedia](http://dbpedia.org) to access the full crown knowledge of all Wikipedia!

Unfortunately dbpedia is a great public service that does non ensure any SLA, very often the services is down for maintenance and you can't know when this happens.
This is not acceptable if you want to build a solid application.

A reasonable solution is to copy the data you need from dbpedia to your own knowlege base system, so you can safely use it in your application. 
This is where [linkeddata.center](http://linkeddata.center/) service plays its role. 
It give you an easy way to populate and host your private
knowledge base getting and update data from any public and private source (for instance dbpedia), 
create data mashup, apply rules, data inferences and many other features.

In this project I use the **demo** knowledge basethat is available at 
http://hub1.linkeddatacenter/ekb/demo/sparql endpoint.
The *demo* knoledge base is populate starting form a very simple data set described in a 
pulic [simple plain html file](http://demo.hub1.linkeddata.center/data/abox.html).

The demo end point is accessible using "demo" as username and "demo" password. 
For information about how to populate a knowledge base, please refer to LinkedData.Center site. 

This project implements a simple proxy to the demo sparql end point 
that you can call in any web front end using standard jQuery methods to realize a 
simple field autocomplention.

##Requirements and test
All *pub* directory must be published in a web server that supports php 5 (with curl extension ).
Beside this, in order to run the code you need:

  - the [composer](https://getcomposer.org/) php dependency manager.
  - git and subversion to clone required componens
  
If you like devop approach:

  - install [vagrant](https://docs.vagrantup.com/v2/installation/) and [virtual box](https://www.virtualbox.org/) on your workstation.
  - clone this project and cwd in it
  - open a shell window and put the command `vagrant up`. A new virtual machine with all needed tools will be ready and running in few minutes.
  - point your browser to http://localhosts:8080/api/index.php/test to call your first demo api.
  - to destroy your virtual host just type `vagrant destroy` in shell windows

##Api Usage
```
 http://your_endpoint_path/cities?search=[&list=10][&lang=*]
```
search municipalities in wikipedia

Mandatory parameters:
  - **search**: filter for auto complention. Search is enabled if you provide at least two chars.  Default empty.

Optional parameters:

  - **list**: maximum number of items returned. Default 10, max 100, min 1
  - **lang**: preferred language using the two chars international coding standard. Default en.

Example:

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


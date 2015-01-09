#ldcdemo project
----------------
The objective of this project is to build a simple autocomplete html form based on data extracted from wikipedia.  
See [an on-line installation available in E-Artspace demo site](http://demo.e-artspace.com/ldc/pub/).

The html/javascript part is based on [jQueryUI autocomplete](http://jqueryui.com/autocomplete/), the server script is based on [BOTK](http://ontology.it/tools/botk) toolkit.
Wikipedia knowledge is distilled by [Dbpedia](http://dbpedia.org) and hosted by [LinkedData.Center](http://linkeddata.center/).


##Background

Because is not always easy to understend the power of SPARQL and of Semantic Web technologies in day-by-day programming, I provided a simple example that solves a 
very general and frequent problem: autocomplete an input field selecting data from a large dataset.

Suppose that you want use jQueryUi autocomplete feature allowing the user to select one city from a list of all municipalities in the world, and suppose that you want this list available
in different languages. You got a big problem: to populate and maintain the big data set needed by the autocomplete script.
 
Here is where the Semantic Web does the magic: you can use [Dbpedia](http://dbpedia.org) to access the full "Wisdom of the crowd" contained in Wikipedia and use it to get a list of all municipalities in the word, translated in any language!

Unfortunately dbpedia is a great public service but it does non ensure any SLA, the performaces are unpredictable and very often the services is down for maintenance and you can't know when this happens.
This is not acceptable if you want to build a solid application based directly on such service.

A reasonable solution is to copy the data you need from dbpedia to your own knowlege base system, so you can safely use it in your application. 

This is where [linkeddata.center](http://linkeddata.center/) service plays its role.
It allows you to quicly create and host a knoledge base populated from Linked Open Data, from  private data or from any combination of both. In this project we use just dbpedia.
LinkedData.Centers exposes a dedicated and password protected [sparql end-point](http://www.w3.org/TR/sparql11-query/) full compliant with the last [W3C semantic web standards](http://www.w3.org/standards/semanticweb/).
You can create data mashup, apply rules, data inferences and many other features.

In this project I use the **demo** knowledge base that is available at 
https://hub1.linkeddata.center/ekb/demo/sparql endpoint.


The *demo* knoledge base is populated starting form a very simple data set described in a 
[simple html file](http://demo.hub1.linkeddata.center/data/abox.html).
For more information about how to populate a knowledge base, please refer to LinkedData.Center site. 

## Test in a local environment using Vagrant (suggested)

These instructions allow to install and test the project on your local workstation using some simple virtualization technologies:

  - install [vagrant](https://docs.vagrantup.com/v2/installation/) and [virtual box](https://www.virtualbox.org/) on your workstation.
  - clone this project in directory of your workstation and cwd in it
  - open a shell and type the command `vagrant up`. A new virtual machine with all needed tools will be ready and running in few minutes.
  - point your browser to http://localhosts:8080/demo to call your first demo api.
  - to destroy your virtual host just type `vagrant destroy` in shell windows

You should get locally the same results available in [E-Artspace demo site](http://demo.e-artspace.com/ldc/pub/).
 
## Install on your PHP web server

   - Publish the project in a web server that supports php 5 (with curl extension ).

The provision script contained in Vagrant file will give an idea of a complete api installation on a ubuntu 14.04 box.

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

[Try in demo site](http://demo.e-artspace.com/ldc/pub/cities?term=am&list=3&lang=en).

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
I have to thank a *lot* of awesome open source projects that were suggested by [BOTK](http://ontology.it/tools/botk):

 - BOTK Core and BOTK SPARQL packages by E-Artspace.
 - Rest by Alexandre Gomes Gaigalas
 - Mimeparse by Joe Gregorio
 - Guzzle] by Michael Dowling
 - EasyRDF by Nicholas J Humfrey
 - Composer by Nils Adermann, Jordi Boggiano
 
And, of course, PHP and JQuery community.

##License
This project is licensed under the GPL v3, see here for the full license in LICENSE file.
Find details and licenses of embedded software in the Vendor directory that will be created doring deploy process.


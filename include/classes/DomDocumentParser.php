<?php

 class DomDocumentParser {

    private $doc;

 
    public function __construct($url){

       
        $options = array('http' => array('method'=>"GET", 'header'=>"User-Agent: mablooBot/0.1\n"));
        $context = stream_context_create($options);


        //creating init php built DomDocument, that let performe action on webpages
        $this->doc = new DomDocument();
        //the @ sign supress the error recieve from the loadHTML( the error in this case are of no value)
        @$this->doc->loadHTML(file_get_contents($url, false, $context));

    
  

    }

    public function getLinks(){
        return $this->doc->getElementsByTagName("a"); // using the DomDocument to get the link for the http webpage
    }

    public function getTitletags(){
        return $this->doc->getElementsByTagName("title");
    }

    public function getMetaTags(){
        return $this->doc->getElementsByTagName("meta");
    }

    public function getImages(){
        return $this->doc->getElementsByTagName("img"); // using the DomDocument to get images for the http webpage
    }

     
 }// End of class







?>
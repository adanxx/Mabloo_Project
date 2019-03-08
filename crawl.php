<?php
  
  require_once "include/config.php";
  require_once "include/classes/DomDocumentParser.php";

  
  $alreadyCrawled = array();
  $crawling = array();
  $ImageAlreadyFound = array();

  function linksExists($url){
    global $conn;
    
    $query = $conn->prepare("SELECT * FROM sites WHERE url = :url");
      $query->bindParam(":url", $url);
 
    if(!$query->execute()){
      echo $query->errorInfo();
    }

    return $query->rowCount() != 0;

  }

  function insertLinks($url, $title,$description,$keywords){
    global $conn;
    
    $query = $conn->prepare("INSERT INTO sites(url, title, description, keywords)
                         VALUES(:url, :title, :description, :keywords)");
        $query->bindParam(":url", $url);
        $query->bindParam(":title", $title);
        $query->bindParam(":description", $description);
        $query->bindParam(":keywords", $keywords);
   
    if(!$query->execute()){
      echo $query->errorInfo();
    }else{
      return true;
    }

    
  }

  function createLink($src, $url){

    $scheme = parse_url($url)["scheme"]; //http or https
    $host = parse_url($url)["host"]; //www.?.com
     
    // if the link contains 2 slash, use th parse function to convert to link with schema conversation attribute function
    if(substr($src, 0, 2) == "//"){
      $src =$scheme . ":" . $src; 
    }
    else if((substr($src, 0, 1) == "/")){
      $src = $scheme . "://" . $host . $src; //https://schema.host/source-link
    }
    else if((substr($src, 0, 2) == "./")){
      $src = $scheme . "://" . $host . dirname(parse_url($url)['path']) . substr($src,1);
    }
    else if((substr($src, 0, 3) == "../")){
      $src = $scheme . "://" . $host . "/" . $src;
    }
    else if((substr($src, 0, 5) !== "https") && (substr($src, 0, 4) !== "http")){
      $src = $src = $scheme . "://" . $host . "/" . $src;
    }

    return $src;
  }

  function insertImage($url, $src, $alt, $title){
    global $conn;
    
    $query = $conn->prepare("INSERT INTO images(siteUrl, imageUrl, alt, title)
                         VALUES(:siteUrl, :imageUrl, :alt, :title)");
        $query->bindParam(":siteUrl", $url);
        $query->bindParam(":imageUrl", $src);
        $query->bindParam(":alt", $alt);
        $query->bindParam(":title", $title);
   
    if(!$query->execute()){
      echo $query->errorInfo();
    }

    return $query;

  }

  function getDetails($url){

    global $ImageAlreadyFound;

    $parser = new DomDocumentParser($url);

    $titleArray  = $parser->getTitletags(); 
   
    if(sizeof($titleArray) == 0 || $titleArray->item(0) == NULL ){
      return;
    }
   
    $title = $titleArray->item(0)->nodeValue; //get the very first element in array
    $title = str_replace("\n","", $title); //replace an new line with empty-string

    if($title == ""){
      return;
    }

    $description = "";
    $keywords   = "";

    $metaArray = $parser->getMetaTags();

    foreach ($metaArray as $meta) {
     
      if($meta->getAttribute("name") == "description"){
        $description = $meta->getAttribute('content');
      }

      if($meta->getAttribute("name") == "keywords"){
        $keywords = $meta->getAttribute('content');
      }
    }

    $description = str_replace("\n","", $description);
    $keywords = str_replace("\n","", $keywords);

    
    if(linksExists($url)){
      echo "Already Exists<br>";
    }
    else if(insertLinks($url,$title,$description,$keywords)){
      echo "Success: $url<br>";
    }
    else{
      echo "Error: failed to insert $url into db<br>";
    }

    $ImageArray = $parser->getImages();
     foreach ($ImageArray as $img) {

        $src = $img->getAttribute("src");
        $alt = $img->getAttribute("alt");
        $title = $img->getAttribute("title");

   

        if(!$title && !$alt){
          continue;
        }
        
          
        $src = createLink($src, $url);


        if(!in_array($src, $ImageAlreadyFound)){
          
          $ImageAlreadyFound[]= $src;

          //insert image int db;
          insertImage($url, $src, $alt, $title);
        
        }


      
      }

 
  }

  function followlinks($url){

    global $alreadyCrawled;
    global $crawling;
    
    $parser = new DomDocumentParser($url);

    $linkList = $parser->getLinks();


     foreach ($linkList as $link ) {
      $href = $link->getAttribute("href"); // retrive the href link-tag from the a-tag element

      echo $href . "<br>";
        
      // // if found empty link with pound-sign skip to th next ;
      if(strpos($href, "#") !== false){ 
        continue;
      } 
      //if link contain 11-character length javascript word skip it;
      else if(substr($href, 0, 11) == "javascript:"){ 
       continue;
      } 

      $href = createLink($href, $url);

      if(!in_array($href, $alreadyCrawled)){
        $alreadyCrawled [] = $href;
        $crawling [] = $href;

        //insert $href
        getDetails($href);
      }
      // else{
      //   return;   // TURN ON: for getting "PARTIAL" crawl result bit faster from the webstie 
      // } 
        


    }
    
    array_shift($crawling); //remove the elment in the first-position

    //recusive function resending the 
    foreach ($crawling as $site) {
      followlinks($site);

      echo $crawling . "<br>";
    }

  }

  $startUrl =  "https://unsplash.com/";
  followLinks($startUrl);


?>
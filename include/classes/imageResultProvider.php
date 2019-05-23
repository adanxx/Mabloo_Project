<?php

 class ImageResultProvider{

   private $conn;

    public function __construct($conn){
      $this->conn =$conn; 
    }

    public function getNumResults($term){
     
        $query = $this->conn->prepare("SELECT count(*) as total from images WHERE (title LIKE :term OR alt LIKE :term) AND broken = 0");
        
        $searhTerm = "%". $term ."%";
        $query->bindParam(":term", $searhTerm);

        if(!$query->execute()){
        echo $query->errorInfo();
        exit;
        }       

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    
    }

    public function getResultHtml($page, $pageSize, $term){

        $fromLimit = ($page - 1) * $pageSize;
     
        $query = $this->conn->prepare("SELECT * from images 
                                        WHERE (title LIKE :term
                                        OR alt LIKE :term)
                                        AND broken = 0
                                        ORDER BY click DESC
                                        LIMIT :fromlimit, :pageSize");

        $searhTerm = "%". $term ."%";
        $query->bindParam(":term", $searhTerm); 
        $query->bindParam(":fromlimit", $fromLimit, PDO::PARAM_INT); 
        $query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT); 

        if(!$query->execute()){
            echo $query->errorInfo().__LINE__;
            exit;
        }       

        $resultHtml = "<div class='imageResults'>"; 
        
        $count = 0;

        while( $row = $query->fetch(PDO::FETCH_ASSOC)){

            $count++;

            $id = $row['id'];
            $imageUrl = $row['imageUrl'];
            $siteUrl = $row['siteUrl'];
            $title = $row['title'];
            $alt = $row['alt'];

            if($title){
                $displayText = $title;
            }
            else if($alt){
                $displayText = $alt;
            }
            else{
                $displayText = $imageUrl;
            }

     
            $resultHtml .= "<div  class='gridItem image$count'>
                               <a href='$imageUrl' data-fancybox data-caption='$displayText' data-siteurl='$siteUrl'>
                                 <script>
                                 
                                  $(document).ready(function(){
                                      loadImage(\"$imageUrl\", \"image$count\");
                                  });
                                 </script>
                                  <span class='details'>$displayText</span>
                               </a>
                           </div>";
          
        }

        $resultHtml .="</div>";

        return $resultHtml;
    }

 






 }
 ?>
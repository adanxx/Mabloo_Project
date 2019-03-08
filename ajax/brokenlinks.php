<?php
 require_once("../include/config.php");

 
 if(isset($_POST['src'])){

    try {
        
      $sourc = $_POST['src'];

      $query = $conn->prepare("UPDATE images SET broken= 1 WHERE imageUrl=:src");
      $query->bindParam(":src", $sourc); 

      if(!$query->execute()){

          echo $query->getMessage();
      }

      echo 1;

    } catch (PDOExecption $th) {
        echo $th->getMessage();
    }

  }else{
      echo "Ajax-Error: 0" .__LINE__;
  }




?>
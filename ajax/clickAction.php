<?php
  require_once("../include/config.php");

  if(isset($_POST['id'])){

    try {
        
      $linkId = $_POST['id'];

      $query = $conn->prepare("UPDATE sites SET clicks= clicks+1 WHERE id=:id");
      $query->bindParam(":id", $linkId); 

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
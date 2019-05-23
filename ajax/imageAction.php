<?php

require_once("../include/config.php");

if(isset($_POST['src'])){

  try {
      
    $src = $_POST['src'];

    $query = $conn->prepare("UPDATE images SET click = click+1 WHERE imageUrl=:src");
    $query->bindParam(":src", $src); 

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
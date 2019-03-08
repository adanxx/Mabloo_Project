<?php
 ob_start(); // turn on output buffering, saves the data  when once finished running:

 try {

    $host     = "localhost"; 
    $db       = "mabloo";
    $root     = "root";
    $password = "";

    $conn = new PDO("mysql:dbname=$db;host=$host",$root, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
    
 } catch (PDOExecption $th) {
    echo "Connection Error: ". $th->getMessage();
 }

?>
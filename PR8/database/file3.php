<?php
$host="localhost";
$username="root";
$password=null;
$database="college";
try{
    $conn=new PDO("mysql:host=$host;dbname=$database",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo"Connection Successful";
    echo"<br/>";
    $result=$conn->query("SHOW TABLES");
    $tables=$result->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
}
catch(PDOException $e)
{
    die("Some error : ".$e->getMessage());
}
?>



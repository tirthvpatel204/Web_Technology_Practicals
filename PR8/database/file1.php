<?php
$host="localhost";
$username="root";
$password=null;
$database="college";

$conn=new mysqli($host,$username,$password,$database);
if($conn->connect_error){
    die("some erro".$conn->$connect_error);
}

echo"connection successful";
echo"<br/>";

$result=$conn->query("show tables")->fetch_all();
print_r($result);

?>
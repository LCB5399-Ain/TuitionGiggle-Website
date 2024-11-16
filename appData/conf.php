<?php

$connect= new mysqli("localhost","root","","tuitioncentre");

if($connect->connect_error){
    exit("Connection Failed: ".$connect->connect_error);

}else{

	echo "Connected successfully";

}

?>
<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "infilearn";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if($conn->connect_error){
    header("Location: error_pages/service500.php");
}
date_default_timezone_set('Asia/Kolkata');
$currentTime = date('d-m-Y h:i:s A', time ()); 
?>
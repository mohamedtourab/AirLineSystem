<?php
/*
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "airlinedatabase";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

$stmt = mysqli_prepare($conn,"INSERT INTO 'airlinedatabase'.'Users' VALUES(?,?);");
if(!$stmt){
    die("Error during INSERT:".mysqli_error());
}
mysqli_stmt_bind_param($stmt,"ss",$userName,$myPassword);
mysqli_stmt_execute($stmt);

*/?>
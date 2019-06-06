<?php
require_once 'Controller.php';

$modelData=array("userName"=>"mohamed", "password"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);

if(isset($_POST['signUpRequest'])){
    $myController->signUp();
}

if(isset($_POST['loginRequest'])){
    $loginResult = $myController->Login();
}

if(isset($_POST['checkSeatState'])){
    $seatState = $myController->checkSelectedSeat();
}

if(isset($_POST['reserveSeatRequest'])){
    $myController->reserveSeat();
}


if(isset($_POST['purchaseSeatRequest'])){
    $myController->purchaseSeat();
}

?>
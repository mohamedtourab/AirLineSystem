<?php
require_once 'Controller.php';

$modelData=array("dbuserName"=>"mohamed", "dbpassword"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);

if(isset($_POST['signUpRequest'])){
    $result = $myController->signUp();
    echo $result;
    exit();
}

if(isset($_POST['loginRequest'])){
    $loginResult = $myController->Login();
    echo $loginResult;
    exit();
}

if(isset($_POST['checkSeatState'])){
    $seatState = $myController->checkSelectedSeat();
    echo $seatState;
    exit();
}

if(isset($_POST['reserveSeatRequest'])){
    $result = $myController->reserveSeat();
    echo $result;
    exit();
}


if(isset($_POST['purchaseSeatRequest'])){
   $result = $myController->purchaseSeat();
   echo $result;
   exit();
}

?>
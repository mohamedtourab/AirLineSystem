<?php
require_once 'Controller.php';

$modelData=array("userName"=>"mohamed", "password"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);

if(isset($_POST['signUpRequest'])){
    $result = $myController->signUp();
    unset($_POST);
    echo $result;
    exit();
}

if(isset($_POST['loginRequest'])){
    $loginResult = $myController->Login();
    unset($_POST);
    echo $loginResult;
    exit();
}

if(isset($_POST['logoOutRequest'])){
    $logoutResult = $myController->logout();
    unset($_POST);
    echo $logoutResult;
    exit();
}
if(isset($_POST['checkSeatState'])){
    $seatState = $myController->checkSelectedSeat();
    unset($_POST);
    echo $seatState;
    exit();
}

if(isset($_POST['reserveSeatRequest'])){
    $result = $myController->reserveSeat();
    unset($_POST);
    echo $result;
    exit();
}

if(isset($_POST['cancelSeatReservation'])){
    $result = $myController->cancelSeatReservation();
    unset($_POST);
    echo $result;
    exit();
}

if(isset($_POST['purchaseSeatRequest'])){
    $result = $myController->purchaseSeat();
    unset($_POST);
    echo $result;
    exit();
}

?>
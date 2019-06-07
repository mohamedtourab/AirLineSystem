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
    $reserveResult = $myController->reserveSeat();
    unset($_POST);
    echo $reserveResult;
    exit();
}

if(isset($_POST['cancelSeatReservation'])){
    $cancelResult = $myController->cancelSeatReservation();
    unset($_POST);
    echo $cancelResult;
    exit();
}

if(isset($_POST['purchaseSeatRequest'])){
    $purchaseResult = $myController->purchaseSeat();
    unset($_POST);
    echo $purchaseResult;
    exit();
}

?>
<?php


require_once 'Controller.php';

$modelData=array("userName"=>"mohamed", "password"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);

$functionsCorrespondingToRequest=array(
    'signUpRequest'=>'signup',
    'loginRequest'=>'Login',
    'logoOutRequest'=>'logout',
    'checkSeatState'=>'checkSelectedSeat',
    'purchaseSeatRequest'=>'purchaseSeat',
    'updateView'=>'updateView'
);

foreach( $functionsCorrespondingToRequest as $request => $function ) {
    if(isset($_POST[$request])){
        $result = $myController->$function();
        unset($_POST);
        echo $result;
        exit();
    }
}


/*require_once 'Controller.php';

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
if(isset($_POST['purchaseSeatRequest'])){
    $purchaseResult = $myController->purchaseSeat();
    unset($_POST);
    echo $purchaseResult;
    exit();
}

if(isset($_POST['updateView'])){
    $updateResult = $myController->updateView();
    unset($_POST);
    echo $updateResult;
    exit();
}

*/
?>
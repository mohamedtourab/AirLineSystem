<?php


require_once 'Controller.php';

$modelData = array("userName" => "mohamed", "password" => "mohamed", "dbName" => "airlinedatabase");
$myController = new Controller($modelData);

$functionsCorrespondingToRequest = array(
    'signUpRequest' => 'signup',
    'loginRequest' => 'Login',
    'logoOutRequest' => 'logout',
    'checkSeatState' => 'checkSelectedSeat',
    'purchaseSeatRequest' => 'purchaseSeat',
    'updateView' => 'updateView'
);

foreach ($functionsCorrespondingToRequest as $request => $function) {
    if (isset($_POST[$request])) {
        $result = $myController->$function();
        unset($_POST);
        echo $result;
        exit();
    }
}

?>
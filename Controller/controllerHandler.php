<?php


require_once 'Controller.php';

$modelData = array("userName" => "s259371", "password" => "elogican", "dbName" => "s259371");
$myController = new Controller($modelData);

$functionsCorrespondingToRequest = array(
    'signUpRequest' => 'signup',
    'loginRequest' => 'Login',
    'logoOutRequest' => 'logout',
    'checkSeatState' => 'checkSelectedSeat',
    'purchaseSeatRequest' => 'purchaseSeat',
    'updateView' => 'updateView',
    'getPlaneInfo'=>'sendPlaneData'
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
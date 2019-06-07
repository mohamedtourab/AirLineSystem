<?php
require_once('../Model/Model.php');


class Controller
{
    var $myModel;

    function __construct($modelData)
    {
        $numberOfColumns = 6;
        $numberOfRows = 10;
        $char = 'A';
        global $myModel;

        if (!$myModel) {
            $myModel = new Model($modelData['userName'],$modelData['password'],$modelData['dbName']);
        }
        for($i=0;$i<($numberOfRows);$i++){
            for($j=0;$j<($numberOfColumns);$j++){
                $myModel->insertSeat(($i+1),$char,'free');
                $char++;
                if($j==$numberOfColumns-1){
                    $char='A';
                }
            }
        }
    }


    function signUp(){

        session_start();
        global $myModel;

        if(isset($_POST['userName'])&&isset($_POST['password'])){
            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
            $myModel->insertUser($postUserName,$postPassword);
             $_SESSION['LAST_ACTIVITY'] = time();
             $_SESSION['CURRENT_USER_NAME'] = $postUserName;
            return $postUserName;
        }

    }


    /**
     * @return bool
     */
    function Login(){

        session_start();
        global $myModel;

        if(isset($_POST['userName'])&&isset($_POST['password'])){
            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
            $result = $myModel->select("SELECT userPassword FROM airlinedatabase.Users WHERE userID = '$postUserName'");
            if($result == null){
                return 'ERROR IN Login';
            }
            $row =  mysqli_fetch_assoc($result);
            $retrievedPassword = $row['userPassword'];
            if($retrievedPassword == $postPassword){
                $_SESSION['LAST_ACTIVITY'] = time();
                $_SESSION['CURRENT_USER_NAME'] = $postUserName;
                return $postUserName;
            }
            else{
                return 'wrong';
            }
        }
    }

    function logout(){
        session_start();
        $_SESSION=array();
        session_destroy();
        return 'Done';
    }

    function checkSelectedSeat(){

        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds

        if (isset($_SESSION['LAST_ACTIVITY']) && ((time() -$_SESSION['LAST_ACTIVITY']) > $timeDuration )) {
            $_SESSION=array();
            unset($_SESSION);
            session_destroy();
            return 'timeout';

        }
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_SESSION['CURRENT_USER_NAME'])){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $result = $myModel->select("SELECT seatState,holdingUser FROM airlinedatabase.Seats WHERE seatRow = '$row' AND seatColumn = '$column'");
            if(!$result){
                return 'ERROR IN checkSeatState';
            }
            $value = mysqli_fetch_assoc($result);
            $retrievedState = $value['seatState'];
            $currentHoldingUser = $value['holdingUser'];
            if($retrievedState=='selected'){
                if($_SESSION['CURRENT_USER_NAME'] == $currentHoldingUser){
                    $retrievedState= 'already_selected';
                }
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            return $retrievedState;
        }


    }
    function cancelSeatReservation(){
        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $_SESSION=array();
            session_destroy();
            return 'timeout';
        }
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_SESSION['CURRENT_USER_NAME']) ){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $reservingUser = null;
            $myModel->updateSeatState('free',$reservingUser,$row,$column);
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }
    function reserveSeat(){

        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $_SESSION=array();
            session_destroy();
            return 'timeout';
        }
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_SESSION['CURRENT_USER_NAME']) ){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $reservingUser = $_SESSION['CURRENT_USER_NAME'];
            $myModel->updateSeatState('selected',$reservingUser,$row,$column);
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    function purchaseSeat(){

        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $_SESSION=array();
            session_destroy();
            return 'timeout';
        }
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_SESSION['CURRENT_USER_NAME']) ){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $purchasingUser = $_SESSION['CURRENT_USER_NAME'];
            $myModel->updateSeatState('purchased',$purchasingUser,$row,$column);
            $_SESSION['LAST_ACTIVITY'] = time();
            return "purchased Successfully";

        }

    }

}



//Test for the database inteface functions
/*
        $result = $myModel->select('SELECT * FROM airlinedatabase.Seats');
        while($row = mysqli_fetch_assoc($result)){
            echo "<br>";
            echo $row['seatRow']." ".$row['seatColumn']." ".$row['seatState']." ".$row['holdingUser'];

        }
        echo "<br>";
        $result = $myModel->select('SELECT * FROM airlinedatabase.Users');
        while($row = mysqli_fetch_assoc($result)){
            echo "<br>";
            echo $row['userID']." ".$row['userPassword'];

        }
        echo "<br>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
        }
            */

?>
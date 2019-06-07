<?php
require_once('../Model/Model.php');



class Controller
{
    var $myModel;
    var $timeDuration = 120; //in seconds

    function __construct($modelData)
    {
        $numberOfColumns = 6;
        $numberOfRows = 10;
        $char = 'A';
        global $myModel;
        $myModel = new Model($modelData['userName'],$modelData['password'],$modelData['dbName']);

        for($i=0;$i<($numberOfRows);$i++){
            for($j=0;$j<($numberOfColumns);$j++){
                $myModel->insertSeat(($i+1),$char,'purchased');
                $char++;
                if($j==$numberOfColumns-1){
                    $char='A';
                }
            }
        }
    }


    function signUp(){

        global $myModel;

        if(isset($_POST['userName'])&&isset($_POST['password'])){
            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
            unset($_POST);
            $myModel->insertUser($postUserName,$postPassword);
            /* session_start();
             $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
             $_SESSION['CURRENT_USER_NAME'] = $postUserName;*/
            return $postUserName;
        }

    }


    /**
     * @return bool
     */
    function Login(){
        global $myModel;

        if(isset($_POST['userName'])&&isset($_POST['password'])){
            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
            unset($_POST);
            $result = $myModel->select("SELECT userPassword FROM airlinedatabase.Users WHERE userID = '$postUserName'");
            if($result == null){
                return 'ERROR IN Login';
            }
            $row =  mysqli_fetch_assoc($result);
            $retrievedPassword = $row['userPassword'];
            if($retrievedPassword == $postPassword){
                /*session_start();
                $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
                $_SESSION['CURRENT_USER_NAME'] = $postUserName;*/
                return $postUserName;
            }
            else{
                return 'wrong';
            }
        }


    }

    function checkSelectedSeat(){
        global $myModel;
        global $timeDuration;

        if (isset($_SESSION['LAST_ACTIVITY']) &&
            ($_SERVER['REQUEST_TIME'] - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            session_unset();
            unset($_POST);
            unset($_SESSION);
            session_destroy();
            return 'timeout';

        }
        if(isset($_POST['row']) && isset($_POST['column'])){
            $row = $_POST['row'];
            $column = $_POST['column'];
            unset($_POST);
            $result = $myModel->select("SELECT seatState FROM airlinedatabase.Seats WHERE seatRow = '$row' AND seatColumn = '$column'");
            if(!$result){
                return 'ERROR IN checkSeatState';
            }
            $value = mysqli_fetch_assoc($result);
            $retrievedState = $value['seatState'];
            $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
            return $retrievedState;
        }


    }

    function reserveSeat(){
        global $myModel;
        global $timeDuration;
        if (isset($_SESSION['LAST_ACTIVITY']) &&
            ($_SERVER['REQUEST_TIME'] - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            session_unset();
            unset($_POST);
            session_destroy();
            return 'timeout';
        }
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_POST['reservingUserName']) ){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $reserveingUser = $_POST['reservingUserName'];
            unset($_POST);
            $myModel->updateSeatState('selected',$reserveingUser,$row,$column);
            $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
        }

    }

    function purchaseSeat(){
        global $myModel;
        global $timeDuration;

        if (isset($_SESSION['LAST_ACTIVITY']) &&
            ($_SERVER['REQUEST_TIME'] - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            session_unset();
            unset($_POST);
            session_destroy();
            return 'timeout';

        }
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_POST['purchaseUserName']) ){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $purchasingUser = $_POST['purchaseUserName'];
            unset($_POST);
            $myModel->updateSeatState('purchased',$purchasingUser,$row,$column);
            $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];

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
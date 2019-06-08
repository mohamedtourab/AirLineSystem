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
        //create an array for seats
        if(!isset($_SESSION['selectedSeats'])){
            $_SESSION['selectedSeats'] = array();
        }

        if (isset($_SESSION['LAST_ACTIVITY']) && ((time() -$_SESSION['LAST_ACTIVITY']) > $timeDuration )) {
            $_SESSION=array();
            unset($_SESSION);
            session_destroy();
            return 'timeout';
        }

        if(isset($_POST['row']) && isset($_POST['column']) && isset($_SESSION['CURRENT_USER_NAME'])){

            $row = $_POST['row'];
            $column = $_POST['column'];
            $seatID = $row.$column;

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
                    $this->cancelSeatReservation($row,$column); //I already was already selecting this seat and now I'm un-selecting it;

                    if (($key = array_search($seatID, $_SESSION['selectedSeats'])) !== false) {
                        unset($_SESSION['selectedSeats'][$key]);
                    }
                }
                else{
                    $this->reserveSeat($row,$column); // this means that the seat was selected by another person and now I'm selecting it again;
                    array_push($_SESSION['selectedSeats'],$seatID);
                }
            }
            else if($retrievedState == 'free'){
                $this->reserveSeat($row,$column);//seat is free so I'm selecting it;
                array_push($_SESSION['selectedSeats'],$seatID);
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            /*for($x = 0; $x < count($_SESSION['selectedSeats']); $x++) {
                echo count($_SESSION['selectedSeats']);
                echo "<br>";
                echo $_SESSION['selectedSeats'][$x];
                echo "<br>";
            }*/
            return $retrievedState;
        }


    }
    function cancelSeatReservation($row,$column){
        global $myModel;;
        $timeDuration = 120; //in seconds
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $_SESSION=array();
            session_destroy();
            return 'timeout';
        }
        if(isset($_SESSION['CURRENT_USER_NAME'])){
            $reservingUser = null;
            $myModel->updateSeatState('free',$reservingUser,$row,$column);
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    function reserveSeat($row,$column){
        global $myModel;
        $timeDuration = 120; //in seconds
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $_SESSION=array();
            session_destroy();
            return 'timeout';
        }
        if(isset($_SESSION['CURRENT_USER_NAME']) ){
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
        if(isset($_SESSION['CURRENT_USER_NAME']) && isset($_SESSION['selectedSeats'])){
            foreach( $_SESSION['selectedSeats'] as $seat ) {
                $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$seat);
                $row = $arr[0];
                $column = $arr[1];
                $purchasingUser = $_SESSION['CURRENT_USER_NAME'];
                $myModel->updateSeatState('purchased',$purchasingUser,$row,$column);
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            return "purchased Successfully";

        }

    }

}

?>
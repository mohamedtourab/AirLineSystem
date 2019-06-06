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
            $myModel->insertUser($postUserName,$postPassword);
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
            $result = $myModel->select("SELECT * FROM airlinedatabase.Users WHERE userID = $postUserName AND userPassword = $postPassword ");
            if(!$result){
                echo "ERROR IN Login";
                return false;
            }
            $row =  mysqli_fetch_assoc($result);
            $retrievedPassword = $row['userPassword'];
            if($retrievedPassword == $postPassword){
                return true;
            }
        }


    }

    function checkSelectedSeat(){
        global $myModel;
        if(isset($_POST['row']) && isset($_POST['column'])){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $result = $myModel->select("SELECT seatState FROM Seats WHERE seatRow = $row AND seatColumn = $column");
            if(!$result){
                echo "ERROR IN checkSeatState";
                return false;
            }
            $value = mysqli_fetch_assoc($result);
            $retrievedState = $value['seatState'];
            return $retrievedState;
        }


    }

    function reserveSeart(){
            global $myModel;
            if(isset($_POST['row']) && isset($_POST['column']) && isset($_POST['reservingUserName']) ){
                $row = $_POST['row'];
                $column = $_POST['column'];
                $reserveingUser = $_POST['reservingUserName'];
                $myModel->updateSeatState('selected',$reserveingUser,$row,$column);
            }

    }

    function purchaseSeat(){
        global $myModel;
        if(isset($_POST['row']) && isset($_POST['column']) && isset($_POST['purchaseUserName']) ){
            $row = $_POST['row'];
            $column = $_POST['column'];
            $purchasingUser = $_POST['purchaseUserName'];
            $myModel->updateSeatState('purchased',$purchasingUser,$row,$column);

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
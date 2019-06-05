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
                $myModel->insertSeat(($i+1),$char,'free');
                $char++;
                if($j==$numberOfColumns-1){
                    $char='A';
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
    }


    function signUp(){
        global $myModel;
        if(isset($_POST['signUpRequest'])){
            if(isset($_POST['userName'])&&isset($_POST['password'])){
                $postUserName = $_POST['userName'];
                $postPassword = $_POST['password'];
                $myModel->insertUser($postUserName,$postPassword);
            }
        }
    }


    /**
     * @return bool
     */
    function Login(){
        global $myModel;
        var $row;
        var $retrievedPassword=null;

        if(isset($_POST['loginRequest'])){
            if(isset($_POST['userName'])&&isset($_POST['password'])){
                $postUserName = $_POST['userName'];
                $postPassword = $_POST['password'];
                $result = $myModel->select("SELECT * FROM airlinedatabase.Users WHERE userID = $postUserName AND userPassword = $postPassword ");
                if(!$result){
                    return false;
                }
                $row =  mysqli_fetch_assoc($result);
                $retrievedPassword = $row['userPassword'];
                if($retrievedPassword == $postPassword){
                    return true;
                }
            }

        }
    }

    function selectSeat(){


    }
    function purchaseSeat(){

    }

}






$modelData=array("userName"=>"mohamed", "password"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);
?>
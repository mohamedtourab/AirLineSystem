<?php
require_once('../Model/Model.php');



class Controller
{

    function __construct($modelData)
    {
        $numberOfColumns = 6;
        $numberOfRows = 10;
        $char = 'A';


        $myModel = new Model($modelData['userName'],$modelData['password'],$modelData['dbName']);

        $postUserName = $_POST['userName'];
        $postPassword = $_POST['password'];
        $myModel->insertUser($postUserName,$postPassword);

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
        echo "<br>";*/
    }

}
$modelData=array("userName"=>"mohamed", "password"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);
?>
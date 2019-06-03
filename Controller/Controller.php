<?php
require_once('../Model/Model.php');



class Controller
{
    function __construct($modelData)
    {
        $myModel = new Model($modelData['userName'],$modelData['password'],$modelData['dbName']);

        $postUserName = $_POST['userName'];
        $postPassword = $_POST['password'];
        $myModel->insert($postUserName,$postPassword);
        $myModel->updateSeatState('purchased',$postUserName,1,'a');
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
    }

}
$modelData=array("userName"=>"mohamed", "password"=>"mohamed", "dbName"=>"airlinedatabase");
$myController = new Controller($modelData);
?>
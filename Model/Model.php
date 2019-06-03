<?php

class Model
{
    protected $password;
    protected $dbName;
    protected $user;
    protected $conn;
    function __construct($userName,$Password,$DbName)
    {
        global $conn;
        $this->user = $userName;
        $this->password = $Password;
        $this->dbName = $DbName;

        $conn = mysqli_connect('localhost',$this->user,$this->password,$this->dbName );

        if(!$conn){
            die('failed to connect to MySQL: '.mysqli_connect_error() );
        }
        echo "Connection successfully";
    }

    function select($query){
        global $conn;
        $result = mysqli_query($conn,$query);
        if(!$result){
            die("Error in SELECT query: ".mysqli_error());
        }
        return $result;
    }

    function insert($userName,$myPassword){
        global $conn;
        $stmt = mysqli_prepare($conn,"INSERT INTO Users VALUES(?,?);");
        if(!$stmt){
            die("Error during INSERT:".mysqli_error());
        }
        mysqli_stmt_bind_param($stmt,"ss",$userName,$myPassword);
        mysqli_stmt_execute($stmt);
    }

}
?>

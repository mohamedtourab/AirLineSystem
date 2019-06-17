<?php

class Model
{
    var $password;
    var $dbName;
    var $user;
    var $conn;

    function __construct($userName, $Password, $DbName)
    {
        global $conn;
        $this->user = $userName;
        $this->password = $Password;
        $this->dbName = $DbName;

        $conn = mysqli_connect('localhost', $this->user, $this->password, $this->dbName);

        if (!$conn) {
            die('failed to connect to MySQL: ' . mysqli_connect_error());
        }
    }

    function select($query)
    {
        global $conn;
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Error in SELECT query: " . mysqli_error($conn));
        }
        return $result;
    }

    function insertUser($userName, $myPassword)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, "INSERT INTO airlinedatabase.Users VALUES(?,?);");
        if (!$stmt) {
            die("Error during INSERT:" . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "ss", $userName, $myPassword);
        mysqli_stmt_execute($stmt);
    }


    function insertSeat($seat_row, $seat_column, $seat_state)
    {

        global $conn;
        $stmt = mysqli_prepare($conn, "INSERT INTO airlinedatabase.Seats(seatRow,seatColumn,seatState) VALUES(?,?,?);");
        if (!$stmt) {
            die("Error during INSERT Seat:" . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "iss", $seat_row, $seat_column, $seat_state);
        mysqli_stmt_execute($stmt);
    }

    function updateSeatState($seat_state, $holding_user, $row, $column)
    {
        global $conn;
        $stmt1 = mysqli_prepare($conn, "UPDATE airlinedatabase.Seats SET seatState = ? WHERE seatRow = ? and seatColumn = ? ");
        $stmt2 = mysqli_prepare($conn, "UPDATE airlinedatabase.Seats SET holdingUser = ? WHERE seatRow = ? and seatColumn = ? ");
        if (!$stmt1) {
            die("Error during UPDATE Seat state:" . mysqli_error($conn));
        }
        if (!$stmt2) {
            die("Error during UPDATE holding user:" . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt1, "sis", $seat_state, $row, $column);
        mysqli_stmt_bind_param($stmt2, "sis", $holding_user, $row, $column);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_execute($stmt2);

    }

    function disableAutoCommit()
    {
        global $conn;
        mysqli_autocommit($conn, false);
    }

    function commitQuery()
    {
        global $conn;
        mysqli_commit($conn);
    }
    function sanitizeString($myString){
        global $conn;
        return mysqli_real_escape_string($conn,$myString);
    }

}

?>

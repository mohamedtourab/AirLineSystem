<?php
require_once('../Model/Model.php');

// TODO add after each wrong response session termination if needed
class Controller
{
    var $myModel;

    function __construct($modelData)
    {

        $numberOfColumns = 6;
        $numberOfRows = 10;
        if (!(isset($_POST['numberOfColumns']) && isset($_POST['numberOfRows']))) {
            $_POST['numberOfColumns'] = $numberOfColumns;
            $_POST['numberOfRows'] = $numberOfRows;
        }
        $char = 'A';
        global $myModel;

        if (!$myModel) {
            $myModel = new Model($modelData['userName'], $modelData['password'], $modelData['dbName']);
            for ($i = 0; $i < ($numberOfRows); $i++) {
                for ($j = 0; $j < ($numberOfColumns); $j++) {
                    $myModel->insertSeat(($i + 1), $char, 'free');
                    $char++;
                    if ($j == $numberOfColumns - 1) {
                        $char = 'A';
                    }
                }
            }
            $myModel->insertUser('u1@p.it', password_hash('p1', PASSWORD_DEFAULT));
            $myModel->insertUser('u2@p.it', password_hash('p2', PASSWORD_DEFAULT));
        }
    }


    function signUp()
    {

        session_start();
        global $myModel;

        if (isset($_POST['userName']) && isset($_POST['password'])) {

            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
            $myModel->disableAutoCommit();
            $result = $myModel->select("SELECT * FROM airlinedatabase.Users WHERE userID = '$postUserName' FOR UPDATE");

            if (mysqli_num_rows($result) > 0) {
                $_SESSION = array();
                session_destroy();
                $myModel->commitQuery();
                return 'AlreadyTaken';
            }
            $hashedPassword = password_hash($postPassword, PASSWORD_DEFAULT);
            $myModel->insertUser($postUserName, $hashedPassword);
            $myModel->commitQuery();
            $_SESSION['LAST_ACTIVITY'] = time();
            $_SESSION['CURRENT_USER_NAME'] = $postUserName;
            return $postUserName;
        }

    }


    /**
     * @return bool
     */
    function Login()
    {

        session_start();
        global $myModel;

        if (isset($_POST['userName']) && isset($_POST['password'])) {
            $postUserName = $_POST['userName'];
            $postPassword = $_POST['password'];
            $result = $myModel->select("SELECT userPassword FROM airlinedatabase.Users WHERE userID = '$postUserName'");
            if (!$result) {
                $_SESSION = array();
                session_destroy();
                return 'ERROR IN Login';
            }
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $retrievedPassword = $row['userPassword'];
                if (password_verify($postPassword, $retrievedPassword)) {
                    $_SESSION['LAST_ACTIVITY'] = time();
                    $_SESSION['CURRENT_USER_NAME'] = $postUserName;
                    return $postUserName;
                } else {
                    $_SESSION = array();
                    session_destroy();
                    return 'wrong';
                }
            } else {
                return "wrong";
            }
        }
    }

    function logout()
    {
        session_start();
        if (isset($_SESSION['CURRENT_USER_NAME']) && isset($_SESSION['selectedSeats'])) {
            foreach ($_SESSION['selectedSeats'] as $seat) {
                $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $seat);
                $row = $arr[0];
                $column = $arr[1];
                $this->cancelSeatReservation($row, $column);
            }
        }
        $_SESSION = array();
        session_destroy();
        return 'Done';
    }

    function checkSelectedSeat()
    {
        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds

        if (isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration)) {
            $this->seatCancellationTimeOut();
            $_SESSION = array();
            session_destroy();
            return 'timeout';
        }
        //create an array for seats
        if (!isset($_SESSION['selectedSeats'])) {
            $_SESSION['selectedSeats'] = array();
        }

        if (isset($_POST['row']) && isset($_POST['column']) && isset($_SESSION['CURRENT_USER_NAME'])) {

            $row = $_POST['row'];
            $column = $_POST['column'];
            $seatID = $row . $column;

            $result = $myModel->select("SELECT seatState,holdingUser FROM airlinedatabase.Seats WHERE seatRow = '$row' AND seatColumn = '$column'");
            if (!$result) {
                return 'ERROR IN checkSeatState';
            }
            $value = mysqli_fetch_assoc($result);

            $retrievedState = $value['seatState'];
            $currentHoldingUser = $value['holdingUser'];

            if ($retrievedState == 'selected') {
                if ($_SESSION['CURRENT_USER_NAME'] == $currentHoldingUser) {

                    $retrievedState = 'already_selected';
                    $this->cancelSeatReservation($row, $column); //I already was already selecting this seat and now I'm un-selecting it;

                    if (($key = array_search($seatID, $_SESSION['selectedSeats'])) !== false) {
                        unset($_SESSION['selectedSeats'][$key]);
                    }
                } else {
                    $this->reserveSeat($row, $column); // this means that the seat was selected by another person and now I'm selecting it again;
                    array_push($_SESSION['selectedSeats'], $seatID);
                }
            } else if ($retrievedState == 'free') {
                $this->reserveSeat($row, $column);//seat is free so I'm selecting it;
                array_push($_SESSION['selectedSeats'], $seatID);
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            return $retrievedState;
        }
    }

    function seatCancellationTimeOut()
    {
        if (isset($_SESSION['CURRENT_USER_NAME']) && isset($_SESSION['selectedSeats'])) {
            foreach ($_SESSION['selectedSeats'] as $seat) {
                $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $seat);
                $row = $arr[0];
                $column = $arr[1];
                $this->cancelSeatReservation($row, $column);
            }
        }
    }

    function cancelSeatReservation($row, $column)
    {
        global $myModel;;

        if (isset($_SESSION['CURRENT_USER_NAME'])) {
            $reservingUser = null;
            $myModel->updateSeatState('free', $reservingUser, $row, $column);
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    function reserveSeat($row, $column)
    {
        global $myModel;
        if (isset($_SESSION['CURRENT_USER_NAME'])) {
            $reservingUser = $_SESSION['CURRENT_USER_NAME'];
            $myModel->updateSeatState('selected', $reservingUser, $row, $column);
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    function sendPlaneData()
    {
        if (isset($_POST['getPlaneInfo'])) {
            if (isset($_POST['numberOfColumns']) && isset($_POST['numberOfRows'])) {

                return $_POST['numberOfRows'] . "_" . $_POST['numberOfColumns'];
            }
        }

    }


    function purchaseSeat()
    {

        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds
        $returnResult = "Purchased Successfully";

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $this->seatCancellationTimeOut();
            $_SESSION = array();
            session_destroy();
            return 'timeout';
        }

        //create an array for seats
        if (!isset($_SESSION['purchasedSeats'])) {
            $_SESSION['purchasedSeats'] = array();
        }

        if (isset($_SESSION['CURRENT_USER_NAME']) && isset($_SESSION['selectedSeats'])) {
            $myModel->disableAutoCommit();
            foreach ($_SESSION['selectedSeats'] as $seat) {
                $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $seat);
                $row = $arr[0];
                $column = $arr[1];
                $seatID = $row . $column;
                $purchasingUser = $_SESSION['CURRENT_USER_NAME'];
                //check that the user selected this seat is me before buying it
                $result = $myModel->select("SELECT seatState FROM airlinedatabase.Seats WHERE holdingUser = '$purchasingUser' AND seatRow ='$row' AND seatColumn='$column' FOR UPDATE");

                if (mysqli_num_rows($result) > 0) { //if the seat holding user is me the seatState will be returned from the query and then I buy it
                    $myModel->updateSeatState('purchased', $purchasingUser, $row, $column);
                    array_push($_SESSION['purchasedSeats'], $seatID);
                } else {// nothing returned this means someone else selected the seat so I should cancel the buy and free all the seats
                    $returnResult = "Purchase Failed";
                    //This will cancel all the purchased seats till the seat that made the confliction
                    foreach ($_SESSION['purchasedSeats'] as $seat2) {
                        $arr2 = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $seat2);
                        $row2 = $arr2[0];
                        $column2 = $arr2[1];
                        $this->cancelSeatReservation($row2, $column2);
                    }
                    //This will cancel for all the seats after the seat that made a confliction
                    $queryResult = $myModel->select("SELECT seatRow,seatColumn FROM airlinedatabase.Seats WHERE holdingUser = '$purchasingUser' AND seatState = 'selected'");
                    if (mysqli_num_rows($queryResult) > 0) {
                        while ($returnedRow = mysqli_fetch_assoc($queryResult)) {
                            $mySeatRow = $returnedRow['seatRow'];
                            $mySeatColumn = $returnedRow['seatColumn'];
                            $this->cancelSeatReservation($mySeatRow, $mySeatColumn);
                        }
                    }
                    $_SESSION['purchasedSeats'] = array();
                    $_SESSION['selectedSeats'] = array();
                    $_SESSION['LAST_ACTIVITY'] = time();
                    $myModel->commitQuery();
                    return $returnResult;
                }

            }
            $myModel->commitQuery();
            //Empty the purchased seats and selected seats
            if ($returnResult == "Purchased Successfully" && isset($_SESSION['purchasedSeats']) && isset($_SESSION['selectedSeats'])) {
                $_SESSION['selectedSeats'] = array();
                $_SESSION['purchasedSeats'] = array();
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            return $returnResult;

        }

    }

    function updateView()
    {
        session_start();
        global $myModel;
        $timeDuration = 120; //in seconds
        $myArray = array();

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeDuration) {
            $this->seatCancellationTimeOut();
            $_SESSION = array();
            session_destroy();
            array_push($myArray, ['timeoutRespone' => 'timeout']);
            return json_encode($myArray);
        }

        $result = $myModel->select("SELECT * FROM airlinedatabase.Seats");
        if (!$result) {
            return 'ERROR IN checkSeatState';
        }
        while ($row = mysqli_fetch_assoc($result)) {
            $seatID = $row['seatRow'] . $row['seatColumn'];

            if ($row['seatState'] == 'purchased') {

                array_push($myArray, ['seatID' => $seatID, 'color' => 'Red']);
            } else if ($row['seatState'] == 'free') {

                array_push($myArray, ['seatID' => $seatID, 'color' => 'Green']);
            } else {
                if (isset($_SESSION['CURRENT_USER_NAME']) && $row['holdingUser'] == $_SESSION['CURRENT_USER_NAME']) {

                    array_push($myArray, ['seatID' => $seatID, 'color' => 'Yellow']);
                } else {

                    array_push($myArray, ['seatID' => $seatID, 'color' => 'Orange']);
                }
            }
        }
        $_SESSION['LAST_ACTIVITY'] = time();
        return json_encode($myArray);
    }

}

?>
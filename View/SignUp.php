<?php ?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>AirLine</title>
</head>
<body>
<div id="mainDiv">
    <p id="Title"><h1>Please Sign up</h1></p>
     <div id="divForm">
        <form id="form" name="registerationForm" action="../Controller/Controller.php" method="post">

            <label for="userNameID"><b>UserID</b></label>
            <input id="userNameID" name="userName" type="text" required>
            <br><br>
            <label for="passwordID"><b>Password</b></label>
            <input id="passwordID" name="password" type="password" required>
            <br><br>
            <input type="submit" value="Sign up" name="signUpButton">

        </form>
    </div>
</div>
</body>
</html>


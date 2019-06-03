<?php
require_once('../Model/Model.php');

$myModel = new Model("mohamed","mohamed","airlinedatabase");
$postUserName = $_POST['userName'];
$postPassword = $_POST['password'];
    $myModel->insert($postUserName,$postPassword);


/*class Controller
{
    protected $myModel;
    function __construct()
    {
        global $myModel;
        $myModel = new Model("mohamed","mohamed","airlinedatabase");
    }



}*/
?>
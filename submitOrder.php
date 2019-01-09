<?php
/**
 * Created by M.R.Hosseinpoor.
 * User: admin
 * Date: 1/8/2019
 * Time: 4:42 PM
 */

session_start();
if ((!isset($_SESSION['aemail']) && !isset($_SESSION['temail'])) || (empty($_SESSION['aemail']) && empty($_SESSION['temail'])) || !isset($_POST['id']) || empty($_POST['id']))
    header("location:../login.php");

$db = @mysqli_connect("localhost", "root", "", "ebbroker");
if (!mysqli_connect_error()) {
    $sql = "UPDATE orders SET verify=1 WHERE orderId = " . $_POST['id'];
    $res = mysqli_query($db, $sql);
    if (!mysqli_error($db)) {
        echo "ok";
    }
}
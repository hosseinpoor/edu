<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/16/2018
 * Time: 11:05 AM
 */

session_start();

if (isset($_SESSION['aemail']) && !empty($_SESSION['aemail'])) {
    $db = @mysqli_connect("localhost", "root", "", "ebbroker");

    function download($name)
    {
        if (!mysqli_connect_error()) {

            mysqli_query($GLOBALS['db'], "SET NAMES utf8");
            header('Content-Disposition: attachment; filename=' . $name . '.xls');
            header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
            header('Pragma: no-cache');
            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            echo pack("CCC",0xef,0xbb,0xbf);
        } else {
            echo "<script>
                alert('error in connecting to DB. please try again later');
                window.location.href='../login.php';
                </script>";
        }
    }
} else
    header("location:../login.php");
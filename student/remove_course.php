<?php
session_start();
include_once("../strings.php");
if (isset($_SESSION['semail']) && !empty($_SESSION['semail'])) {


    if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['mail']) && !empty($_POST['mail'])) {
        $db = @mysqli_connect("localhost", "root", "", "ebbroker");
        if (!mysqli_connect_error()) {

            $sql1 = "UPDATE orders SET active=0 WHERE courseId = " . $_POST['id'] . " AND studentMail = '" . $_POST['mail'] . "'";
            $result1 = mysqli_query($db, $sql1);

            $sql2 = "INSERT INTO orders (courseId, StudentMail, status, active, date) VALUES (" . $_POST['id'] . " , '" . $_POST['mail'] . "' , 0 , 1 , CAST(' " . date("Y-m-d") . "' AS DATE ))";
            $result2 = mysqli_query($db, $sql2);

            if ($result1 && $result2) {
                echo $course_removed;
                $s = "UPDATE orders SET status=1  WHERE courseId = " . $_POST['id'] . " AND active=1 AND status=3 ORDER BY orderId DESC limit 1";
                $r = mysqli_query($db, $s);
            } else {
                echo $course_remove_error . mysqli_error($db);
            }
        } else {
            echo $db_error;
        }
    }
} else {
    header("location:../login.php");
}

?>

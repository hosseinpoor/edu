<?php
session_start();
if (isset($_SESSION['semail']) && !empty($_SESSION['semail'])) {

    if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['mail']) && !empty($_POST['mail'])) {

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");
        if (!mysqli_connect_error()) {
            $sql1 = "UPDATE orders SET active=0 WHERE courseId = " . $_POST['id'] . " AND studentMail = '" . $_POST['mail'] . "'";
            $result1 = mysqli_query($db, $sql1);

            $disId = "Null";
            if (isset($_POST['code']) && !empty($_POST['code'])) {
                $s = "SELECT discountId FROM discount WHERE code = '" . $_POST['code'] . "' AND courseId = " . $_POST['id'];
                $r = mysqli_query($db, $s);
                $count = mysqli_num_rows($r);
                if ($count == 1) {
                    $res = mysqli_fetch_assoc($r);
                    $disId = $res['discountId'];
                }
            }

            $sql = "INSERT INTO orders (courseId, StudentMail, status, active, date, discountId) VALUES (" . $_POST['id'] . " , '" . $_POST['mail'] . "' , 1  , 1 , CAST(' " . date("Y-m-d") . "' AS DATE )  , " . $disId . " )";
            $result = mysqli_query($db, $sql);
            if ($result && $result1)
                echo "course added";
            else
                echo "error in adding course";
        } else {
            echo "error in connecting to database";
        }
    } else {
        header("location:../login.php");
    }
} else {
    header("location:../login.php");
}

?>
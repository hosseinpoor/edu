<?php
session_start();

function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = //chr(123)// "{"
            ""
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)//.chr(125);// "}"
        ;
        return $uuid;
    }
}

function getSubmitCount($id, $db)
{
    $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 1";
    $result = mysqli_query($db, $sql);
    return mysqli_num_rows($result);
}

$resFile = "";
$disFile = "";
$baseUrl = 'E:\xampp\htdocs\edu\\';

if (isset($_SESSION['semail']) && !empty($_SESSION['semail'])) {

    if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['mail']) && !empty($_POST['mail'])) {

        if (isset($_FILES['disFile'])) {
            if (file_exists($_FILES['disFile']['tmp_name']) || is_uploaded_file($_FILES['disFile']['tmp_name'])) {

                $disFile = "";
                $target_dir = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                if (!file_exists($baseUrl . $target_dir)) {
                    mkdir($baseUrl . $target_dir, 0777, true);
                }
                $info = new SplFileInfo(basename($_FILES["disFile"]["name"]));
                $target_dir = $target_dir . getGUID() . "." . $info->getExtension();
                $target_qr = $baseUrl . $target_dir;

                if (move_uploaded_file($_FILES["disFile"]["tmp_name"], $target_qr)) {
                    $disFile = $target_dir;
                } else {
                    $disFile = "";
                }
            }
        }

        if (isset($_FILES['resFile'])) {
            if (file_exists($_FILES['resFile']['tmp_name']) || is_uploaded_file($_FILES['resFile']['tmp_name'])) {

                $resFile = "";
                $target_dir = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                if (!file_exists($baseUrl . $target_dir)) {
                    mkdir($baseUrl . $target_dir, 0777, true);
                }
                $info = new SplFileInfo(basename($_FILES["resFile"]["name"]));
                $target_dir = $target_dir . getGUID() . "." . $info->getExtension();
                $target_qr = $baseUrl . $target_dir;

                if (move_uploaded_file($_FILES["resFile"]["tmp_name"], $target_qr)) {
                    $resFile = $target_dir;
                } else {
                    $resFile = "";
                }
            }
        }

        $resFile = !empty($resFile) ? "'$resFile'" : "NULL";
        $disFile = !empty($disFile) ? "'$disFile'" : "NULL";

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");
        if (!mysqli_connect_error()) {
            $sql1 = "UPDATE orders SET active=0 WHERE courseId = " . $_POST['id'] . " AND studentMail = '" . $_POST['mail'] . "'";
            $result1 = mysqli_query($db, $sql1);

            $disId = "Null";
            $needFile = 0;
            if (isset($_POST['code']) && !empty($_POST['code'])) {
                $s = "SELECT discountId , needFile FROM discount WHERE code = '" . $_POST['code'] . "' AND courseId = " . $_POST['id'];
                $r = mysqli_query($db, $s);
                $count = mysqli_num_rows($r);
                if ($count == 1) {
                    $res = mysqli_fetch_assoc($r);
                    $disId = $res['discountId'];
                    $needFile = $res['needFile'];
                }
            }

            $s = "SELECT * FROM course WHERE courseId = " . $_POST['id'];
            $r = mysqli_query($db, $s);
            $a = mysqli_fetch_assoc($r);
            $cap = ($a['capacity'] == NULL) ? 999999 : $a['capacity'];

            if ($disId != "NULL" && $needFile == 1) {
                if (!empty($disFile) && $disFile != "NULL") {
                    if (getSubmitCount($_POST['id'], $db) < $cap) {

                        $sql = "INSERT INTO orders (courseId, StudentMail, status, active, date, discountId,file,receipt) VALUES (" . $_POST['id'] . " , '" . $_POST['mail'] . "' , 1  , 1 , CAST(' " . date("Y-m-d") . "' AS DATE )  , " . $disId . " , " . $disFile . " , " . $resFile . " )";
                        $result = mysqli_query($db, $sql);
                        if ($result && $result1) {
                            echo "course added";
                        } else
                            echo "error in adding course";
                    } else {
                        $sql = "INSERT INTO orders (courseId, StudentMail, status, active, date, discountId,file,receipt) VALUES (" . $_POST['id'] . " , '" . $_POST['mail'] . "' , 3  , 1 , CAST(' " . date("Y-m-d") . "' AS DATE )  , " . $disId . " , " . $disFile . " , " . $resFile . " )";
                        $result = mysqli_query($db, $sql);
                        if ($result && $result1)
                            echo "course reserved";
                        else
                            echo "error in reserving course";
                    }
                } else {
                    echo "you have to upload a file";
                }
            } else {
                if (getSubmitCount($_POST['id'], $db) < $cap) {

                    $sql = "INSERT INTO orders (courseId, StudentMail, status, active, date, discountId,receipt) VALUES (" . $_POST['id'] . " , '" . $_POST['mail'] . "' , 1  , 1 , CAST(' " . date("Y-m-d") . "' AS DATE )  , " . $disId . " , " . $resFile . " )";
                    $result = mysqli_query($db, $sql);
                    if ($result && $result1)
                        echo "course added";
                    else
                        echo "error in adding course";
                } else {
                    $sql = "INSERT INTO orders (courseId, StudentMail, status, active, date, discountId,receipt) VALUES (" . $_POST['id'] . " , '" . $_POST['mail'] . "' , 3  , 1 , CAST(' " . date("Y-m-d") . "' AS DATE )  , " . $disId . " , " . $resFile . " )";
                    $result = mysqli_query($db, $sql);
                    if ($result && $result1)
                        echo "course reserved";
                    else
                        echo "error in reserving course";
                }
            }

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
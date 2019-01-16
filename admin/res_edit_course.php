<?php
session_start();
if (!isset($_SESSION['aemail']) || empty($_SESSION['aemail']))
    header("location:../login.php");

$qr = "";
$brochurePath = "";
$titlePath = "";
$brochureName = "";
$titleName = "";
$baseUrl = 'E:\xampp\htdocs\edu\\';

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

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function convertNumbers($srting, $toPersian = false)
{
    $en_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $fa_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    if ($toPersian) return str_replace($en_num, $fa_num, $srting);
    else return str_replace($fa_num, $en_num, $srting);
}

function capIncrease($db){
    $s ="SELECT capacity FROM course WHERE courseId = " . $_GET['id'];
    $r = mysqli_query($db , $s);
    $a = mysqli_fetch_assoc($r);
    $old = (int) $a['capacity'];
    $new = (int) $_POST['cap'];
    return $new - $old;
    if($old < $new){
        return $new - $old;
    }
}

$title_unsafe = test_input($_POST['title']);
$description_unsafe = test_input($_POST['des']);
$startDate_unsafe = convertNumbers(test_input($_POST['startDate']));
$endDate_unsafe = convertNumbers(test_input($_POST['endDate']));
$holdingDays_unsafe = test_input($_POST['holdingDays']);
$cost_unsafe = test_input($_POST['cost']);
$capacity_unsafe = test_input($_POST['cap']);
$quorum_unsafe = test_input($_POST['quorum']);
$topictext_unsafe = test_input($_POST['topicText']);
$teacher_unsafe = test_input($_POST['teacher']);
$courseId = test_input($_GET['id']);
$type_unsafe = test_input($_POST['type']);


if (isset($_POST["submit"])) {

    if (!empty($title_unsafe) && !empty($description_unsafe) && !empty($teacher_unsafe)) {

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");

        if (!mysqli_connect_error()) {

            $sql = "SET NAMES 'utf8'";
            mysqli_query($db, $sql);


            if (file_exists($_FILES['qrCode']['tmp_name']) || is_uploaded_file($_FILES['qrCode']['tmp_name'])) {

                $qr = "";
                $target_dir = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                if (!file_exists($baseUrl . $target_dir)) {
                    mkdir($baseUrl . $target_dir, 0777, true);
                }
                $info = new SplFileInfo(basename($_FILES["qrCode"]["name"]));
                $target_dir = $target_dir . getGUID() . "." . $info->getExtension();
                $target_qr = $baseUrl . $target_dir;

                if (move_uploaded_file($_FILES["qrCode"]["tmp_name"], $target_qr)) {
                    $qr = $target_dir;
                } else {
                    $qr = "";
                }

            } else {
                $sql = "SELECT qrCode FROM course WHERE courseId = " . $courseId;
                $result = mysqli_query($db, $sql);
                $course = mysqli_fetch_assoc($result);
                $qr = $course['qrCode'];
            }

            if (file_exists($_FILES['brochureFile']['tmp_name']) || is_uploaded_file($_FILES['brochureFile']['tmp_name'])) {

                $brochurePath = "";
                $target_dir = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                if (!file_exists($baseUrl . $target_dir)) {
                    mkdir($baseUrl . $target_dir, 0777, true);
                }
                $info = new SplFileInfo(basename($_FILES["brochureFile"]["name"]));
                $target_dir = $target_dir . getGUID() . "." . $info->getExtension();
                $target_brochure = $baseUrl . $target_dir;

                if (move_uploaded_file($_FILES["brochureFile"]["tmp_name"], $target_brochure)) {
                    $brochurePath = $target_dir;
                    $brochureName = $_FILES["brochureFile"]["name"];
                } else {
                    $brochurePath = "";
                    $brochureName = "";
                }

            } else {
                $sql = "SELECT brochureFile FROM course WHERE courseId = " . $courseId;
                $result = mysqli_query($db, $sql);
                $course = mysqli_fetch_assoc($result);
                $brochurePath = $course['brochureFile'];
            }

            if (file_exists($_FILES['topicFile']['tmp_name']) || is_uploaded_file($_FILES['topicFile']['tmp_name'])) {

                $titlePath = "";
                $target_dir = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                if (!file_exists($baseUrl . $target_dir)) {
                    mkdir($baseUrl . $target_dir, 0777, true);
                }
                $info = new SplFileInfo(basename($_FILES["topicFile"]["name"]));
                $target_dir = $target_dir . getGUID() . "." . $info->getExtension();
                $target_title = $baseUrl . $target_dir;

                if (move_uploaded_file($_FILES["topicFile"]["tmp_name"], $target_title)) {
                    $titlePath = $target_dir;
                    $titleName = $_FILES["topicFile"]["name"];
                } else {
                    $titlePath = "";
                    $titleName = "";
                }

            } else {
                $sql = "SELECT topicFile FROM course WHERE courseId = " . $courseId;
                $result = mysqli_query($db, $sql);
                $course = mysqli_fetch_assoc($result);
                $titlePath = $course['topicFile'];
            }

            $title = mysqli_real_escape_string($db, $title_unsafe);
            $description = mysqli_real_escape_string($db, $description_unsafe);
            $startDate = mysqli_real_escape_string($db, $startDate_unsafe);
            $endDate = mysqli_real_escape_string($db, $endDate_unsafe);
            $holdingDays = mysqli_real_escape_string($db, $holdingDays_unsafe);
            $cost = mysqli_real_escape_string($db, $cost_unsafe);
            $capacity = mysqli_real_escape_string($db, $capacity_unsafe);
            $quorum = mysqli_real_escape_string($db, $quorum_unsafe);
            $topictext = mysqli_real_escape_string($db, $topictext_unsafe);
            $teacher = mysqli_real_escape_string($db, $teacher_unsafe);
            $brochurePath = mysqli_real_escape_string($db, $brochurePath);
            $titlePath = mysqli_real_escape_string($db, $titlePath);
            $type = mysqli_real_escape_string($db, $type_unsafe);
            $qr = mysqli_real_escape_string($db, $qr);


            $startDate = !empty($startDate) ? "'$startDate'" : "NULL";
            $endDate = !empty($endDate) ? "'$endDate'" : "NULL";
            $holdingDays = !empty($holdingDays) ? "'$holdingDays'" : "NULL";
            $cost = !empty($cost) ? "'$cost'" : "NULL";
            $capacity = !empty($capacity) ? "'$capacity'" : "NULL";
            $quorum = !empty($quorum) ? "'$quorum'" : "NULL";
            $topictext = !empty($topictext) ? "'$topictext'" : "NULL";
            $brochurePath = !empty($brochurePath) ? "'$brochurePath'" : "NULL";
            $titlePath = !empty($titlePath) ? "'$titlePath'" : "NULL";
            $brochureName = !empty($brochureName) ? "'$brochureName'" : "NULL";
            $titleName = !empty($titleName) ? "'$titleName'" : "NULL";
            $qr = !empty($qr) ? "'$qr'" : "NULL";

            $dif = capIncrease($db , $capacity);
            if($dif > 0) {
                $s = "UPDATE orders SET status=1  WHERE courseId = " . $_GET['id'] . " AND active=1 AND status=3 ORDER BY orderId limit " . $dif;
                mysqli_query($db, $s);
            }

            $sql = "UPDATE course SET 
                                title = '$title' ,
                                description = '$description' ,
                                startDate = $startDate ,
                                endDate = $endDate ,
                                holdingDays = $holdingDays ,
                                cost = $cost ,
                                capacity = $capacity ,
                                quorum = $quorum ,
                                topicText = $topictext ,
                                topicFile = $titlePath ,
                                brochureFile = $brochurePath ,
                                topicFileName = $titleName,
                                brochureFileName = $brochureName,
                                teacherMail = '$teacher' ,
                                isVirtual = $type ,
                                qrCode = $qr
                                WHERE courseId = $courseId";

            $result = mysqli_query($db, $sql);

            include_once("../strings.php");
            if ($result > 0)
                echo "<script>
                    alert('".$course_updated."');
                    window.location.href='admin.php';
                    </script>";
            else
                echo "<script>
                    alert('".$course_update_error."');
                    window.location.href='edit_course.php?id=" . $courseId . "';
                    </script>";

            mysqli_close($db);

        } else {
            echo "<script>
                        alert('".$db_error."');
                        window.location.href='edit_course.php?id=" . $courseId . "';
                        </script>";
        }

    } else
        header("location:edit_course.php?id=" . $courseId);

} else {
    header("location:edit_course.php?id=" . $courseId);
}

?>
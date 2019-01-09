<?php
session_start();
if (empty($_SESSION['aemail']))
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

function convertNumbers($srting, $toPersian = false)
{
    $en_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $fa_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    if ($toPersian) return str_replace($en_num, $fa_num, $srting);
    else return str_replace($fa_num, $en_num, $srting);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
$type_unsafe = test_input($_POST['type']);


if (isset($_POST["submit"])) {

    if (!empty($title_unsafe) && !empty($description_unsafe) && !empty($teacher_unsafe)) {


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

        }

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");

        if (!mysqli_connect_error()) {
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
            $qr = mysqli_real_escape_string($db, $qr);
            $type = mysqli_real_escape_string($db, $type_unsafe);


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

            $sql = "SET NAMES 'utf8'";
            mysqli_query($db, $sql);

            $query = "insert into course (title,description,startDate,endDate,holdingDays,cost,capacity ,quorum ,topicText,topicFile ,topicFileName , brochureFileName ,brochureFile,teacherMail,qrCode,isVirtual) values
                            ('$title','$description',$startDate,$endDate,$holdingDays,$cost,$capacity , $quorum ,$topictext,$titlePath,$titleName,$brochureName,$brochurePath,'$teacher',$qr,$type)";

            $result = mysqli_query($db, $query);
            include_once("../strings.php");
            if ($result > 0)
                echo "<script>
                    alert('".$new_course_added."');
                    window.location.href='admin.php';
                    </script>";
            else
                echo "<script>
                    alert('".$new_course_add_error."');
                    window.location.href='admin.php';
                    </script>";

            mysqli_close($db);

        } else {
            echo "<script>
                    alert('".$db_error."');
                    window.location.href='admin.php';
                    </script>";
        }

    } else
        header("location:admin.php");

} else {
    header("location:admin.php");
}

?>

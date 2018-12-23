<?php
session_start();
if (empty($_SESSION['temail']))
    header("location:../login.php");

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
$topictext_unsafe = test_input($_POST['topicText']);

if (isset($_POST["submit"])) {

    if (!empty($title_unsafe) && !empty($description_unsafe)) {

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
            $topictext = mysqli_real_escape_string($db, $topictext_unsafe);
            $brochurePath = mysqli_real_escape_string($db, $brochurePath);
            $titlePath = mysqli_real_escape_string($db, $titlePath);

            $startDate = !empty($startDate) ? "'$startDate'" : "NULL";
            $endDate = !empty($endDate) ? "'$endDate'" : "NULL";
            $holdingDays = !empty($holdingDays) ? "'$holdingDays'" : "NULL";
            $cost = !empty($cost) ? "'$cost'" : "NULL";
            $capacity = !empty($capacity) ? "'$capacity'" : "NULL";
            $topictext = !empty($topictext) ? "'$topictext'" : "NULL";
            $brochurePath = !empty($brochurePath) ? "'$brochurePath'" : "NULL";
            $titlePath = !empty($titlePath) ? "'$titlePath'" : "NULL";
            $brochureName = !empty($brochureName) ? "'$brochureName'" : "NULL";
            $titleName = !empty($titleName) ? "'$titleName'" : "NULL";

            $sql = "SET NAMES 'utf8'";
            mysqli_query($db, $sql);

            $query = "insert into course (title,description,startDate,endDate,holdingDays,cost,capacity,topicText,topicFile ,topicFileName , brochureFileName ,brochureFile,teacherMail) values
                            ('$title','$description',$startDate,$endDate,$holdingDays,$cost,$capacity,$topictext,$titlePath,$titleName,$brochureName,$brochurePath,'".$_SESSION['temail']."')";

            $result = mysqli_query($db, $query);

            if ($result > 0)
                echo "<script>
                    alert('new course added');
                    window.location.href='courses.php';
                    </script>";
            else
                echo "<script>
                    alert('error in adding new course');
                    window.location.href='courses.php';
                    </script>";

            mysqli_close($db);

        } else {
            echo "<script>
                    alert('error in connecting to DB. please try again later');
                    window.location.href=courses.php;
                    </script>";
        }

    } else
        header("location:courses.php");

} else {
    header("location:courses.php");
}

?>

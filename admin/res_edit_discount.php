<?php
session_start();
if (!isset($_SESSION['aemail']) || empty($_SESSION['aemail']))
    header("location:../login.php");

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

$code_unsafe = test_input($_POST['code']);
$type_unsafe = test_input($_POST['type']);
$startDate_unsafe = convertNumbers(test_input($_POST['startDate']));
$endDate_unsafe = convertNumbers(test_input($_POST['endDate']));
$amount_unsafe = test_input($_POST['amount']);
$isrial_unsafe = test_input($_POST['isrial']);
$courseId = test_input($_POST['course']);
$discountId = test_input($_GET['id']);
$needFile_unsafe = test_input($_POST['needFile']);

if (isset($_POST["submit"])) {

    if (!empty($type_unsafe) && !empty($startDate_unsafe) && !empty($endDate_unsafe) && !empty($amount_unsafe) && !empty($isrial_unsafe) && !empty($courseId) && !empty($discountId) && !empty($code_unsafe)) {

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");

        if (!mysqli_connect_error()) {

            $sql = "SET NAMES 'utf8'";
            mysqli_query($db, $sql);

            $code = mysqli_real_escape_string($db, $code_unsafe);
            $type = mysqli_real_escape_string($db, $type_unsafe);
            $startDate = mysqli_real_escape_string($db, $startDate_unsafe);
            $endDate = mysqli_real_escape_string($db, $endDate_unsafe);
            $amount = mysqli_real_escape_string($db, $amount_unsafe);
            $isrial = mysqli_real_escape_string($db, $isrial_unsafe);
            $discountId = mysqli_real_escape_string($db, $discountId);
            $courseId = mysqli_real_escape_string($db, $courseId);
            $needFile = mysqli_real_escape_string($db, $needFile_unsafe);

            $sql = "UPDATE discount SET 
                                startDate = '$startDate' ,
                                endDate = '$endDate' ,
                                amount = $amount ,
                                isRial = $isrial ,
                                type = '$type' ,
                                courseId = $courseId ,
                                code = '$code',
                                needFile = $needFile
                                WHERE discountId = $discountId";

            $result = mysqli_query($db, $sql);

            if ($result > 0)
                echo "<script>
                    alert('the discount updated');
                    window.location.href='discounts.php';
                    </script>";
            else
                echo "<script>
                    alert('error in updating the discount');
                    window.location.href='edit_discount.php?id=" . $discountId . "';
                    </script>";

            mysqli_close($db);

        } else {
            echo "<script>
                        alert('error in connecting to DB. please try again later');
                        window.location.href='edit_discount.php?id=" . $discountId . "';
                        </script>";
        }

    } else
        header("location:edit_discount.php?id=" . $discountId);

} else {
    header("location:edit_discount.php?id=" . $discountId);
}

?>
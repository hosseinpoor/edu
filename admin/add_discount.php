<?php
session_start();
if (isset($_SESSION['aemail']) && !empty($_SESSION['aemail'])) {

    $pic = "";
    $baseUrl = 'E:\xampp\htdocs\edu\\';

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

    $course_unsafe = test_input($_POST['course']);
    $type_unsafe = test_input($_POST['type']);
    $startDate_unsafe = convertNumbers(test_input($_POST['startDate']));
    $endDate_unsafe = convertNumbers(test_input($_POST['endDate']));
    $amount_unsafe = test_input($_POST['amount']);
    $isrial_unsafe = test_input($_POST['isrial']);
    $code_unsafe = test_input($_POST['code']);


    if (isset($_POST["submit"])) {

        if (!empty($type_unsafe) && !empty($startDate_unsafe) && !empty($endDate_unsafe) && !empty($amount_unsafe) && !empty($isrial_unsafe)) {

            $db = @mysqli_connect("localhost", "root", "", "ebbroker");
            if (!mysqli_connect_error()) {
                $course = mysqli_real_escape_string($db, $course_unsafe);
                $type = mysqli_real_escape_string($db, $type_unsafe);
                $startDate = mysqli_real_escape_string($db, $startDate_unsafe);
                $endDate = mysqli_real_escape_string($db, $endDate_unsafe);
                $amount = mysqli_real_escape_string($db, $amount_unsafe);
                $isrial = mysqli_real_escape_string($db, $isrial_unsafe);
                $code = mysqli_real_escape_string($db, $code_unsafe);

                $sql = "SET NAMES 'utf8'";
                mysqli_query($db, $sql);

                $query = "insert into discount (startDate,endDate,amount,isRial,type,courseId,code) values
                            ('$startDate','$endDate',$amount,$isrial,'$type',$course,'$code')";

                $result = mysqli_query($db, $query);

                if ($result > 0)
                    echo "<script>
                    alert('new discount added');
                    window.location.href='discounts.php';
                    </script>";
                else
                    echo "<script>
                    alert('error in adding new discount');
                    window.location.href='discounts.php';
                    </script>";

                mysqli_close($db);

            } else {
                echo "<script>
                    alert('error in connecting to DB. please try again later');
                    window.location.href='discounts.php';
                    </script>";
            }

        } else {
            header("location:../login.php");
        }

    } else {
        header("location:../login.php");
    }
} else {
    header("location:../login.php");
}

?>
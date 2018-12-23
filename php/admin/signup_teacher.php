<?php
session_start();
if (isset($_SESSION['aemail']) && !empty($_SESSION['aemail'])) {

    $pic = "";
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

    $email_unsafe = test_input($_POST['email']);
    $hash = password_hash($_POST['pswd'], PASSWORD_DEFAULT);
    $password_unsafe = test_input($hash);
    $name_unsafe = test_input($_POST['name']);
    $family_unsafe = test_input($_POST['family']);
    $phoneNum_unsafe = test_input($_POST['phoneNum']);
    $landlineNum_unsafe = test_input($_POST['landlineNum']);
    $dadsName_unsafe = test_input($_POST['dadsName']);
    $education_unsafe = test_input($_POST['education']);
    $sex_unsafe = test_input($_POST['sex']);
    $birthCity_unsafe = test_input($_POST['birthCity']);
    $address_unsafe = test_input($_POST['address']);
    $birthDay_unsafe = convertNumbers(test_input($_POST['birthDay']));
    $province_unsafe = convertNumbers(test_input($_POST['province']));



    if (!empty($email_unsafe) && !empty($password_unsafe) && !empty($name_unsafe) && !empty($family_unsafe) && !empty($phoneNum_unsafe) &&
        !empty($sex_unsafe) && !empty($education_unsafe) && !empty($province_unsafe)) {

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");

        if (!mysqli_connect_error()) {

            if (file_exists($_FILES['pictureFile']['tmp_name']) || is_uploaded_file($_FILES['pictureFile']['tmp_name'])) {

                $pic = "";
                $target_dir = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                if (!file_exists($baseUrl . $target_dir)) {
                    mkdir($baseUrl . $target_dir, 0777, true);
                }
                $info = new SplFileInfo(basename($_FILES["pictureFile"]["name"]));
                $target_dir = $target_dir . getGUID() . "." . $info->getExtension();
                $target_pic = $baseUrl . $target_dir;

                if (move_uploaded_file($_FILES["pictureFile"]["tmp_name"], $target_pic)) {
                    $pic = $target_dir;
                } else {
                    $pic = "";
                }

            }

            $email = mysqli_real_escape_string($db, $email_unsafe);
            $password = mysqli_real_escape_string($db, $password_unsafe);
            $name = mysqli_real_escape_string($db, $name_unsafe);
            $family = mysqli_real_escape_string($db, $family_unsafe);
            $phoneNum = mysqli_real_escape_string($db, $phoneNum_unsafe);
            $landlineNum = mysqli_real_escape_string($db, $landlineNum_unsafe);
            $dadsName = mysqli_real_escape_string($db, $dadsName_unsafe);
            $education = mysqli_real_escape_string($db, $education_unsafe);
            $province = mysqli_real_escape_string($db, $province_unsafe);
            $birthDay = mysqli_real_escape_string($db, $birthDay_unsafe);
            $address = mysqli_real_escape_string($db, $address_unsafe);
            $birthCity = mysqli_real_escape_string($db, $birthCity_unsafe);
            $sex = mysqli_real_escape_string($db, $sex_unsafe);
            $pic = mysqli_real_escape_string($db, $pic);

            $landlineNum = !empty($landlineNum) ? "'$landlineNum'" : "NULL";
            $dadsName = !empty($dadsName) ? "'$dadsName'" : "NULL";
            $address = !empty($address) ? "'$address'" : "NULL";
            $birthDay = !empty($birthDay) ? "'$birthDay'" : "NULL";
            $birthCity = !empty($birthCity) ? "'$birthCity'" : "NULL";
            $pic = !empty($pic) ? "'$pic'" : "NULL";

            $sql = "SET NAMES 'utf8'";
            $res = mysqli_query($db, $sql);

            $query1 = "insert into users (password,email,role) values ('$password','$email',3)";
            $result1 = mysqli_query($db, $query1);

            if ($result1 > 0) {
                $query2 = "insert into teachers (name,family,image,province,sex,address,landlineNum,phoneNum,birthDay,dadsName,birthCity,education,email) values
                            ('$name' , '$family' , $pic , '$province' , $sex , $address , $landlineNum , '$phoneNum' , $birthDay , $dadsName , $birthCity , '$education' , '$email')";
                $result2 = mysqli_query($db, $query2);
                if ($result2 > 0) {
                    echo "<script>
                        alert('استاد جدید با موفقیت افزوده شد');
                        window.location.href='teachers.php';
                        </script>";
                } else
                    echo mysqli_error($db);
            } else
                echo mysqli_error($db);

            mysqli_close($db);
        } else {
            echo "<script>
                        alert('error in connecting to DB. please try again later');
                        window.location.href='new_teacher.php';
                        </script>";
        }

    } else {
        header("location:new_teacher.php");
    }
} else {
    header("location:../login.php");
}

?>
<?php

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

if (isset($_POST['submit'])) {

    $email_unsafe = test_input($_POST['email']);
    $hash = password_hash($_POST['pswd'], PASSWORD_DEFAULT);
    $password_unsafe = test_input($hash);
    $name_unsafe = test_input($_POST['name']);
    $family_unsafe = test_input($_POST['family']);
    $phoneNum_unsafe = test_input($_POST['phoneNum']);
    $landlineNum_unsafe = test_input($_POST['landlineNum']);
    $dadsName_unsafe = test_input($_POST['dadsName']);
    $education_unsafe = test_input($_POST['education']);
    $nationalId_unsafe = test_input($_POST['nationalId']);

    if (!empty($email_unsafe) && !empty($password_unsafe) && !empty($name_unsafe) && !empty($family_unsafe) && !empty($phoneNum_unsafe)) {

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
            $nationalId = mysqli_real_escape_string($db, $nationalId_unsafe);
            $pic = mysqli_real_escape_string($db, $pic);


            $nationalId = !empty($nationalId) ? "'$nationalId'" : "NULL";
            $landlineNum = !empty($landlineNum) ? "'$landlineNum'" : "NULL";
            $dadsName = !empty($dadsName) ? "'$dadsName'" : "NULL";
            $education = !empty($education) ? "'$education'" : "NULL";
            $pic = !empty($pic) ? "'$pic'" : "NULL";


            $sql = "SET NAMES 'utf8'";
            $res = mysqli_query($db, $sql);

            $query1 = "insert into users (password,email,role) values ('$password','$email',5)";
            $result1 = mysqli_query($db, $query1);

            if ($result1 > 0) {
                $query2 = "insert into students (name,family,nationalId,landlineNum,phoneNum,dadsName,education,email,image) values 
                            ('$name','$family',$nationalId,$landlineNum,'$phoneNum',$dadsName,$education,'$email',$pic)";
                $result2 = mysqli_query($db, $query2);
                if ($result2 > 0) {
                    session_start();
                    $_SESSION["semail"] = $email;

                    if (!empty($_GET['id']) && !empty($_GET['reagent']))
                        header("location:student/course.php?id=" . $_GET['id'] . "&reagent=" . $_GET['reagent']);
                    else if (!empty($_GET['id']))
                        header("location:student/course.php?id=" . $_GET['id']);
                    else
                        header("location:student/panel.php");
                } else
                    echo "<script>
                        alert('faild to sign up in students');
                        window.location.href='signup.php';
                        </script>";
            } else
                echo mysqli_error($db);

            mysqli_close($db);
        } else {
            echo "<script>
                        alert('error in connecting to DB. please try again later');
                        window.location.href='signup.php';
                        </script>";
        }

    } else {
        header("location:signup.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ثبت نام</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/civem.js"></script>
    <script src="js/script.js"></script>
</head>
<body dir="rtl">

<div class="container">
    <h2>ثبت نام</h2>
    <form action="" enctype="multipart/form-data" method="post" autocomplete="off">

        <div class="form-group">
            <span class="required">*</span>
            <label for="email">رایانامه:</label>
            <input type="email" class="form-control" id="email" name="email" title="رایانامه" required
                   data-errormessage="لطفا یک رایانامه معتبر وارد کنید"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="pwd">گذرواژه:</label>
            <input type="password" class="form-control" id="pwd" name="pswd" minlength="4" title="گذرواژه" required
                   data-errormessage="گذرواژه باید حداقل 4 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="name">نام:</label>
            <input type="text" class="form-control" id="name" name="name" minlength="3" title="نام" required
                   data-errormessage="نام باید حداقل 3 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="family">نام خانوادگی:</label>
            <input type="text" class="form-control" id="family" name="family" minlength="3" title="نام خانوادگی"
                   required
                   data-errormessage="نام خانوادگی باید حداقل 3 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="phoneNum">تلفن همراه:</label>
            <input type="text" class="form-control" id="phoneNum" name="phoneNum" minlength="10" maxlength="11"
                   title="تلفن همراه" required
                   data-errormessage="شماره تلفن همراه باید حداقل 10 رقم باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <label for="nationalId">کد ملی:</label>
            <input type="text" class="form-control" id="nationalId" name="nationalId" minlength="10" title="کد ملی"
                   maxlength="10"
                   data-errormessage="کد ملی باید 10 رقم باشد"
            >
        </div>
        <div class="form-group">
            <label for="landlineNum">تلفن ثابت:</label>
            <input type="text" class="form-control" id="landlineNum" name="landlineNum" minlength="8" title="تلفن ثابت"
                   maxlength="11"
                   data-errormessage="شماره تلفن ثابت باید حداقل 8 رقم باشد"
            >
        </div>
        <div class="form-group">
            <label for="dadsName">نام پدر:</label>
            <input type="text" class="form-control" id="dadsName" name="dadsName" title="نام پدر" minlength="3"
                   data-errormessage="نام پدر باید حداقل 3 حرف باشد"
            >
        </div>
        <div class="form-group">
            <label for="education">تحصیلات:</label>
            <input type="text" class="form-control" id="education" title="تحصیلات" name="education">
        </div>
        <div class="form-group">
            <label for="pictureFile">تصویر : </label>
            <input type="file" class="form-control-file border" name="pictureFile" id="pictureFile"
                   title="تصویر دانشجو">
        </div>
        <button type="submit" class="btn btn-success" name="submit" value="signup">ثبت نام</button>
    </form>

    <br>

</div>

</body>
</html>


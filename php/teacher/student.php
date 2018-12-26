<?php
// Start the session
session_start();
if (empty($_SESSION['temail']))
    header("location:../login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php
        if (isset($_GET['mail']) && !empty($_GET['mail'])) {
            $db = @mysqli_connect("localhost", "root", "", "ebbroker");
            if (!mysqli_connect_error()) {
                mysqli_query($db, "SET NAMES utf8");
                $sql = "SELECT family FROM students WHERE email = '" . base64_decode($_GET['mail']) . "'";
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo $row['family'];
                } else {
                    header("location:students.php");
                }
            } else {
                header("location:students.php");
            };
        } else {
            header("location:students.php");
        }
        ?></title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/script.js"></script>
</head>
<body dir="rtl">

<?php
include("sidebar_teacher.php");
?>

<div class="content p-5 <?php echo ($_SESSION["isCollapse"]=='true')? 'ac' : '' ?>">

    <?php
    $sql = "SELECT * FROM conf";
    $res = mysqli_query($db,$sql);
    $r = mysqli_fetch_assoc($res);

    $sql = "SELECT * FROM students WHERE email = '" . base64_decode($_GET['mail']) . "'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);

    $phone = ($r['phoneAuth']) ? $row['phoneNum'] : "شما مجاز به مشاهده این بخش نیستید";
    $mail = ($r['emailAuth']) ? $row['email'] : "شما مجاز به مشاهده این بخش نیستید";
    $landline = ($r['landlineAuth']) ? $row['landlineNum'] : "شما مجاز به مشاهده این بخش نیستید";

    echo "نام : " . $row['name'] . "<br>";
    echo "نام خانوادگی : " . $row['family'] . "<br>";
    echo "نام پدر : " . $row['dadsName'] . "<br>";
    echo "کد ملی : " . $row['nationalId'] . "<br>";
    echo "تلفن همراه : " . $phone . "<br>";
    echo "تلفن ثابت : " . $landline . "<br>";
    echo "میزان تحصیلات : " . $row['education'] . "<br>";
    echo "رایانامه : " . $mail . "<br>";
    ?>

</div>
</body>
</html>
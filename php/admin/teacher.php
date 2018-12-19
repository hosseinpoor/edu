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
                $sql = "SELECT family FROM teachers WHERE email = '" . $_GET['mail'] . "'";
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo $row['family'];
                } else {
                    header("location:teachers.php");
                }
            } else {
                header("location:teachers.php");
            };
        } else {
            header("location:teachers.php");
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
include("sidebar_admin.php");
?>

<div class="content" style="padding: 50px">

    <?php
    $sql = "SELECT * FROM teachers WHERE email = '" . $_GET['mail'] . "'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "نام : " . $row['name'] . "<br>";
    echo "نام خانوادگی : " . $row['family'] . "<br>";
    echo "نام پدر : " . $row['dadsName'] . "<br>";
    echo "تلفن همراه : " . $row['phoneNum'] . "<br>";
    echo "تلفن ثابت : " . $row['landlineNum'] . "<br>";
    echo "میزان تحصیلات : " . $row['education'] . "<br>";
    echo "رایانامه : " . $row['email'] . "<br>";
    ?>

</div>
</body>
</html>
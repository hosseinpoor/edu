<?php
session_start();
include_once("../strings.php");
if (!isset($_SESSION['semail']) || empty($_SESSION['semail']))
    header("location:../login.php");

$db = @mysqli_connect("localhost", "root", "", "ebbroker");
if (!mysqli_connect_error()) {
    mysqli_query($db, "SET NAMES utf8");
    $sql = "SELECT * FROM students WHERE email = '" . $_SESSION['semail'] . "'";
    $result = mysqli_query($db, $sql);
    $student = mysqli_fetch_assoc($result);

} else {
    echo "<script>
                alert('".$db_error."');
                window.location.href='../login.php';
                </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>پنل دانشجو</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/stu-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</head>
<body dir="rtl">

<div class="topnav" id="myTopnav">
    <a href="#" class="navlogo"><img src="../img/eblogo.png" alt="اقتصاد بیدار"></a>
    <a href="#" class="active">معاملات</a>
    <a href="#">خدمات ما</a>
    <a href="#">سرمایه گذاری</a>
    <a href="#">درباره ما</a>
    <a href="#">دفاتر و شعب</a>

    <a href="#" class="nav-oppening">افتتاح حساب</a>
    <a href="#" class="nav-advice">مشاوره بورسی</a>

    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
    </a>
</div>

<div class="container">

    <div class="row">
        <div class="col-lg-3">
            <div class="sidebar box">

                <?php
                if ($student['image'] == "NULL" || $student['image'] == "")
                    echo '<a href="panel.php"><img src="../img/avatar.png" class="avatar" alt="avatar"></a>';
                else
                    echo '<img src="../' . $student['image'] . '" class="avatar" alt="avatar">';
                ?>

                <div class="side-details">
                    <a href="panel.php"><h2><?php echo $student['name'] . " " . $student['family'] ?></h2></a>
                    <ul class="side-list">
                        <li>
                            <img src="../img/envelope.png" alt="icon" class="side-icon">
                            پیام ها
                        </li>
                        <li>
                            <img src="../img/overtime.png" alt="icon" class="side-icon">
                            برنامه کلاس ها
                        </li>
                        <li><a href="finished_list.php">
                                <img src="../img/finished.png" alt="icon" class="side-icon">
                                دوره های پایان یافته من
                            </a>
                        </li>
                        <li><a href="course_list.php">
                                <img src="../img/newcourse.png" alt="icon" class="side-icon">
                                ثبت نام دوره جدید
                            </a>
                        </li>
                        <li><a href="conf.php">
                                <img src="../img/settings.png" alt="icon" class="side-icon">
                                تنظیمات حساب کاربری
                            </a>
                        </li>
                        <li><a href="logout.php">
                                <img src="../img/signout.png" alt="icon" class="side-icon">
                                خروج
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-lg-9 pos">
            <div>
                <br>
                <h1>ارتباط با ما:</h1>
                <br>
                <h3>تلگرام : ebclub@</h3>
                <br>
                <h3>آموزش : 02142029550</h3>
                <br>
                <h3>تلفن همراه : 09204202900</h3>
                <br>
                <h3>مدیریت : 02142029520</h3>
            </div>

            <div class="row options">
                <div class="col-md-2 col-sm-4 col-xs-6  text-center option">
                    <a href="allcourse_list.php">
                        <img src="../img/course-list.png" alt="فهرست دوره ها">
                        <div>فهرست دوره ها</div>
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <a href="finished_list.php">
                        <img src="../img/license.png" alt="گواهینامه ها">
                        <div>گواهینامه ها</div>
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <a href="topics.php">
                    <img src="../img/topic.png" alt="سرفصل دوره ها">
                    <div>سرفصل دوره ها</div>
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <img src="../img/viredu.png" alt="آموزش مجازی">
                    <div>آموزش مجازی</div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <img src="../img/download-center.png" alt="مرکز دانلود">
                    <div>مرکز دانلود</div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <a href="contact.php">
                        <img src="../img/support.png" alt="پشتیبانی">
                        <div>پشتیبانی</div>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="footer">
    <div class="row">
        <div class="col-md-8 address">
            <h2 class="contact text-right">دفتر اوراق بهادار</h2>
            <h5 class="contact text-right">تهران، خیابان ولیعصر، بالاتر از جام جم، کوچه پروین، پلاک 48، طبقه سوم</h5>
            <h2 class="contact text-right">دفتر اوراق بهادار</h2>
            <h5 class="contact text-right">تهران، خیابان ولیعصر، بالاتر از جام جم، کوچه پروین، پلاک 48، طبقه سوم</h5>

        </div>
        <div class="col-md-4 socials">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <a href="#" class="footer-advice">مشاوره بورسی</a>
                </div>
                <div class="col-lg-6 col-md-12">
                    <a href="#" class="footer-oppening">افتتاح حساب</a>
                </div>
            </div>
            <h1 class="contact">مرکــــــز تمــــــاس</h1>
            <h1 class="contact">000 29 420 - 021</h1>
            <a href="#"><img class="social" src="../img/email.png" alt="email"></a>
            <a href="#"><img class="social" src="../img/telegram.png" alt="telegram"></a>
            <a href="#"><img class="social" src="../img/linkdin.png" alt="linkdin"></a>
        </div>
    </div>
    <div class="to-top"><a href="#"><i class="fa fa-chevron-up"></i></a></div>
</div>


<script>
    function myFunction() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }
</script>

</body>
</html>
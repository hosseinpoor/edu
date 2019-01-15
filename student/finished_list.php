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

function jalali_to_gregorian($jy, $jm, $jd, $mod = '')
{
    if ($jy > 979) {
        $gy = 1600;
        $jy -= 979;
    } else {
        $gy = 621;
    }
    $days = (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
    $gy += 400 * ((int)($days / 146097));
    $days %= 146097;
    if ($days > 36524) {
        $gy += 100 * ((int)(--$days / 36524));
        $days %= 36524;
        if ($days >= 365) $days++;
    }
    $gy += 4 * ((int)($days / 1461));
    $days %= 1461;
    if ($days > 365) {
        $gy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $gd = $days + 1;
    foreach (array(0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {
        if ($gd <= $v) break;
        $gd -= $v;
    }
    return ($mod == '') ? array($gy, $gm, $gd) : $gy . $mod . $gm . $mod . $gd;
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
    <script src="../js/stu-script.js"></script>
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
                    echo '<img src="../img/avatar.png" class="avatar" alt="avatar">';
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
                        <li><a href="course_list.php">
                                <img src="../img/overtime.png" alt="icon" class="side-icon">
                                برنامه کلاس ها
                            </a>
                        </li>
                        <li><a href="finished_list.php">
                                <img src="../img/finished.png" alt="icon" class="side-icon">
                                دوره های پایان یافته
                            </a>
                        </li>
                        <li>
                            <img src="../img/payments.png" alt="icon" class="side-icon">
                            پرداخت ها

                        </li>
                        <li>
                            <img src="../img/newcourse.png" alt="icon" class="side-icon">
                            ثبت نام دوره جدید
                        </li>
                        <li>
                            <img src="../img/settings.png" alt="icon" class="side-icon">
                            تنظیمات حساب کاربری
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
        <div class="col-lg-9">
            <div style="position: relative ; height: 50px"><img
                        style="position: absolute ; right: calc(50% - 77px) ; top: -100px !important; z-index: -100"
                        src="../img/1.png" alt="آموزش"></div>
            <h4 class="font-weight-bold text-center">دوره های آموزشی تمام شده</h4>
            <div class="row intro-courses">

                <?php

                function getOffset()
                {
                    if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0) {
                        return ($_GET['page'] - 1) * 9;
                    } else
                        return 0;
                }

                function getSubmitCount($id, $db)
                {
                    $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 1";
                    $result = mysqli_query($db, $sql);
                    return mysqli_num_rows($result);
                }

                $sql = "SELECT courseId FROM course WHERE courseId NOT IN (SELECT courseId FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "' AND active = 1 AND (status = 1 OR status = 3)) ORDER BY courseId DESC ";
                $result = mysqli_query($db, $sql);

                $today = date("Y-m-d");
                $counter = 0;
                while ($r = mysqli_fetch_assoc($result)) {
                    $s = "SELECT * FROM course INNER JOIN teachers ON teacherMail = email WHERE courseId = " . $r['courseId'];
                    $res = mysqli_query($db, $s);
                    $row = mysqli_fetch_assoc($res);
                    if ($row['endDate']) {
                        $end = $row['endDate'];
                        $endY = substr($end, 0, 4);
                        $endM = substr($end, 5, 2);
                        $endD = substr($end, 8, 2);
                        $endG = jalali_to_gregorian($endY, $endM, $endD, "-");
                        if ($today < $endG)
                            continue;
                    }
                    $counter = $counter + 1;
                    if ($counter < 10 + getOffset() && getOffset() < $counter) {
                        echo '<div class="col-sm-12 col-md-6 col-lg-4">';
                        if ($row['isVirtual']) echo '<div class="ribbon"><span>آنلاین</span></div>';
                        echo '<div class="row intro-course shadow-bottom mb-2">';
                        echo '<div class="col-sm-5 col-xs-12">';
                        if ($row['image'] == Null || $row['image'] == '')
                            echo '<img src="../img/teacher_av.png" alt="avatar"></div>';
                        else
                            echo '<img src="../' . $row['image'] . '" alt="avatar"></div>';
                        echo '<div class="col-sm-7 col-xs-12"> <div class="details">';
                        echo '<div class="title">' . $row['title'] . '</div>';
                        echo '<div> <span>استاد: </span> <span>' . $row['name'] . " " . $row['family'] . '</span> </div>';
                        echo '<div> <span>تاریخ شروع: </span> <span>' . str_replace('-', '/', $row['startDate']) . '</span> </div>';
                        if ($row['cost'] == NULL) echo '<div><span>هزینه دوره: </span><span class="txt-red"> رایگان</span></div>'; else echo '<div><span>هزینه دوره: </span>' . $row['cost'] . 'ریال</div>';
                        ($row['capacity'] == NULL) ? $remaining_cap = "نامحدود" : $remaining_cap = intval($row['capacity']) - intval(getSubmitCount($row['courseId'], $db));
                        echo '<div><span>ظرفیت باقی مانده: </span><span>' . $remaining_cap . '</span></div>';
                        echo '<div><span>محل برگزاری: </span><span>تهران</span></div>';
                        if ($remaining_cap)
                            echo '</div><a href="course.php?id=' . $row["courseId"] . '" class="intro-submit">ثبت نام</a></div></div></div>';
                        else
                            echo '</div><a href="course.php?id=' . $row["courseId"] . '" class="intro-reserve">رزرو</a></div></div></div>';
                    }
                }

                ?>
            </div>

            <div class="row options">
                <div class="col-md-2 col-sm-4 col-xs-6  text-center option">
                    <a href="course_list.php">
                        <img src="../img/course-list.png" alt="فهرست دوره ها">
                        <div>فهرست دوره ها</div>
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <img src="../img/license.png" alt="گواهینامه ها">
                    <div>گواهینامه ها</div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 text-center option">
                    <img src="../img/topic.png" alt="سرفصل دوره ها">
                    <div>سرفصل دوره ها</div>
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
                    <img src="../img/support.png" alt="پشتیبانی">
                    <div>پشتیبانی</div>
                </div>
            </div>

            <?php
            include("pager.php");
            createPager("", $db, $counter);
            ?>

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
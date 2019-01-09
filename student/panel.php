<?php
session_start();
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
                alert('error in connecting to DB. please try again later');
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
                        <li>
                            <img src="../img/overtime.png" alt="icon" class="side-icon">
                            برنامه کلاس ها
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
        <div class="col-lg-9 pos">
            <div class="courses box">
                <div class="header">
                    <img src="../img/notif.png" alt="icon">
                    دوره های آموزشی شما
                </div>

                <table class="table table-hover">
                    <tbody>


                    <?php
                    $sql = "SELECT courseId , status , verify FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "' AND active = 1";
                    $result = mysqli_query($db, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $s = "SELECT title, holdingDays, cost , courseId , name , family FROM course INNER JOIN teachers ON teacherMail = email WHERE courseId = " . $row["courseId"];
                            $r = mysqli_query($db, $s);
                            while ($crow = mysqli_fetch_assoc($r)) {
                                echo "<tr class='clickable-row text-center' data-href='course.php?id=" . $crow["courseId"] . "'>";
                                echo '<td> <img src="../img/t1.png" alt="icon"> کلاس ' . $crow["title"] . "</td>" . '<td><img src="../img/t2.png" alt="icon"> استاد: ' . $crow["name"] . " " . $crow["family"] . "</td>" .
                                    '<td><img src="../img/t3.png" alt="icon"> ' . $crow["holdingDays"] . "</td>";
                                if ($row['verify']) {
                                    if ($row['status'] == '1') {
                                        echo '<td> <img src="../img/id-card.png" alt="icon"> ' . "<a class='disFile txt-black' target='_blank' href='card.php?id=" . $row['courseId'] . "'>کارت من</a>" . "</td>"
                                            . '<td class="txt-green">تایید شده</td>';
                                    }
                                    if ($row['status'] == '3') {
                                        echo '<td> <img src="../img/id-card.png" alt="icon"> ' . "<a class='disFile txt-gray' target='_blank'>کارت من</a>" . "</td>" .
                                            '<td class="txt-blue">رزرو شده</td>';
                                    }
                                } else {
                                    echo '<td> <img src="../img/id-card.png" alt="icon"> ' . "<a class='disFile txt-gray' target='_blank'>کارت من</a>" . "</td>" .
                                        '<td class="txt-yellow">در انتظار تایید</td>';
                                }
                                echo "</tr>";
                            }
                        }

                    } else {
                        echo "<tr class='text-center'>";
                        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
                        echo "</tr>";
                    }
                    ?>

                    </tbody>
                </table>
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
            <h4 class="font-weight-bold text-center">جدید ترین دوره های آموزش و همایش های بورس</h4>
            <div class="row intro-courses">

                <?php

                function getSubmitCount($id, $db)
                {
                    $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 1";
                    $result = mysqli_query($db, $sql);
                    return mysqli_num_rows($result);
                }

                $sql = "SELECT courseId FROM course WHERE courseId NOT IN (SELECT courseId FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "'  AND active = 1 AND (status = 1 OR status = 3)) ORDER BY courseId DESC LIMIT 3";
                $result = mysqli_query($db, $sql);

                while ($r = mysqli_fetch_assoc($result)) {
                    $s = "SELECT * FROM course INNER JOIN teachers ON teacherMail = email WHERE courseId = " . $r['courseId'];
                    $res = mysqli_query($db, $s);
                    $row = mysqli_fetch_assoc($res);

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
                    if ($remaining_cap == "0") $remaining_cap = "<span class='txt-red'>تکمیل</span>";
                    echo '<div><span>ظرفیت باقی مانده: </span><span>' . $remaining_cap . '</span></div>';
                    echo '<div><span>محل برگزاری: </span><span>تهران</span></div>';
                    if ($remaining_cap)
                        echo '</div><a href="course.php?id=' . $row["courseId"] . '" class="intro-submit">ثبت نام</a></div></div></div>';
                    else
                        echo '</div><a href="course.php?id=' . $row["courseId"] . '" class="intro-reserve">رزرو</a></div></div></div>';
                }

                ?>


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
<?php
session_start();
include_once("../strings.php");
$session = false;
if (isset($_SESSION['semail']) && !empty($_SESSION['semail'])) {
    $session = true;

    $db = @mysqli_connect("localhost", "root", "", "ebbroker");
    if (!mysqli_connect_error()) {
        mysqli_query($db, "SET NAMES utf8");
        $sql = "SELECT * FROM students WHERE email = '" . $_SESSION['semail'] . "'";
        $result = mysqli_query($db, $sql);
        $student = mysqli_fetch_assoc($result);

    } else {
        echo "<script>
                alert('".$db_error."');
                window.location.href='courses.php';
                </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>
        <?php
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $db = @mysqli_connect("localhost", "root", "", "ebbroker");
            if (!mysqli_connect_error()) {
                mysqli_query($db, "SET NAMES utf8");
                $sql = "SELECT title FROM course WHERE courseId = " . $_GET['id'];
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo $row['title'];
                } else {
                    $session ? header("location:courses.php") : header("location:../login.php");
                }
            } else {
                $session ? header("location:courses.php") : header("location:../login.php");
            };
        } else {
            $session ? header("location:courses.php") : header("location:../login.php");
        }
        ?>
    </title>
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

            <div class="sidebar box <?php
            if (!$session)
                echo 'd-none'
            ?>">

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
        <div class="col-lg-9 text-right">

            <?php

            function getSubmitCount($id, $db)
            {
                $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 1";
                $result = mysqli_query($db, $sql);
                return mysqli_num_rows($result);
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

            $discountCode = "";
            $sql = "SELECT * FROM course WHERE courseId = " . $_GET['id'];
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_assoc($result);
            echo "<div>" . "عنوان : " . $row['title'] . "</div><br>";

            echo "<div>" . "توضیحات : " . "</div><br><div>";
            echo str_replace("&nbsp;", " ", htmlspecialchars_decode(stripslashes($row['description'])));
            echo "</div>";

            if ($row['startDate'] != null && $row['startDate'] != "")
                echo "تاریخ شروع : " . $row['startDate'] . "<span class='txt-red'> در صورت عدم رسیدن به حد نصاب این تاریخ ممکن است به تعویق بیافتد. توجه داشته باشید که پس از قطعی شدن تاریخ شروع کلاس با شما تماس گرفته خواهد شد.</span>" . "<br>";
            if ($row['endDate'] != null && $row['endDate'] != "")
                echo "تاریخ پایان : " . $row['endDate'] . "<br>";
            if ($row['holdingDays'] != null && $row['holdingDays'] != "")
                echo "روز های برگزاری : " . $row['holdingDays'] . "<br>";
            if ($row['capacity'] != null && $row['capacity'] != "" && $row['capacity'] != "0")
                echo "ظرفیت : " . $row['capacity'] . "<br>";
            else if ($row['capacity'] == "0")
                echo "ظرفیت : " . "<span class='txt-red'>تکمیل</span>" . "<br>";
            if ($row['quorum'] != null && $row['quorum'] != "")
                echo "حد نصاب : " . $row['quorum'] . "<br>";
            if ($row['topicText'] != null && $row['topicText'] != "")
                echo "سرفصل ها : " . $row['topicText'] . "<br>";
            ($row['capacity'] == NULL) ? $remaining_cap = 999999 : $remaining_cap = intval($row['capacity']) - intval(getSubmitCount($row['courseId'], $db));

            if (!empty($_SESSION['semail'])) {
                if (isset($_GET['reagent']) && !empty($_GET['reagent'])) {
                    $reagent = base64_decode($_GET['reagent']);
                    $sql = "SELECT courseId , discountId FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "' AND courseId = " . $_GET['id'] . " AND active = 1 AND status = 1";
                    $result = mysqli_query($db, $sql);

                    if (mysqli_num_rows($result) > 0) {

                        $res = mysqli_fetch_assoc($result);
                        if ($row['cost']) {
                            echo "<span id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                            echo "<span class='pr-2' id='finalCost'>";

                            if (isset($res['discountId']) && !empty($res['discountId'])) {
                                $s = "SELECT * FROM discount WHERE discountId = " . $res['discountId'];
                                $r = mysqli_query($db, $s);
                                $count = mysqli_num_rows($r);
                                if ($count == 1) {
                                    $res = mysqli_fetch_assoc($r);
                                    if ($res['isRial']) {
                                        echo " میزان تخفیف : " . $res['amount'] . " ریال";
                                    } else {
                                        echo " میزان تخفیف : " . $res['amount'] . " درصد";
                                    }
                                }
                            }

                            echo "</span> <br>";

                        } else {
                            echo "هزینه : " . "<span class='txt-red'>رایگان</span>" . "<br>";
                        }

                    } else {
                        if ($row['cost']) {
                            $finalCost = $row['cost'];
                            $sql = "SELECT * FROM discount WHERE code = '" . $reagent . "' AND courseId = " . $_GET['id'];
                            $result = mysqli_query($db, $sql);
                            $count = mysqli_num_rows($result);
                            if ($count == 1) {
                                $res = mysqli_fetch_assoc($result);
                                $start = $res['startDate'];
                                $startY = substr($start, 0, 4);
                                $startM = substr($start, 5, 2);
                                $startD = substr($start, 8, 2);
                                $startG = jalali_to_gregorian($startY, $startM, $startD, "-");
                                $end = $res['endDate'];
                                $endY = substr($end, 0, 4);
                                $endM = substr($end, 5, 2);
                                $endD = substr($end, 8, 2);
                                $endG = jalali_to_gregorian($endY, $endM, $endD, "-");
                                $today = date("Y-m-d");

                                if ($res['isRial'] == 1) {
                                    if ($today >= $startG && $today <= $endG)
                                        $finalCost = $row['cost'] - $res['amount'];
                                } else {
                                    if ($today >= $startG && $today <= $endG)
                                        $finalCost = $row['cost'] - ($res['amount'] * $row['cost']) / 100;
                                }
                                echo "<span class='invalid' id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                                echo "<span class='pr-2' id='finalCost'>هزینه برای شما : " . $finalCost . " ریال" . "</span>";
                                echo "<br>";
                                $discountCode = $reagent;

                            } else {
                                echo "<span id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                                echo "<br>";
                            }

                        } else {
                            echo "هزینه : " . "<span class='txt-red'>رایگان</span>" . "<br>";
                        }
                    }

                } else {
                    $sql = "SELECT courseId , discountId FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "' AND courseId = " . $_GET['id'] . " AND active = 1 AND status = 1";
                    $result = mysqli_query($db, $sql);

                    if (mysqli_num_rows($result) > 0) {

                        $res = mysqli_fetch_assoc($result);
                        if ($row['cost']) {
                            echo "<span id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                            echo "<span class='pr-2' id='finalCost'>";

                            if (isset($res['discountId']) && !empty($res['discountId'])) {
                                $s = "SELECT * FROM discount WHERE discountId = " . $res['discountId'];
                                $r = mysqli_query($db, $s);
                                $count = mysqli_num_rows($r);
                                if ($count == 1) {
                                    $res = mysqli_fetch_assoc($r);
                                    if ($res['isRial']) {
                                        echo " میزان تخفیف : " . $res['amount'] . " ریال";
                                    } else {
                                        echo " میزان تخفیف : " . $res['amount'] . " درصد";
                                    }
                                }
                            }

                            echo "</span> <br>";

                        } else {
                            echo "هزینه : " . "<span class='txt-red'>رایگان</span>" . "<br>";
                        }

                    } else {

                        if ($row['cost']) {
                            echo "<span id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                            echo "<span class='pr-2' id='finalCost'></span>";
                            echo "<br>";

                            echo '
                    <span>نوع تخفیف:</span>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="none" class="form-check-input" id="disNone" name="disType" checked="checked"> هیچ کدام
                    </label>
                    </div>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="code" class="form-check-input" id="disCode" name="disType"> کد تخفیف
                    </label>
                    </div>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="file" class="form-check-input" id="disFile" name="disType"> دانشجویی
                    </label>
                    </div>
                    <br>';

                            echo '<div class="needFile">';
                            echo '<span>آپلود کارت دانشجویی  : </span>
                    <input type="file" class="border" name="neededFile" id="neededFile"
                    title="فایل بروشور">
                    <button id="discountFileBtn" class="btn btn-primary" name="submit" value="submit" cost="' . $row['cost'] . '" courseId="' . $_GET['id'] . '" >اعتبار سنجی فایل</button> <br> ';
                            echo '</div>';

                            echo '<div class="needCode">';
                            echo '<label for="discount">کد تخفیف:</label>
                    <input type="text"  id="discount">
                    <button id="discountCodeBtn" class="btn btn-primary" name="submit" value="submit" cost="' . $row['cost'] . '" courseId="' . $_GET['id'] . '" >بررسی تخفیف</button> <br> ';
                            echo '</div>';

                            echo '
                    <span>نوع پرداخت:</span>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="receipt" class="form-check-input" name="payType" checked="checked"> آپلود فیش واریزی
                    </label>
                    </div>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="online" class="form-check-input" name="payType"> پرداخت آنلاین
                    </label>
                    </div>
                    <br>';

                            echo '<div>';
                            echo '<span>آپلود فیش واریزی  : </span>
                    <input type="file" class="border" name="receipt" id="receipt"
                    title="فایل بروشور">
                    </div>';

                        } else {
                            echo "هزینه : " . "<span class='txt-red'>رایگان</span>" . "<br>";
                        }

                    }
                }
            } else {
                if (isset($_GET['reagent']) && !empty($_GET['reagent'])) {
                    $reagent = base64_decode($_GET['reagent']);
                    if ($row['cost']) {

                        $finalCost = $row['cost'];
                        $sql = "SELECT * FROM discount WHERE code = '" . $reagent . "' AND courseId = " . $_GET['id'];
                        $result = mysqli_query($db, $sql);
                        $count = mysqli_num_rows($result);
                        if ($count == 1) {
                            $res = mysqli_fetch_assoc($result);
                            $start = $res['startDate'];
                            $startY = substr($start, 0, 4);
                            $startM = substr($start, 5, 2);
                            $startD = substr($start, 8, 2);
                            $startG = jalali_to_gregorian($startY, $startM, $startD, "-");
                            $end = $res['endDate'];
                            $endY = substr($end, 0, 4);
                            $endM = substr($end, 5, 2);
                            $endD = substr($end, 8, 2);
                            $endG = jalali_to_gregorian($endY, $endM, $endD, "-");
                            $today = date("Y-m-d");

                            if ($res['isRial'] == 1) {
                                if ($today >= $startG && $today <= $endG)
                                    $finalCost = $row['cost'] - $res['amount'];
                            } else {
                                if ($today >= $startG && $today <= $endG)
                                    $finalCost = $row['cost'] - ($res['amount'] * $row['cost']) / 100;
                            }
                            echo "<span class='invalid' id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                            echo "<span class='pr-2' id='finalCost'>هزینه برای شما : " . $finalCost . " ریال" . "</span>";
                            echo "<br>";
                            $discountCode = $reagent;

                        } else {
                            echo "<span id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                            echo "<br>";
                        }

                    } else {
                        echo "هزینه : " . "<span class='txt-red'>رایگان</span>" . "<br>";
                    }
                } else {
                    if ($row['cost']) {
                        echo "<span id='realCost'>" . "هزینه : " . $row['cost'] . " ریال " . "</span>";
                        echo "<span class='pr-2' id='finalCost'></span>";
                        echo "<br>";

                        echo '
                    <span>نوع تخفیف:</span>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="none" class="form-check-input" name="disType" checked="checked"> هیچ کدام
                    </label>
                    </div>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="code" class="form-check-input" name="disType"> کد تخفیف
                    </label>
                    </div>
                    <div class="form-check-inline">
                    <label class="form-check-label">
                    <input type="radio" value="file" class="form-check-input" name="disType"> نیازمند فایل
                    </label>
                    </div>
                    <br>';

                        echo '<div class="needFile">';
                        echo '<span>آپلود فایل  : </span>
                    <input type="file" class="border" name="neededFile" id="neededFile"
                    title="فایل بروشور">
                    <button id="discountFileBtn" class="btn btn-primary" name="submit" value="submit" cost="' . $row['cost'] . '" courseId="' . $_GET['id'] . '" >اعتبار سنجی فایل</button> <br> ';
                        echo '</div>';

                        echo '<div class="needCode">';
                        echo '<label for="discount">کد تخفیف:</label>
                    <input type="text"  id="discount">
                    <button id="discountCodeBtn" class="btn btn-primary" name="submit" value="submit" cost="' . $row['cost'] . '" courseId="' . $_GET['id'] . '" >بررسی تخفیف</button> <br> ';
                        echo '</div>';

                    } else {
                        echo "هزینه : " . "<span class='txt-red'>رایگان</span>" . "<br>";
                    }
                }

            }

            if ($row["topicFile"] != Null)
                echo '<a href="../' . $row["topicFile"] . '" class="btn btn-info" role="button" download="' . $row['topicFileName'] . '">فایل سرفصل ها</a> ';
            else
                echo '<a href="../' . $row["topicFile"] . '" class="btn btn-info disabled" role="button">فایل سرفصل ها</a> ';
            if ($row["brochureFile"] != Null)
                echo '<a href="../' . $row["brochureFile"] . '" class="btn btn-info" role="button" download="' . $row['brochureFileName'] . '">فایل بروشور</a>' . "<br>";
            else
                echo '<a href="../' . $row["brochureFile"] . '" class="btn btn-info disabled" role="button">فایل بروشور</a>' . "<br>";
            echo "<br>";

            if (!empty($_SESSION['semail'])) {
                $sql = "SELECT courseId , discountId , status , verify FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "' AND courseId = " . $_GET['id'] . " AND active = 1 AND (status = 1 OR status = 3)";
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    echo '<button id="removeBtn" class="btn btn-danger" name="remove" value="remove" courseId="' . $_GET['id'] . '" email="' . $_SESSION['semail'] . '">حذف کلاس</button>';
                    $res = mysqli_fetch_assoc($result);
                    if ($res['status'] == 1 && $res['verify'] == 1)
                        echo '<a href="card.php?id=' . $_GET['id'] . '" class="btn btn-success mr-1" role="button">دریافت کارت ورود به جلسه</a>';
                } else {
                    if (intval($remaining_cap) > 0)
                        echo '<button id="submitBtn" class="btn btn-success" name="submit" value="submit" code="' . $discountCode . '" courseId="' . $_GET['id'] . '" email="' . $_SESSION['semail'] . '">ثبت کلاس</button>';
                    else
                        echo '<button id="submitBtn" class="btn btn-warning" name="submit" value="submit" code="' . $discountCode . '" courseId="' . $_GET['id'] . '" email="' . $_SESSION['semail'] . '">رزرو</button>';

                }
            } else {
                if (isset($_GET['reagent']) && !empty($_GET['reagent']))
                    echo "<span>" . "برای ثبت کلاس ابتدا باید " . "<a href='../login.php?id=" . $_GET['id'] . "&reagent=" . $_GET['reagent'] . "'>وارد سایت شوید</a>" . ". اگر قبلا در سایت ثبت نام نکرده اید از این لینک " . "<a href='../signup.php?id=" . $_GET['id'] . "&reagent=" . $_GET['reagent'] . "'>ثبت نام کنید</a>." . "</span>";
                else
                    echo "<span>" . "برای ثبت کلاس ابتدا باید " . "<a href='../login.php?id=" . $_GET['id'] . "'>وارد سایت شوید</a>" . ". اگر قبلا در سایت ثبت نام نکرده اید از این لینک " . "<a href='../signup.php?id=" . $_GET['id'] . "'>ثبت نام کنید</a>." . "</span>";

            }

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
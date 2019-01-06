<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php
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
                    header("location:courses.php");
                }
            } else {
                header("location:courses.php");
            };
        } else {
            header("location:courses.php");
        }
        ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/stu-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</head>
<body dir="rtl" class="text-right">
<div class="container">

    <?php

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
    echo "عنوان : " . $row['title'] . "<br>";

    echo "توضیحات : " . "<br>";
    echo htmlspecialchars_decode(stripslashes($row['description']));
    //    echo "<br>";


    echo "تاریخ شروع : " . $row['startDate'] . "<span class='txt-red'> در صورت عدم رسیدن به حد نصاب این تاریخ ممکن است به تعویق بیافتد. توجه داشته باشید که پس از قطعی شدن تاریخ شروع کلاس با شما تماس گرفته خواهد شد.</span>" .  "<br>";
    echo "تاریخ پایان : " . $row['endDate'] . "<br>";
    echo "روز های برگزاری : " . $row['holdingDays'] . "<br>";

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
                    echo "هزینه : " . "رایگان" . "<br>";
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
                    echo "هزینه : " . "رایگان" . "<br>";
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
                    echo "هزینه : " . "رایگان" . "<br>";
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
                    echo "هزینه : " . "رایگان" . "<br>";
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
                echo "هزینه : " . "رایگان" . "<br>";
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
                echo "هزینه : " . "رایگان" . "<br>";
            }
        }

    }

    echo "ظرفیت : " . $row['capacity'] . "<br>";
    echo "سرفصل ها : " . $row['topicText'] . "<br>";
    if ($row["topicFile"] != Null)
        echo '<a href="../../' . $row["topicFile"] . '" class="btn btn-info" role="button" download="' . $row['topicFileName'] . '">فایل سرفصل ها</a> ';
    else
        echo '<a href="../../' . $row["topicFile"] . '" class="btn btn-info disabled" role="button">فایل سرفصل ها</a> ';
    if ($row["brochureFile"] != Null)
        echo '<a href="../../' . $row["brochureFile"] . '" class="btn btn-info" role="button" download="' . $row['brochureFileName'] . '">فایل بروشور</a>' . "<br>";
    else
        echo '<a href="../../' . $row["brochureFile"] . '" class="btn btn-info disabled" role="button">فایل بروشور</a>' . "<br>";
    echo "<br>";

    if (!empty($_SESSION['semail'])) {
        $sql = "SELECT courseId , discountId FROM orders WHERE studentMail = '" . $_SESSION['semail'] . "' AND courseId = " . $_GET['id'] . " AND active = 1 AND status = 1";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<button id="removeBtn" class="btn btn-danger" name="remove" value="remove" courseId="' . $_GET['id'] . '" email="' . $_SESSION['semail'] . '">حذف کلاس</button>';
            echo '<a href="card.php?id=' .$_GET['id']. '" class="btn btn-success mr-1" role="button">دریافت کارت ورود به جلسه</a>';
        } else {
            echo '<button id="submitBtn" class="btn btn-success" name="submit" value="submit" code="' . $discountCode . '" courseId="' . $_GET['id'] . '" email="' . $_SESSION['semail'] . '">ثبت کلاس</button>';
        }
    } else {
        if (isset($_GET['reagent']) && !empty($_GET['reagent']))
            echo "<span>" . "برای ثبت کلاس ابتدا باید " . "<a href='../login.php?id=" . $_GET['id'] . "&reagent=" . $_GET['reagent'] . "'>وارد سایت شوید</a>" . ". اگر قبلا در سایت ثبت نام نکرده اید از این لینک " . "<a href='../signup.php?id=" . $_GET['id'] . "&reagent=" . $_GET['reagent'] . "'>ثبت نام کنید</a>." . "</span>";
        else
            echo "<span>" . "برای ثبت کلاس ابتدا باید " . "<a href='../login.php?id=" . $_GET['id'] . "'>وارد سایت شوید</a>" . ". اگر قبلا در سایت ثبت نام نکرده اید از این لینک " . "<a href='../signup.php?id=" . $_GET['id'] . "'>ثبت نام کنید</a>." . "</span>";

    }

    ?>

</div>
</body>
</html>
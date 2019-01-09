<?php
// Start the session
session_start();

if (!isset($_SESSION['temail']) || empty($_SESSION['temail']))
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/jquery.Bootstrap-PersianDateTimePicker.css"/>
    <link rel="stylesheet" href="../ckeditor/samples/css/samples.css">
    <link rel="stylesheet" href="../ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.js" type="text/javascript"></script>
    <script src="../js/civem.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/calendar.js" type="text/javascript"></script>
    <script src="../js/jquery.Bootstrap-PersianDateTimePicker.js" type="text/javascript"></script>
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="../ckeditor/samples/js/sample.js"></script>
</head>

<body dir="rtl">

<?php
include("sidebar_teacher.php");
?>

<div class="content <?php echo ($_SESSION["isCollapse"]=='true')? 'ac' : '' ?>">

    <?php

    function getOffset()
    {
        if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0) {
            return ($_GET['page'] - 1) * 10;
        } else
            return 0;
    }

    function getPay($id, $db, $cost)
    {
        if ($id != Null) {
            $sql = "SELECT * FROM discount WHERE discountId = " . $id;
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['discountId'] != Null) {
                    if ($row['isRial'] == 1) {
                        return $cost - $row['amount'];
                    } else {
                        return $cost - ($row['amount'] * $cost) / 100;
                    }

                } else {
                    return $cost;
                }
            }
        }
        return $cost;
    }
    echo "<div class='mt-5'><a class='namelink' href='student.php?mail=".$_GET['mail']."'>";
    $sql = "SELECT family FROM students WHERE email = '" . base64_decode($_GET['mail']) . "'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['family'];
    } else {
        header("location:students.php");
    }
    echo "</a></div>";
    $sql = "SELECT title, holdingDays, cost, course.courseId , discountId , receipt, verify , orderId , file FROM orders INNER JOIN course WHERE studentMail = '" . base64_decode($_GET['mail']) . "' AND course.teacherMail = '".$_SESSION['temail']."' AND (status = 1 OR status = 3) AND active = 1 AND course.courseId = orders.courseId ORDER BY course.courseId LIMIT 10 OFFSET " . getOffset();
    $result = mysqli_query($db, $sql);
    echo "<span class='text-right'>لیست دروس ثبت نامی</span>";
    echo '<div class="d-inline-block float-left pb-1">';
    echo '<a href="download/studentcourses_download.php?mail='.$_GET['mail'].'" class="btn btn-info" role="button">دانلود فایل اکسل</a>';
    echo '</div>';
    echo "<table class='table table-striped table-bordered table-hover'>";
    echo "<thead class='thead-dark text-center'> <tr> <th>عنوان</th> <th>روز های برگزاری</th> <th>هزینه کلاس</th> <th style='width: 10%'>مبلغ پرداختی</th> <th style='width: 10%'>نوع تخفیف</th> <th style='width: 10%'>فیش واریز</th> <th style='width: 10%'>لیست دانشجویان</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='clickable-row text-center' data-href='edit_course.php?id=" . $row["courseId"] . "'>";
            echo "<td>" . $row["title"] . "</td>" . "<td>" . $row["holdingDays"] . "</td>" . "<td>" . $row["cost"] . "</td>" ."<td>" . getPay($row['discountId'], $db, $row['cost']) . "</td><td>";
            if ($row['discountId']) {
                $s = "SELECT * FROM discount WHERE discountId = " . $row['discountId'];
                $r = mysqli_query($db, $s);
                $a = mysqli_fetch_assoc($r);
                if ($a['needFile']) {
                    echo 'فایل: ' . '<a class="disFile" target="_blank" href="' . "../" . $row['file'] . '">' . "مشاهده" . '</a>';
                } else {
                    $sq = "SELECT name , family FROM teachers WHERE email = '" . $a['code'] . "'";
                    $res = mysqli_query($db, $sq);
                    if (mysqli_num_rows($res) == 1) {
                        $ans = mysqli_fetch_assoc($res);
                        echo 'معرف: ' . $ans['name'] . " " . $ans['family'];
                    } else if ($a['code'] == 'ALLUSERS') {
                        echo "معرف: همه";
                    } else {
                        echo 'کد: ' . $a['code'];
                    }


                }
            } else
                echo "بدون تخفیف";
            echo "</td>";
            if ($row['receipt']) {
                echo "<td>" . '<a class="disFile" target="_blank" href="' . "../" . $row['receipt'] . '">' . "مشاهده" . '</a>';
                if (!$row['verify'])
                    echo '<a class="disFile" id="submitOrder" orderId="' . $row['orderId'] . '" target="_blank" href="">' . " / تایید" . '</a>';
                echo "</td>";
            } else
                echo "<td></td>";
            echo "<td>" .
                '<a href="students.php?id=' . $row["courseId"] . '" class="btn btn-secondary" role="button">لیست دانشجویان</a>'
                . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr class='text-center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>". "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    ?>


    <?php
    include("pager.php");
    $sql = "SELECT title, holdingDays, cost, course.courseId , discountId FROM orders INNER JOIN course WHERE studentMail = '" . base64_decode($_GET['mail']) . "' AND course.teacherMail = '".$_SESSION['temail']."' AND (status = 1 OR status = 3) AND active = 1 AND course.courseId = orders.courseId";
    createPager($sql, $db);
    ?>

</div>

</body>
</html>
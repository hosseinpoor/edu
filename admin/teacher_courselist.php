<?php
// Start the session
session_start();

if (!isset($_SESSION['aemail']) || empty($_SESSION['aemail']))
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
include("sidebar_admin.php");
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

    function getSubmitCount($id, $db)
    {
        $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 1";
        $result = mysqli_query($db, $sql);
        return mysqli_num_rows($result);
    }

    function getTotalPay($id, $db)
    {
        $total = 0;
        $sql = "SELECT cost , discountId FROM orders INNER JOIN course WHERE orders.courseId = " . $id . " AND course.courseId = orders.courseId AND active = 1 AND status = 1";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['discountId'] != Null) {
                $s = "SELECT * FROM discount WHERE discountId = " . $row['discountId'];
                $r = mysqli_query($db, $s);
                $count = mysqli_num_rows($r);
                if ($count == 1) {
                    $res = mysqli_fetch_assoc($r);
                    if ($res['isRial'] == 1) {
                        $total += $row['cost'] - $res['amount'];
                    } else {
                        $total += $row['cost'] - ($res['amount'] * $row['cost']) / 100;
                    }
                }
            } else {
                $total += $row['cost'];
            }
        }
        return $total;
    }

    echo "<div class='mt-5'><a class='namelink' href='teacher.php?mail=".$_GET['mail']."'>";
    $sql = "SELECT family FROM teachers WHERE email = '" . $_GET['mail'] . "'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['family'];
    } else {
        header("location:teachers.php");
    }
    echo "</a></div>";
    $sql = "SELECT title, holdingDays, cost, courseId , name , family FROM course INNER JOIN teachers WHERE teacherMail = email AND teacherMail = '".$_GET['mail']."' ORDER BY courseId DESC LIMIT 10 OFFSET " . getOffset();
    $result = mysqli_query($db, $sql);
    echo "<span class='text-right'>لیست دروس ثبت نامی</span>";
    echo '<a href="download/teachercourses_download.php?mail='.$_GET["mail"].'" class="btn btn-info float-left" role="button">دانلود فایل اکسل</a>';
    echo "<table class='table table-striped table-bordered table-hover mt-3'>";
    echo "<thead class='thead-dark text-center'> <tr> <th style='width: 15%'>عنوان</th> <th style='width: 15%'>استاد</th> <th style='width: 15%'>روز های برگزاری</th> <th style='width: 15%'>هزینه</th> <th style='width: 15%'>تعداد ثبت نامی</th> <th style='width: 15%'>مبلغ کل ثبت نام</th> <th style='width: 10%'>لیست دانشجویان</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='clickable-row text-center' data-href='edit_course.php?id=" . $row["courseId"] . "'>";
            echo "<td>" . $row["title"] . "</td>" . "<td>" . $row['name'] . " " . $row['family'] . "</td>" . "<td>" . $row["holdingDays"] . "</td>" . "<td>" . $row["cost"] . "</td>" . "<td>" . getSubmitCount($row['courseId'], $db) . "</td>" . "<td>" . getTotalPay($row['courseId'], $db) . "</td>" . "<td>" .
                '<a href="students.php?id=' . $row["courseId"] . '" class="btn btn-secondary" role="button">لیست دانشجویان</a>'
                . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr class='text-center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    ?>

    <?php
    include("pager.php");
    $sql = "SELECT title, holdingDays, cost, courseId , name , family FROM course INNER JOIN teachers WHERE teacherMail = email AND teacherMail = '".$_GET['mail']."'";
    createPager($sql, $db);
    ?>


</div>

</body>
</html>
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
                $sql = "SELECT family FROM students WHERE email = '" . $_GET['mail'] . "'";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/jquery.Bootstrap-PersianDateTimePicker.css"/>
    <link rel="stylesheet" href="../../ckeditor/samples/css/samples.css">
    <link rel="stylesheet" href="../../ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.js" type="text/javascript"></script>
    <script src="../../js/civem.js"></script>
    <script src="../../js/script.js"></script>
    <script src="../../js/calendar.js" type="text/javascript"></script>
    <script src="../../js/jquery.Bootstrap-PersianDateTimePicker.js" type="text/javascript"></script>
    <script src="../../ckeditor/ckeditor.js"></script>
    <script src="../../ckeditor/samples/js/sample.js"></script>
</head>

<body dir="rtl">

<?php
include("sidebar_admin.php");
?>

<div class="content">

    <?php
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
    echo "<h1 style='text-align: right;'>";
    $sql = "SELECT family FROM students WHERE email = '" . $_GET['mail'] . "'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['family'];
    } else {
        header("location:students.php");
    }
    echo "</h1>";
    $sql = "SELECT title, holdingDays, cost, course.courseId , discountId FROM orders INNER JOIN course WHERE studentMail = '" . $_GET['mail'] . "' AND status = 1 AND active = 1 AND course.courseId = orders.courseId";
    $result = mysqli_query($db, $sql);
    echo "<span style='text-align: right;'>لیست دروس ثبت نامی</span>";
    echo "<table style='margin-top: 15px' class='table table-striped table-bordered table-hover'>";
    echo "<thead class='thead-dark' style='text-align:center'> <tr> <th style='width: 28%'>عنوان</th> <th style='width: 28%'>روز های برگزاری</th> <th style='width: 28%'>هزینه کلاس</th> <th style='width: 28%'>مبلغ پرداختی</th> <th style='width: 16%'>لیست دانشجویان</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='clickable-row' data-href='edit_course.php?id=" . $row["courseId"] . "' style='text-align:center'>";
            echo "<td>" . $row["title"] . "</td>" . "<td>" . $row["holdingDays"] . "</td>" . "<td>" . $row["cost"] . "</td>" ."<td>" . getPay($row['discountId'], $db, $row['cost']) . "</td>". "<td>" .
                '<a href="students.php?id=' . $row["courseId"] . '" class="btn btn-secondary" role="button">لیست دانشجویان</a>'
                . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr style='text-align:center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    ?>


</div>

</body>
</html>
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
    <title>پنل مدیریت</title>

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
    function getOffset()
    {
        if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0) {
            return ($_GET['page'] - 1) * 10;
        } else
            return 0;
    }

    $sql = "SELECT * FROM (teachers LEFT JOIN course ON teachers.email = course.teacherMail) ORDER BY teachers.family LIMIT 10 OFFSET " . getOffset();
    $result = mysqli_query($db, $sql);
    echo "<h1 class='text-right'>پنل مدیریت</h1>";
    echo "<span class='text-right'>لیست تمام اساتید</span>";
    echo '<a href="new_teacher.php" class="btn btn-success float-left" role="button">استاد جدید</a>';
    echo '<a href="download/teachers_download.php" class="btn btn-info float-left ml-1" role="button">دانلود فایل اکسل</a>';
    echo "<table class='table table-striped table-bordered table-hover mt-3'>";
    echo "<thead class='thead-dark text-center'> <tr> <th style='width: 28%'>نام و نام خانوادگی</th> <th style='width: 28%'>رایانامه</th> <th style='width: 28%'>شماره تماس</th> <th style='width: 16%'>دروس تدریسی</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='clickable-row text-center' data-href='teacher.php?mail=" . $row["email"] . "'>";
            echo "<td>" . $row["name"] . " " . $row["family"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["phoneNum"] . "</td>" . "<td>" ;
            if($row['title'] != "") echo '<a href="students.php?id=' . $row["courseId"] . '" class="btn btn-secondary" role="button">' . $row['title'] . '</a>' ;
            echo "</td>";
            echo "</tr>";

        }
    } else {
        echo "<tr class='text-center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    ?>

    <?php
    include("pager.php");
    $sql = "SELECT * FROM (teachers LEFT JOIN course ON teachers.email = course.teacherMail)";
    createPager($sql, $db);
    ?>

</div>

</body>
</html>

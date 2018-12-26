<?php
// Start the session
session_start();
if (!isset($_SESSION['temail']) || empty($_SESSION['temail']))
    header("location:../login.php");

$db = @mysqli_connect("localhost", "root", "", "ebbroker");

if (!mysqli_connect_error()) {
    mysqli_query($db, "SET NAMES utf8");
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
    <title>پنل اساتید</title>

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

    $sql = "SELECT courseId from course WHERE teacherMail = '".$_SESSION['temail']."'";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $courses[] = $row['courseId'];
    }

    $sql = "SELECT * FROM discount WHERE courseId IN (".implode(",",$courses).") ORDER BY discountId DESC LIMIT 10 OFFSET " . getOffset();
    $result = mysqli_query($db, $sql);
    echo "<h1 class='text-right'>پنل اساتید</h1>";
    echo "<span class='text-right'>لیست تخفیف ها</span>";
    echo '<a href="new_discount.php" class="btn btn-success float-left" role="button">تخفیف جدید</a>';
    echo '<a href="download/discounts_download.php" class="btn btn-info float-left ml-1" role="button">دانلود فایل اکسل</a>';
    echo "<table class='table table-striped table-bordered table-hover mt-3'>";
    echo "<thead class='thead-dark text-center'> <tr> <th>درس</th> <th>نوع</th> <th>مقدار</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $s = "SELECT title FROM course WHERE courseId = " . $row["courseId"];
            $r = mysqli_query($db, $s);
            $crow = mysqli_fetch_assoc($r);
            echo "<tr class='clickable-row text-center' data-href='edit_discount.php?id=" . $row["discountId"] . "'>";
            if ($row['isRial'])
                echo "<td>" . $crow["title"] . "</td>" . "<td>" . $row["type"] . "</td>" . "<td>" . $row["amount"] . " ریال</td>";
            else
                echo "<td>" . $crow["title"] . "</td>" . "<td>" . $row["type"] . "</td>" . "<td>" . $row["amount"] . " %</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr class='text-center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    ?>

    <?php
    include("pager.php");
    $sql = "SELECT * FROM discount";
    createPager($sql, $db);
    ?>

</div>

</body>
</html>

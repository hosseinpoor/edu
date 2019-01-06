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
    <title>پنل مدیریت</title>

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

    $sql = "SELECT * FROM (students LEFT JOIN orders ON students.email = orders.studentMail) INNER JOIN course ON course.courseId = orders.courseId WHERE active = 1 AND status = 1 ORDER BY students.family LIMIT 10 OFFSET " . getOffset();
    $result = mysqli_query($db, $sql);
    echo "<h1 class='text-right'>پنل مدیریت</h1>";
    echo "<span class='text-right'>لیست تمام دانشجویان</span>";
    echo '<div class="d-inline-block float-left pb-1">';
    echo '<a href="download/allstudents_download.php" class="btn btn-info" role="button">دانلود فایل اکسل</a>';
    echo '</div>';
    echo "<table class='table table-striped table-bordered table-hover'>";
    echo "<thead class='thead-dark text-center'> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تماس</th> <th style='width: 10%'>دروس ثبت نامی</th>  <th style='width: 10%'>نوع تخفیف</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='clickable-row text-center' data-href='student_courses.php?mail=" . $row["email"] . "'>";
            echo "<td>" . $row["name"] . " " . $row["family"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["phoneNum"] . "</td>" . "<td>" .
                '<a href="students.php?id=' . $row["courseId"] . '" class="btn btn-secondary" role="button">' . $row['title'] . '</a>'
                . "</td>". "<td>";
            if($row['discountId']){
                $s = "SELECT * FROM discount WHERE discountId = ".$row['discountId'];
                $r = mysqli_query($db , $s);
                $a = mysqli_fetch_assoc($r);
                if($a['needFile']){
                    echo 'فایل: '  . '<a class="disFile" target="_blank" href="' . "download/disFile.php?id=".$row['orderId'] . '">' . "مشاهده" . '</a>';
                }
                else{
                    $sq = "SELECT name , family FROM teachers WHERE email = '".$a['code']."'";
                    $res = mysqli_query($db , $sq);
                    if(mysqli_num_rows($res) == 1){
                        $ans = mysqli_fetch_assoc($res);
                        echo 'معرف: ' . $ans['name'] . " " . $ans['family'];
                    }
                    else{
                        echo 'کد: ' . $a['code'];
                    }


                }
            }
            else
                echo "بدون تخفیف";
            echo "</td>";
            ;
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
    $sql = "SELECT * FROM (students LEFT JOIN orders ON students.email = orders.studentMail) INNER JOIN course ON course.courseId = orders.courseId WHERE active = 1 AND status = 1";
    createPager($sql, $db);
    ?>

</div>


</body>
</html>

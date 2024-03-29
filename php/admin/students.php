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
                    header("location:admin.php");
                }
            } else {
                header("location:admin.php");
            };
        } else {
            header("location:admin.php");
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


    $cost = 0;
    echo "<h1 style='text-align: right;'>لیست دانشجویان</h1>";
    echo '<a style="float: left;" href="download/students_download.php?id='.$_GET['id'].'" class="btn btn-info" role="button">دانلود فایل اکسل</a>';
    $sql = "SELECT title , cost FROM course WHERE courseId = " . $_GET['id'];
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<div style='text-align: right;padding: 10px;'>افراد ثبت نام کرده در " . $row['title'] . "</div>";
        $cost = $row['cost'];
    } else {
        echo "<div style='text-align: right;padding: 10px;'>افراد ثبت نام کرده در کلاس</div>";
    }


    $sql = "SELECT studentMail , discountId  FROM orders WHERE status = 1 AND active = 1 AND courseId = " . $_GET['id']." ORDER BY orderId DESC LIMIT 10 OFFSET " . getOffset();
    $result = mysqli_query($db, $sql);
    echo "<table class='table table-striped table-bordered table-hover'>";
    echo "<thead class='thead-dark' style='text-align:center'> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تلفن همراه</th> <th>مبلغ پرداختی</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $s = "SELECT name, family, email , phoneNum FROM students WHERE email = '" . $row["studentMail"] . "'";
            $r = mysqli_query($db, $s);
            while ($crow = mysqli_fetch_assoc($r)) {
                echo "<tr class='clickable-row' data-href='student.php?mail=" . $row["studentMail"] . "' style='text-align:center'>";
                echo "<td>" . $crow["name"] . " " . $crow["family"] . "</td>" . "<td>" . $crow["email"] . "</td>" . "<td>" . $crow["phoneNum"] . "</td>" . "<td>" . getPay($row['discountId'], $db, $cost) . "</td>";
                echo "</tr>";
            }
        }

    } else {
        echo "<tr style='text-align:center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    ?>

    <?php
    include("pager.php");
    $sql = "SELECT studentMail , discountId  FROM orders WHERE status = 1 AND active = 1 AND courseId = " . $_GET['id'];
    createPager($sql , $db);
    ?>


</div>

</body>
</html>
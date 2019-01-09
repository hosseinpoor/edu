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


    $cost = 0;
    echo "<h1 class='text-right'>لیست دانشجویان</h1>";
    echo '<div class="d-inline-block float-left pb-1">';
    echo '<a href="download/students_download.php?id='.$_GET['id'].'" class="btn btn-info" role="button">دانلود فایل اکسل</a>';
    echo '</div>';

    $sql = "SELECT * FROM conf";
    $result = mysqli_query($db, $sql);
    $auth = mysqli_fetch_assoc($result);

    $sql = "SELECT title , cost FROM course WHERE courseId = " . $_GET['id'];
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<div class='text-right p-2'>افراد ثبت نام کرده در " . $row['title'] . "</div>";
        $cost = $row['cost'];
    } else {
        echo "<div class='text-right p-2'>افراد ثبت نام کرده در کلاس</div>";
    }


    $s = "SELECT studentMail , discountId , receipt, verify , orderId , file  FROM orders WHERE (status = 1 OR status = 3) AND active = 1 AND courseId = " . $_GET['id']." ORDER BY orderId DESC LIMIT 10 OFFSET " . getOffset();
    $res = mysqli_query($db, $s);
    echo "<table class='table table-striped table-bordered table-hover'>";
    echo "<thead class='thead-dark text-center'> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تلفن همراه</th> <th style='width: 10%'>مبلغ پرداختی</th> <th style='width: 10%'>نوع تخفیف</th> <th style='width: 10%'>فیش واریز</th> </tr> </thead>";
    echo "<tbody>";
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $s = "SELECT name, family, email , phoneNum FROM students WHERE email = '" . $row["studentMail"] . "'";
            $r = mysqli_query($db, $s);
            while ($crow = mysqli_fetch_assoc($r)) {
                $email = ($auth['emailAuth']==1)? $crow['email'] :  "شما مجاز به مشاهده این بخش نیستید";
                $phone = ($auth['phoneAuth']==1)? $crow['phoneNum'] :  "شما مجاز به مشاهده این بخش نیستید";
                echo "<tr class='clickable-row text-center' data-href='student_courselist.php?mail=" . base64_encode($row["studentMail"]) . "'>";
                echo "<td>" . $crow["name"] . " " . $crow["family"] . "</td>" . "<td>" . $email . "</td>" . "<td>" . $phone . "</td>" . "<td>" . getPay($row['discountId'], $db, $cost) . "</td>". "<td>";
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
                if($row['receipt']) {
                    echo "<td>" . '<a class="disFile" target="_blank" href="' . "../" . $row['receipt'] . '">' . "مشاهده" . '</a>';
                    if (!$row['verify'])
                        echo '<a class="disFile" id="submitOrder" orderId="' . $row['orderId'] . '" target="_blank" href="">' . " / تایید" . '</a>';
                    echo "</td>";
                }
                else
                    echo "<td></td>";
                echo "</tr>";
            }
        }

    } else {
        echo "<tr class='text-center'>";
        echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    ?>

    <?php
    include("pager.php");
    $sql = "SELECT studentMail , discountId  FROM orders WHERE (status = 1 OR status = 3) AND active = 1 AND courseId = " . $_GET['id'];
    createPager($sql , $db);
    ?>


</div>

</body>
</html>
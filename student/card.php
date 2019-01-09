<?php
session_start();
if(empty($_SESSION['semail']) || !isset($_SESSION['semail']) || empty($_GET['id']) || !isset($_GET['id']))
    header("location:../login.php");

$name = '';
$family = '';
$image = '';
$title = '';
$days = '';
$qr = '';

$db = @mysqli_connect("localhost", "root", "", "ebbroker");

if (!mysqli_connect_error()) {
    mysqli_query($db, "SET NAMES utf8");

    $query = "select orderId from orders where studentMail = '" . $_SESSION['semail'] . "' and courseId = " . $_GET['id'] . " and active = 1 and status = 1 and verify = 1";
    $res = mysqli_query($db , $query);
    if(mysqli_num_rows($res) != 1)
        header("location:panel.php");

    $s = "select name , family , image from students where email = '" .$_SESSION['semail']. "'";
    $r = mysqli_query($db , $s);
    if(mysqli_num_rows($r) == 1){
        $a = mysqli_fetch_assoc($r);
        $name = $a['name'];
        $family = $a['family'];
        $image = $a['image'];
    }

    $s = "select title , holdingDays , qrCode from course where courseId = " .$_GET['id'];
    $r = mysqli_query($db , $s);
    if(mysqli_num_rows($r) == 1){
        $a = mysqli_fetch_assoc($r);
        $title = $a['title'];
        $days = $a['holdingDays'];
        $qr = $a['qrCode'];
    }
}
else{
    header("location:../login.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>پرینت کارت</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/stu-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</head>

    <page size="A5" layout="landscape">

        <div class="row">
            <div class="col-8">
                <span class="txt-2em"> نام:</span>
                <span class="txt-2em"><?php echo $name ?></span>
                <br>

                <span class="txt-2em"> نام خانوادگی:</span>
                <span class="txt-2em"><?php echo $family ?></span>
                <br>

                <span class="txt-2em">عنوان کلاس: </span>
                <span class="txt-2em"><?php echo $title ?></span>
                <br>

                <span class="txt-1hem text-right">روز های برگزاری: </span>
                <br>
                <span class="txt-1hem"><?php echo $days ?></span>
                <br>

                <span class="txt-1hem">مکان تشکیل کلاس: </span>
                <br>
                <span class="txt-1hem">خیابان ولیعصر - بالاتر از پارک ملت - خیابان پروین - پلاک 48 - طبقه 4 - کارگزاری اقتصاد بیدار</span>
            </div>
            <div class="col-4">
                <?php
                if($image == "NULL" || $image == "")
                    echo '<img src="../img/avatar.png" alt="" style = "width:100%">';
                else
                    echo '<img src="../'.$image.'" alt="" style = "width:100%">';
                ?>
                <br><br>
                <?php
                if($qr == "NULL" || $qr == "")
                    echo '<img src="../img/qr.png" alt="" style = "width:100%">';
                else
                    echo '<img src="../'.$qr.'" alt="" style = "width:100%">';
                ?>
            </div>
        </div>

    </page>


</html>
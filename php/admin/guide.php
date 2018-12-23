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


</body>
</html>

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
    <title>استاد جدید</title>

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

    <h2>تعریف استاد جدید</h2>

    <form action="signup_teacher.php" enctype="multipart/form-data" method="post" autocomplete="off">

        <div class="form-group">
            <span class="required">*</span>
            <label for="email">رایانامه:</label>
            <input type="email" class="form-control" id="email" name="email" title="رایانامه" required
                   data-errormessage="لطفا یک رایانامه معتبر وارد کنید"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="pwd">گذرواژه:</label>
            <input type="password" class="form-control" id="pwd" name="pswd" minlength="4" title="گذرواژه" required
                   data-errormessage="گذرواژه باید حداقل 4 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="name">نام:</label>
            <input type="text" class="form-control" id="name" name="name" minlength="3" title="نام" required
                   data-errormessage="نام باید حداقل 3 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="family">نام خانوادگی:</label>
            <input type="text" class="form-control" id="family" name="family" minlength="3" title="نام خانوادگی"
                   required
                   data-errormessage="نام خانوادگی باید حداقل 3 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="phoneNum">تلفن همراه:</label>
            <input type="text" class="form-control" id="phoneNum" name="phoneNum" minlength="10" maxlength="11"
                   title="تلفن همراه" required
                   data-errormessage="شماره تلفن همراه باید حداقل 10 رقم باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="education">تحصیلات:</label>
            <input type="text" class="form-control" id="education" title="تحصیلات" name="education"  required
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد">
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="province">استان:</label>
            <input type="text" class="form-control" id="province" name="province"
                   title="استان" required
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <label for="address">آدرس:</label>
            <input type="text" class="form-control" id="address" name="address" title="آدرس">
        </div>
        <div class="form-group">
            <label for="sex">جنسیت:</label>
            <br>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="sex" checked="checked"> مرد
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="sex"> زن
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="birthDay">تاریخ تولد : </label>
            <input type="text" class="form-control" id="fromDate1" placeholder="تاریخ تولد" data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="birthDay" title="تاریخ تولد"
                   />
        </div>
        <div class="form-group">
            <label for="birthCity">محل تولد:</label>
            <input type="text" class="form-control" id="birthCity" name="birthCity" title="محل تولد"
            >
        </div>
        <div class="form-group">
            <label for="landlineNum">تلفن ثابت:</label>
            <input type="text" class="form-control" id="landlineNum" name="landlineNum" minlength="8" title="تلفن ثابت"
                   maxlength="11"
                   data-errormessage="شماره تلفن ثابت باید حداقل 8 رقم باشد"
            >
        </div>
        <div class="form-group">
            <label for="dadsName">نام پدر:</label>
            <input type="text" class="form-control" id="dadsName" name="dadsName" title="نام پدر" minlength="3"
                   data-errormessage="نام پدر باید حداقل 3 حرف باشد"
            >
        </div>
        <div class="form-group">
            <label for="pictureFile">تصویر : </label>
            <input type="file" class="form-control-file border" name="pictureFile" id="pictureFile"
                   title="تصویر استاد">
        </div>
        <button type="submit" class="btn btn-success" name="submit" value="signup">ثبت استاد</button>
    </form>

    <br>

</div>

</body>
</html>

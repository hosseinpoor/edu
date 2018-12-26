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
    <title>تخفیف جدید</title>

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


    <h2>افزودن تخفیف جدید</h2>

    <form action="add_discount.php" method="post" autocomplete="off">

        <div class="form-group">
            <span class="required">*</span>
            <label for="course">درس:</label>
            <select class="form-control" name="course" id="course">

                <?php
                $sql = "SELECT title, courseId FROM course WHERE teacherMail = '".$_SESSION['temail']."'";
                $result = mysqli_query($db, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['courseId'] . "'>" . $row['title'] . "</option>";
                }
                ?>

            </select>
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="type">نوع:</label>
            <label><input type="checkbox" name="reagentCheck" id="reagentCheck" value="false"> لینک معرف </label>
            <input type="text" id="discountType" class="form-control noReagentType" name="type" title="نوع" required
                   data-errormessage="این قسمت نمیتواند خالی باشد">
        </div>


        <div class="form-group noReagentType">
            <span class="required">*</span>
            <label for="code">کد تخفیف:</label>
            <input type="text" class="form-control" id="discountCode" name="code" title="نوع" required
                   data-errormessage="این قسمت نمیتواند خالی باشد">
        </div>

        <div class="form-group reagentType">
            <span class="required">*</span>
            <label for="reagent">معرف:</label>
            <select class="form-control" name="reagent" id="reagent">

                <?php
                $sql = "SELECT users.email , name , family FROM users JOIN teachers ON users.email = teachers.email";
                $result = mysqli_query($db, $sql);
                echo "<option value='" . "ALLUSERS" . "'>" . "همه" . "</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['email'] . "'>" . $row['name'] . " " . $row['family'] . "</option>";
                }
                ?>

            </select>
        </div>

        <div class="reagentType">
            <label for="link">لینک تخفیف:</label>
            <span id="link"></span>
        </div>

        <div class="form-group">
            <span class="required">*</span>
            <label for="startDate">تاریخ اعمال : </label>
            <input type="text" class="form-control" id="fromDate3" placeholder="تاریخ اعمال"
                   data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate3" data-groupid="group3" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="startDate" title="تاریخ شروع"/>
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="endDate">تاریخ انقضا : </label>
            <input type="text" class="form-control" id="fromDate4" placeholder="تاریخ انقضا"
                   data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate4" data-groupid="group4" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="endDate" title="تاریخ پایان"/>
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="amount">مقدار:</label>
            <input type="text" class="form-control-inline" name="amount" title="مقدار" required
                   data-errormessage="این قسمت نمیتواند خالی باشد">
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="isrial" checked="checked">ریال
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="isrial">درصد
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="login">افزودن</button>
    </form>

</div>
</body>
</html>

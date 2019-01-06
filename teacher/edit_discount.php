<?php
// Start the session
session_start();
if (empty($_SESSION['temail']))
    header("location:../login.php");
if (isset($_GET['id']) && !empty($_GET['id']) && !isset($_POST['submit'])) {
    $db = @mysqli_connect("localhost", "root", "", "ebbroker");
    if (!mysqli_connect_error()) {
        mysqli_query($db, "SET NAMES utf8");
        $sql = "SELECT * FROM discount WHERE discountId = " . $_GET['id'];
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $startDate = $row['startDate'];
            $startDate = str_replace('-', '/', $startDate);
            $endDate = $row['endDate'];
            $endDate = str_replace('-', '/', $endDate);
            $amount = $row['amount'];
            $isRial = $row['isRial'];
            $type = $row['type'];
            $courseId = $row['courseId'];
            $discountId = $row['discountId'];
            $code = $row['code'];
            $needFile = $row['needFile'];

        } else {
            header("location:courses.php");
        }
    } else {
        header("location:courses.php");
    };
} else {
    header("location:courses.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ویرایش</title>
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

    <h2>ویرایش اطلاعات تخفیف</h2>
    <form action="res_edit_discount.php?id=<?php echo $discountId ?>" method="post" autocomplete="off">

        <div class="form-group">
            <span class="required">*</span>
            <label for="course">درس:</label>
            <select class="form-control" name="course" id="course">

                <?php
                $sql = "SELECT title, courseId FROM course WHERE teacherMail = '".$_SESSION['temail']."'";
                $result = mysqli_query($db, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['courseId'] == $courseId)
                        echo "<option selected='selected' value='" . $row['courseId'] . "'>" . $row['title'] . "</option>";
                    else
                        echo "<option value='" . $row['courseId'] . "'>" . $row['title'] . "</option>";
                }

                ?>

            </select>
        </div>


        <div class="form-group">
            <span class="required">*</span>
            <label for="type">نوع:</label>
            <label><input type="checkbox" name="reagentCheck"
                          id="reagentCheck" <?php if ($type == "معرف") echo " checked"; ?>> لینک معرف </label>
            <input type="text" id="discountType" class="form-control noReagentType" name="type" title="نوع" required
                   value="<?php echo $type ?>"
                   data-errormessage="این قسمت نمیتواند خالی باشد">
        </div>


        <div class="form-group noReagentType">
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="needFile" id="codeId" <?php if(!$needFile) echo 'checked' ?> > نیازمند کد
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="needFile" id="fileId" <?php if($needFile) echo 'checked' ?>> نیازمند فایل
                </label>
            </div>
        </div>

        <div class="form-group noReagentType havecode">
            <span class="required">*</span>
            <label for="code">کد تخفیف:</label>
            <input type="text" class="form-control" id="discountCode" name="code" title="کد تخفیف" required
                   value="<?php echo $code ?>"
                   data-errormessage="این قسمت نمیتواند خالی باشد">
        </div>

        <div class="form-group reagentType">
            <span class="required">*</span>
            <label for="reagent">معرف:</label>
            <select class="form-control" name="reagent" id="reagent">

                <?php

                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['email'] == $teacherMail) {
                        echo "<option selected='selected' value='" . $row['email'] . "'>" . $row['name'] . " " . $row['family'] . "</option>";
                    } else
                        echo "<option value='" . $row['email'] . "'>" . $row['name'] . " " . $row['family'] . "</option>";
                }


                $sql = "SELECT users.email , name , family FROM users JOIN teachers ON users.email = teachers.email";
                $result = mysqli_query($db, $sql);
                if ($code == "ALLUSERS")
                    echo "<option selected='selected' value='" . "ALLUSERS" . "'>" . "همه" . "</option>";
                else
                    echo "<option value='" . "ALLUSERS" . "'>" . "همه" . "</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($code == $row['email'])
                        echo "<option selected='selected' value='" . $row['email'] . "'>" . $row['name'] . " " . $row['family'] . "</option>";
                    else
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
            <input type="text" class="form-control" id="fromDate1" placeholder="تاریخ اعمال"
                   data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="startDate" title="تاریخ شروع"
                   required value="<?php echo $startDate ?>"
                   data-errormessage="این قسمت نمیتواند خالی باشد"/>
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="endDate">تاریخ انقضا : </label>
            <input type="text" class="form-control" id="fromDate2" placeholder="تاریخ انقضا"
                   data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate2" data-groupid="group2" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="endDate" title="تاریخ پایان"
                   required value="<?php echo $endDate ?>"
                   data-errormessage="این قسمت نمیتواند خالی باشد"/>
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="amount">مقدار:</label>
            <input type="text" class="form-control-inline" name="amount" title="مقدار" required
                   value="<?php echo $amount ?>"
                   data-errormessage="این قسمت نمیتواند خالی باشد">
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input"
                           name="isrial" <?php if ($isRial) echo "checked='checked'" ?> >ریال
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input"
                           name="isrial" <?php if (!$isRial) echo "checked='checked'" ?>>درصد
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="login">ویرایش</button>
    </form>
</div>

</body>
</html>
<?php
// Start the session
session_start();
if (empty($_SESSION['temail']))
    header("location:../login.php");
if (isset($_GET['id']) && !empty($_GET['id']) && !isset($_POST['submit'])) {
    $db = @mysqli_connect("localhost", "root", "", "ebbroker");
    if (!mysqli_connect_error()) {
        mysqli_query($db, "SET NAMES utf8");
        $sql = "SELECT * FROM course WHERE courseId = " . $_GET['id'];
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $title = $row['title'];
            $description = $row['description'];
            $startDate = $row['startDate'];
            $startDate = str_replace('-', '/', $startDate);
            $endDate = $row['endDate'];
            $endDate = str_replace('-', '/', $endDate);
            $holdingDays = $row['holdingDays'];
            $cost = $row['cost'];
            $capacity = $row['capacity'];
            $topicText = $row['topicText'];
            $topicFile = $row['topicFile'];
            $brochureFile = $row['brochureFile'];
            $courseId = $row['courseId'];
            $teacherMail = $row['teacherMail'];
            $type = $row['isVirtual'];
            $quorum = $row['quorum'];
            $qrCode = $row['qrCode'];
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

    <h2>ویرایش اطلاعات کلاس</h2>
    <form action="res_edit_course.php?id=<?php echo $courseId ?>" method="post" enctype="multipart/form-data"
          autocomplete="off">

        <div class="form-group">
            <label for="type">نوع:</label>
            <br>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="type" <?php if($type==0) echo 'checked' ?>> حضوری
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="type" <?php if($type==1) echo 'checked' ?>> مجازی
                </label>
            </div>
        </div>

        <div class="form-group">
            <span class="required">*</span>
            <label for="title">عنوان:</label>
            <input type="text" class="form-control" name="title" title="عنوان" required
                   data-errormessage="این قسمت نمیتواند خالی باشد"
                   value="<?php echo $title ?>">
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="des">توضیحات:</label>
            <textarea class="form-control" name="des" rows="3" required
                      data-errormessage="این قسمت نمیتواند خالی باشد"
            ><?php echo $description ?></textarea>
            <script>
                CKEDITOR.replace('des');
            </script>
        </div>
        <div class="form-group">
            <label for="startDate">تاریخ شروع : </label>
            <input type="text" class="form-control" id="fromDate1" placeholder="تاریخ شروع" data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="startDate" title="تاریخ شروع"
                   value="<?php echo $startDate ?>"/>
        </div>
        <div class="form-group">
            <label for="endDate">تاریخ پایان : </label>
            <input type="text" class="form-control" id="fromDate2" placeholder="تاریخ پایان"
                   data-MdDateTimePicker="true"
                   data-trigger="click" data-targetselector="#fromDate2" data-groupid="group2" data-fromdate="true"
                   data-enabletimepicker="false" data-placement="bottom" name="endDate" title="تاریخ پایان"
                   value="<?php echo $endDate ?>"/>
        </div>
        <div class="form-group">
            <label for="holdingDays">روز های برگزاری : </label>
            <input type="text" class="form-control" name="holdingDays" title="روز های برگزاری"
                   value="<?php echo $holdingDays ?>">
        </div>
        <div class="form-group">
            <label for="cost">هزینه : </label>
            <input type="text" class="form-control" name="cost" title="هزینه" value="<?php echo $cost ?>">
        </div>
        <div class="form-group">
            <label for="cap">ظرفیت : </label>
            <input type="text" class="form-control" name="cap" title="ظرفیت" value="<?php echo $capacity ?>">
        </div>
        <div class="form-group">
            <label for="quorum">حد نصاب : </label>
            <input type="text" class="form-control" name="quorum" title="حد نصاب" value="<?php echo $quorum ?>">
        </div>
        <div class="form-group">
            <label for="topicText">متن سرفصل : </label>
            <textarea class="form-control" name="topicText" rows="3" required
                      data-errormessage="این قسمت نمیتواند خالی باشد"
            ><?php echo $topicText ?></textarea>
            <script>
                CKEDITOR.replace('topicText');
            </script>
        </div>
        <div class="form-group">
            <label for="topicFile">فایل سرفصل : </label>
            <input type="file" class="form-control-file border" name="topicFile" id="topicFile" title="فایل سرفصل"
                   value="<?php echo $topicFile ?>">
        </div>
        <div class="form-group">
            <label for="brochureFile">فایل بروشور : </label>
            <input type="file" class="form-control-file border" name="brochureFile" id="brochureFile"
                   title="فایل بروشور" value="<?php echo $brochureFile ?>">
        </div>
        <div class="form-group">
            <label for="qrCode">تصویر qrCode : </label>
            <input type="file" class="form-control-file border" name="qrCode" id="qrCode"
                   title="تصویر qrCode" value="<?php echo $qrCode ?>">
        </div>
        <div class="form-group">
            <label for="teacher">استاد:</label>
            <div name="teacher" id="teacher"><?php
                mysqli_query($db, "SET NAMES utf8");
                $sql = "SELECT name, family FROM teachers where email = '" . $_SESSION['temail'] . "'";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_assoc($result);
                echo $row['name'] . " " . $row['family'];
                ?></div>
        </div>

        <button type="submit" class="btn btn-primary" name="submit" value="login">ویرایش</button>
    </form>
</div>


<script>
    initSample();
</script>

</body>
</html>
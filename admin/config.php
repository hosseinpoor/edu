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

<?php
$sql = "SELECT * FROM conf";
$result = mysqli_query($db , $sql);
$row = mysqli_fetch_assoc($result);
?>

<div class="content <?php echo ($_SESSION["isCollapse"]=='true')? 'ac' : '' ?>">
    <h1 class='text-right'>پنل مدیریت</h1>
    <br>
    <h3 class='text-right'>دسترسی اساتید</h3>
    <form id="idForm" action="" method="post" autocomplete="off">
        <div class="form-group">
            <label for="phoneAuth">مشاهده شماره تلفن همراه دانشجویان:</label>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="phoneAuth" <?php if($row['phoneAuth']) echo "checked" ?>> مجاز
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="phoneAuth"  <?php if(!$row['phoneAuth']) echo "checked" ?>> غیرمجاز
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="landlineAuth">مشاهده شماره تلفن ثابت دانشجویان:</label>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="landlineAuth"  <?php if($row['landlineAuth']) echo "checked" ?> > مجاز
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="landlineAuth" <?php if(!$row['landlineAuth']) echo "checked" ?>> غیرمجاز
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="emailAuth">مشاهده رایانامه دانشجویان:</label>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="true" class="form-check-input" name="emailAuth" <?php if($row['emailAuth']) echo "checked" ?>> مجاز
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input type="radio" value="false" class="form-check-input" name="emailAuth" <?php if(!$row['emailAuth']) echo "checked" ?>> غیرمجاز
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-success" name="submit" value="signup" formnovalidate>اعمال تغییرات</button>
    </form>
</div>


</body>
</html>

<?php
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST["submit"])) {

        $db = @mysqli_connect("localhost", "root", "", "ebbroker");

        if (!mysqli_connect_error()) {

            $phoneAuth = mysqli_real_escape_string($db ,test_input($_POST['phoneAuth']));
            $landlineAuth = mysqli_real_escape_string($db ,test_input($_POST['landlineAuth']));
            $emailAuth = mysqli_real_escape_string($db ,test_input($_POST['emailAuth']));

            $sql = "UPDATE conf SET 
                                phoneAuth = $phoneAuth ,
                                landlineAuth = $landlineAuth ,
                                emailAuth = $emailAuth";

            $result = mysqli_query($db, $sql);

            if ($result > 0)
                echo "<script>
                    alert('the configuration updated');
                    window.location.href='config.php';
                    </script>";
            else
                echo "<script>
                    alert('error in updating the configuration');
                    </script>";

            mysqli_close($db);

        } else {
            echo "<script>
                        alert('error in connecting to DB. please try again later');
                        </script>";
        }

    }



?>
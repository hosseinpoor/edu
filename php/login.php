<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ورود</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/civem.js"></script>
    <script src="../js/script.js"></script>
</head>

<body dir="rtl">

<div class="container">
    <h2>ورود</h2>
    <form id="idForm" action="" method="post" autocomplete="off">
        <div class="form-group">
            <span class="required">*</span>
            <label for="email">رایانامه:</label>
            <input type="email" class="form-control" id="email" name="email" title="رایانامه"
                   required
                   data-errormessage="لطفا یک رایانامه معتبر وارد کنید"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <div class="form-group">
            <span class="required">*</span>
            <label for="pwd">گذرواژه:</label>
            <input type="password" class="form-control" id="pwd" name="pswd"
                   title="گذرواژه" required
                   data-errormessage="گذرواژه باید حداقل 4 حرف باشد"
                   data-errormessage-value-missing="این قسمت نمیتواند خالی باشد"
            >
        </div>
        <button type="submit" class="btn btn-primary" name="submit" value="login">ورود</button>
        <button type="submit" class="btn btn-success" name="submit" value="signup" formnovalidate>ثبت نام</button>
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

if (isset($_POST['submit'])) {

    $email_unsafe = test_input($_POST['email']);
    $password_unsafe = test_input($_POST['pswd']);

    if ($_POST['submit'] == 'login') {
        if (!empty($email_unsafe) && !empty($password_unsafe)) {

            $db = @mysqli_connect("localhost", "root", "", "ebbroker");

            if (!mysqli_connect_error()) {

                $email = mysqli_real_escape_string($db, $email_unsafe);
                $password = mysqli_real_escape_string($db, $password_unsafe);

                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($db, $sql);
                $count = mysqli_num_rows($result);
                $row = mysqli_fetch_assoc($result);
                if ($count == 1 && password_verify($password, $row['password'])) {

                    switch ($row['role']) {
                        case 2:
                            session_start();
                            $_SESSION["aemail"] = $email;
                            header("location:admin/admin.php");
                            break;
                        case 3:
                            session_start();
                            $_SESSION["temail"] = $email;
                            header("location:teacher/courses.php");
                            break;
                        case 5:
                            session_start();
                            $_SESSION["semail"] = $email;

                            if (!empty($_GET['id']) && !empty($_GET['reagent']))
                                header("location:student/course.php?id=" . $_GET['id'] . "&reagent=" . $_GET['reagent']);
                            else
                                header("location:student/courses.php");
                            break;
                        default:
                            echo "<script>
                            alert('faild to log in');
                            window.location.href='login.php';
                            </script>";
                    }
                } else {
                    echo "<script>
                        alert('faild to log in');
                        window.location.href='login.php';
                        </script>";
                }
                mysqli_close($db);
            } else {
                echo "<script>
                        alert('error in connecting to DB. please try again later');
                        window.location.href='login.php';
                        </script>";
            }

        } else
            header("location:login.php");

    } else
        header("location:signup.php");
}

?>

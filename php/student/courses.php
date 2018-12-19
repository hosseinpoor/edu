<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>دروس</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/script.js"></script>
</head>
<body dir="rtl">

<?php
echo '<div class="container">';


if (isset($_SESSION['semail']) && !empty($_SESSION['semail'])) {

    $db = @mysqli_connect("localhost", "root", "", "ebbroker");
    if (!mysqli_connect_error()) {
        mysqli_query($db, "SET NAMES utf8");

        $sql = "SELECT title, holdingDays, cost, courseId FROM course";
        $result = mysqli_query($db, $sql);
        echo "<span style='text-align: right;'>لیست تمام دروس:</span>";
        echo '<a style="float: left;" href="mycourses.php" class="btn btn-info" role="button">دروس من</a>';
        echo "<table style='margin-top: 15px' class='table table-striped table-bordered table-hover'>";
        echo "<thead class='thead-dark' style='text-align:center'> <tr> <th>عنوان</th> <th>روز های برگزاری</th> <th>هزینه</th> </tr> </thead>";
        echo "<tbody>";
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr class='clickable-row' data-href='course.php?id=" . $row["courseId"] . "' style='text-align:center'>";
                echo "<td>" . $row["title"] . "</td>" . "<td>" . $row["holdingDays"] . "</td>" . "<td>" . $row["cost"] . "</td>";
                echo "</tr>";
            }

        } else {
            echo "<tr style='text-align:center'>";
            echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        mysqli_close($db);
    } else {
        echo "<script>
                alert('error in connecting to DB. please try again later');
                window.location.href='../login.php';
                </script>";
    }
} else {
    header("location:../login.php");
}
echo "</div>";

?>

</body>
</html>

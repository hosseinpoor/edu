<?php

include("download.php");
download("دانشجویان");
$msg = "";

$sql = "SELECT * FROM conf";
$result = mysqli_query($db, $sql);
$r = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM (students LEFT JOIN orders ON students.email = orders.studentMail) INNER JOIN course ON course.courseId = orders.courseId WHERE course.teacherMail = '".$_SESSION['temail']."' AND active = 1 AND status = 1 ORDER BY students.family";
$result = mysqli_query($db, $sql);
echo "<table border='1'>";
echo "<thead> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تماس</th> <th>دروس ثبت نامی</th> </tr> </thead>";
echo "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $email = ($r['emailAuth']==1)? $row['email'] :  "شما مجاز به مشاهده این بخش نیستید";
        $phone = ($r['phoneAuth']==1)? $row['phoneNum'] :  "شما مجاز به مشاهده این بخش نیستید";
        echo "<tr>";
        echo "<td>" . $row["name"] . " " . $row["family"] . "</td>" . "<td>" . $email . "</td>" . "<td>" . $phone . "</td>" . "<td>" . $row['title'] . "</td>";
        echo "</tr>";

    }
} else {
    echo "<tr style='text-align:center'>";
    echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";


echo $msg;  //will print the content in the exel page
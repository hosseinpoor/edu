<?php

include("download.php");
download("دانشجویان");
$msg = "";

$sql = "SELECT * FROM (students LEFT JOIN orders ON students.email = orders.studentMail) INNER JOIN course ON course.courseId = orders.courseId WHERE active = 1 AND status = 1 ORDER BY students.family";
$result = mysqli_query($db, $sql);
echo "<table border='1'>";
echo "<thead> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تماس</th> <th>دروس ثبت نامی</th> </tr> </thead>";
echo "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["name"] . " " . $row["family"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["phoneNum"] . "</td>" . "<td>" . $row['title'] . "</td>";
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
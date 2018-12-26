<?php

include("download.php");
download("اساتید");
$msg = "";

$sql = "SELECT * FROM (teachers LEFT JOIN course ON teachers.email = course.teacherMail) ORDER BY teachers.family";
$result = mysqli_query($db, $sql);
echo "<table border='1'>";
echo "<thead> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تماس</th> <th>دروس تدریسی</th> </tr> </thead>";
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
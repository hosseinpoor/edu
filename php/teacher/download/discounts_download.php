<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/16/2018
 * Time: 11:05 AM
 */

include("download.php");
download("تخفیف");
$msg = "";

$sql = "SELECT courseId from course WHERE teacherMail = '".$_SESSION['temail']."'";
$result = mysqli_query($db, $sql);
while($row = mysqli_fetch_assoc($result)){
    $courses[] = $row['courseId'];
}

$sql = "SELECT * FROM discount WHERE courseId IN (".implode(",",$courses).") ORDER BY discountId DESC";
$result = mysqli_query($db, $sql);
$msg .=  "<table border='1'>";
$msg .= "<thead > <tr> <th>درس</th> <th>نوع</th> <th>مقدار</th> </tr> </thead>";
$msg .= "<tbody>";
if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
        $s = "SELECT title FROM course WHERE courseId = " . $row["courseId"];
        $r = mysqli_query($db, $s);
        $crow = mysqli_fetch_assoc($r);
        $msg .= "<tr>";
        if ($row['isRial'])
            $msg .= "<td>" . $crow["title"] . "</td>" . "<td>" . $row["type"] . "</td>" . "<td>" . $row["amount"] . " ریال</td>";
        else
            $msg .= "<td>" . $crow["title"] . "</td>" . "<td>" . $row["type"] . "</td>" . "<td>" . $row["amount"] . " %</td>";
        $msg .= "</tr>";
    }
} else {
    $msg .= "<tr>";
    $msg .= "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    $msg .= "</tr>";
}
$msg .= "</tbody>";
$msg .= "</table>";


echo $msg;  //will print the content in the exel page

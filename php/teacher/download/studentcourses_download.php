<?php

include("download.php");

$fileName = "دروس ";
if (isset($_GET['mail']) && !empty($_GET['mail'])) {

    mysqli_query($db, "SET NAMES utf8");
    $sql = "SELECT family FROM students WHERE email = '" . $_GET['mail'] . "'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fileName .= $row['family'];
    } else {
        header("location:teachers.php");
    }
} else {
    header("location:teachers.php");
}

download($fileName);
$msg = "";

function getPay($id, $db, $cost)
{
    if ($id != Null) {
        $sql = "SELECT * FROM discount WHERE discountId = " . $id;
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['discountId'] != Null) {
                if ($row['isRial'] == 1) {
                    return $cost - $row['amount'];
                } else {
                    return $cost - ($row['amount'] * $cost) / 100;
                }

            } else {
                return $cost;
            }
        }
    }
    return $cost;
}

$sql = "SELECT title, holdingDays, cost, course.courseId , discountId FROM orders INNER JOIN course WHERE studentMail = '" . $_GET['mail'] . "' AND course.teacherMail = '".$_SESSION['temail']."' AND status = 1 AND active = 1 AND course.courseId = orders.courseId ORDER BY course.courseId DESC";
$result = mysqli_query($db, $sql);
$msg .= "<table border='1'>";
$msg .= "<thead> <tr> <th>عنوان</th> <th >روز های برگزاری</th> <th>هزینه کلاس</th> <th>مبلغ پرداختی</th> </tr> </thead>";
$msg .= "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $msg .= "<tr>";
        $msg .= "<td>" . $row["title"] . "</td>" . "<td>" . $row["holdingDays"] . "</td>" . "<td>" . $row["cost"] . "</td>" ."<td>" . getPay($row['discountId'], $db, $row['cost']) . "</td>";
        $msg .= "</tr>";
    }
} else {
    $msg .= "<tr class='text-center'>";
    $msg .= "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    $msg .= "</tr>";
}
$msg .= "</tbody>";
$msg .= "</table>";

echo $msg;  //will print the content in the exel page
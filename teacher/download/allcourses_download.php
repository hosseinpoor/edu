<?php
//download.php page code
//THIS PROGRAM WILL FETCH THE RESULT OF SQL QUERY AND WILL DOWNLOAD IT. (IF YOU HAVE ANY QUERY CONTACT:rahulpatel541@gmail.com)

include("download.php");
download("دروس");

function getSubmitCount($id, $db)
{
    $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 1";
    $result = mysqli_query($db, $sql);
    return mysqli_num_rows($result);
}

function getReservedCount($id, $db)
{
    $sql = "SELECT * FROM orders WHERE courseId = " . $id . " AND active = 1 AND status = 3";
    $result = mysqli_query($db, $sql);
    return mysqli_num_rows($result);
}

function getTotalPay($id, $db)
{
    $total = 0;
    $sql = "SELECT cost , discountId FROM orders INNER JOIN course WHERE orders.courseId = " . $id . " AND course.courseId = orders.courseId AND active = 1 AND (status = 1 OR status = 3) AND verify = 1";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['discountId'] != Null) {
            $s = "SELECT * FROM discount WHERE discountId = " . $row['discountId'];
            $r = mysqli_query($db, $s);
            $count = mysqli_num_rows($r);
            if ($count == 1) {
                $res = mysqli_fetch_assoc($r);
                if ($res['isRial'] == 1) {
                    $total += $row['cost'] - $res['amount'];
                } else {
                    $total += $row['cost'] - ($res['amount'] * $row['cost']) / 100;
                }
            }
        } else {
            $total += $row['cost'];
        }
    }
    return $total;
}


$msg = "";

$sql = "SELECT title, holdingDays, cost, courseId , name , family FROM course INNER JOIN teachers WHERE teacherMail = email AND email = '".$_SESSION['temail']."' ORDER BY courseId DESC";
$result = mysqli_query($db, $sql);
$msg .= "<table border='1'>";
$msg .= "<thead> <tr> <th>عنوان</th> <th>استاد</th> <th>روز های برگزاری</th> <th>هزینه</th> <th>تعداد ثبت نامی</th> <th>تعداد رزرو</th> <th>مبلغ کل ثبت نام</th> </tr> </thead>";
$msg .= "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $msg .= "<tr>";
        $msg .= "<td>" . $row["title"] . "</td>" . "<td>" . $row['name'] . " " . $row['family'] . "</td>" . "<td>" . $row["holdingDays"] . "</td>" . "<td>" . $row["cost"] . "</td>" . "<td>" . getSubmitCount($row['courseId'], $db) . "</td>" . "<td>" . getReservedCount($row['courseId'], $db) . "</td>" . "<td>" . getTotalPay($row['courseId'], $db) . "</td>";
        $msg .= "</tr>";
    }
} else {
    $msg .= "<tr style='text-align:center'>";
    $msg .= "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>". "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    $msg .= "</tr>";
}
$msg .= "</tbody>";
$msg .= "</table>";

echo $msg;  //will print the content in the exel page


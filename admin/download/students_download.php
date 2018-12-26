<?php

include("download.php");

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

mysqli_query($db, "SET NAMES utf8");

$fileName = "";
$cost = 0;
$sql = "SELECT title , cost FROM course WHERE courseId = " . $_GET['id'];
$result = mysqli_query($db, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $cost = $row['cost'];
    $fileName = $row['title'];
}
download($fileName);
$msg = "";

$sql = "SELECT studentMail , discountId  FROM orders WHERE status = 1 AND active = 1 AND courseId = " . $_GET['id'] . " ORDER BY orderId DESC ";
$result = mysqli_query($db, $sql);
$msg .= "<table border='1'>";
$msg .= "<thead> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تلفن همراه</th> <th>مبلغ پرداختی</th> </tr> </thead>";
$msg .= "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $s = "SELECT name, family, email , phoneNum FROM students WHERE email = '" . $row["studentMail"] . "'";
        $r = mysqli_query($db, $s);
        while ($crow = mysqli_fetch_assoc($r)) {
            $msg .= "<tr>";
            $msg .= "<td>" . $crow["name"] . " " . $crow["family"] . "</td>" . "<td>" . $crow["email"] . "</td>" . "<td>" . $crow["phoneNum"] . "</td>" . "<td>" . getPay($row['discountId'], $db, $cost) . "</td>";
            $msg .= "</tr>";
        }
    }

} else {
    $msg .= "<tr>";
    $msg .= "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    $msg .= "</tr>";
}
$msg .= "</tbody>";
$msg .= "</table>";

echo $msg;  //will print the content in the exel page
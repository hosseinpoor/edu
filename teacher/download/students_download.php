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

$sql = "SELECT * FROM conf";
$result = mysqli_query($db, $sql);
$auth = mysqli_fetch_assoc($result);

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

$sql = "SELECT studentMail , discountId , verify FROM orders WHERE (status = 1 OR status = 3) AND active = 1 AND courseId = " . $_GET['id'] . " ORDER BY orderId DESC ";
$result = mysqli_query($db, $sql);
$msg .= "<table border='1'>";
$msg .= "<thead> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تلفن همراه</th> <th>مبلغ پرداختی</th> <th>نوع تخفیف</th><th>وضعیت پرداخت</th> </tr> </thead>";
$msg .= "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $s = "SELECT name, family, email , phoneNum FROM students WHERE email = '" . $row["studentMail"] . "'";
        $r = mysqli_query($db, $s);
        while ($crow = mysqli_fetch_assoc($r)) {
            $email = ($auth['emailAuth'] == 1) ? $crow['email'] : "شما مجاز به مشاهده این بخش نیستید";
            $phone = ($auth['phoneAuth'] == 1) ? $crow['phoneNum'] : "شما مجاز به مشاهده این بخش نیستید";
            $msg .= "<tr>";
            $msg .= "<td>" . $crow["name"] . " " . $crow["family"] . "</td>" . "<td>" . $email . "</td>" . "<td>" . $phone . "</td>" . "<td>" . getPay($row['discountId'], $db, $cost) . "</td><td>";
            if ($row['discountId']) {
                $s = "SELECT * FROM discount WHERE discountId = " . $row['discountId'];
                $r = mysqli_query($db, $s);
                $a = mysqli_fetch_assoc($r);
                if ($a['needFile']) {
                    $msg .= 'فایل';
                } else {
                    $sq = "SELECT name , family FROM teachers WHERE email = '" . $a['code'] . "'";
                    $res = mysqli_query($db, $sq);
                    if (mysqli_num_rows($res) == 1) {
                        $ans = mysqli_fetch_assoc($res);
                        $msg .= 'معرف: ' . $ans['name'] . " " . $ans['family'];
                    } else if ($a['code'] == 'ALLUSERS') {
                        $msg .= "معرف: همه";
                    } else {
                        $msg .= 'کد: ' . $a['code'];
                    }


                }
            } else
                $msg .= "بدون تخفیف";
            $msg .= "</td>";

            if ($row['verify'])
                $msg .= "<td>تایید شده</td>";
            else
                $msg .=  "<td>تایید نشده</td>";
            $msg .= "</tr>";
        }
    }

} else {
    $msg .= "<tr>";
    $msg .= "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    $msg .= "</tr>";
}
$msg .= "</tbody>";
$msg .= "</table>";

echo $msg;  //will print the content in the exel page
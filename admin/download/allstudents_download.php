<?php

include("download.php");
download("دانشجویان");
$msg = "";

$sql = "SELECT * FROM (students LEFT JOIN orders ON students.email = orders.studentMail) INNER JOIN course ON course.courseId = orders.courseId WHERE active = 1 AND (status = 1 OR status = 3) ORDER BY students.family";
$result = mysqli_query($db, $sql);
echo "<table border='1'>";
echo "<thead> <tr> <th>نام و نام خانوادگی</th> <th>رایانامه</th> <th>شماره تماس</th> <th>دروس ثبت نامی</th> <th>نوع تخفیف</th> </tr> </thead>";
echo "<tbody>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["name"] . " " . $row["family"] . "</td>" . "<td>" . $row["email"] . "</td>" . "<td>" . $row["phoneNum"] . "</td>" . "<td>" . $row['title'] . "</td><td>";
        if($row['discountId']){
            $s = "SELECT * FROM discount WHERE discountId = ".$row['discountId'];
            $r = mysqli_query($db , $s);
            $a = mysqli_fetch_assoc($r);
            if($a['needFile']){
                echo 'فایل' ;
            }
            else{
                $sq = "SELECT name , family FROM teachers WHERE email = '".$a['code']."'";
                $res = mysqli_query($db , $sq);
                if(mysqli_num_rows($res) == 1){
                    $ans = mysqli_fetch_assoc($res);
                    echo 'معرف: ' . $ans['name'] . " " . $ans['family'];
                }
                else{
                    echo 'کد: ' . $a['code'];
                }


            }
        }
        else
            echo "بدون تخفیف";
        echo "</td></tr>";

    }
} else {
    echo "<tr style='text-align:center'>";
    echo "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>". "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>" . "<td>" . "سطری جهت نمایش وجود ندارد" . "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";


echo $msg;  //will print the content in the exel page
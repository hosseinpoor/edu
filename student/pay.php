<?php

function jalali_to_gregorian($jy, $jm, $jd, $mod = '')
{
    if ($jy > 979) {
        $gy = 1600;
        $jy -= 979;
    } else {
        $gy = 621;
    }
    $days = (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
    $gy += 400 * ((int)($days / 146097));
    $days %= 146097;
    if ($days > 36524) {
        $gy += 100 * ((int)(--$days / 36524));
        $days %= 36524;
        if ($days >= 365) $days++;
    }
    $gy += 4 * ((int)($days / 1461));
    $days %= 1461;
    if ($days > 365) {
        $gy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $gd = $days + 1;
    foreach (array(0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {
        if ($gd <= $v) break;
        $gd -= $v;
    }
    return ($mod == '') ? array($gy, $gm, $gd) : $gy . $mod . $gm . $mod . $gd;
}

if (isset($_POST['code']) && !empty($_POST['code']) && isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['cost']) && !empty($_POST['cost'])) {

    $db = @mysqli_connect("localhost", "root", "", "ebbroker");
    if (!mysqli_connect_error()) {
        $sql = "SELECT * FROM discount WHERE code = '" . $_POST['code'] . "' AND courseId = " . $_POST['id'];
        $result = mysqli_query($db, $sql);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $res = mysqli_fetch_assoc($result);
            $start = $res['startDate'];
            $startY = substr($start, 0, 4);
            $startM = substr($start, 5, 2);
            $startD = substr($start, 8, 2);
            $startG = jalali_to_gregorian($startY, $startM, $startD, "-");
            $end = $res['endDate'];
            $endY = substr($end, 0, 4);
            $endM = substr($end, 5, 2);
            $endD = substr($end, 8, 2);
            $endG = jalali_to_gregorian($endY, $endM, $endD, "-");
            $today = date("Y-m-d");

            if ($res['isRial'] == 1) {
                if ($today >= $startG && $today <= $endG)
                    echo $_POST['cost'] - $res['amount'];
                else echo "discount has not started yet or is ended";
            } else {
                if ($today >= $startG && $today <= $endG)
                    echo $_POST['cost'] - ($res['amount'] * $_POST['cost']) / 100;
                else echo "discount has not started yet or is ended";
            }

        } else
            echo "error in finding discount";
    } else {
        echo "error in connecting to database";
    }
} else {
    header("location:../login.php");
}

?>





<div class="sidebar">
    <img src="../../img/logo.jpg" alt="logo">

    <?php

    //    preg_match('/books$/', $variable)
    function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

    $db = @mysqli_connect("localhost", "root", "", "ebbroker");
    if (!mysqli_connect_error()) {
        mysqli_query($db, "SET NAMES utf8");
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $sql = "SELECT * FROM adminmenu ORDER BY priority , id";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            if (strpos($actual_link, $row['link']))
                echo '<a href="' . $row["link"] . '" class = "active">' .'<i style="width:20px;text-align:center" class = "' . $row['style'] . '"></i> '. $row["title"] . '</a>';
            else
                echo '<a href="' . $row["link"] . '" >' .'<i style="width:20px;text-align:center" class = "' . $row['style'] . '"></i> '. $row["title"] . '</a>';
        }
    } else {
        echo "<script>
                alert('error in connecting to DB. please try again later');
                window.location.href='../login.php';
                </script>";
    } ?>

</div>

<div style="position: absolute; left: 20px ; top: 20px;">
    <?php
    function gregorian_to_jalali($gy,$gm,$gd,$mod=''){
        $g_d_m=array(0,31,59,90,120,151,181,212,243,273,304,334);
        if($gy>1600){
            $jy=979;
            $gy-=1600;
        }else{
            $jy=0;
            $gy-=621;
        }
        $gy2=($gm>2)?($gy+1):$gy;
        $days=(365*$gy) +((int)(($gy2+3)/4)) -((int)(($gy2+99)/100)) +((int)(($gy2+399)/400)) -80 +$gd +$g_d_m[$gm-1];
        $jy+=33*((int)($days/12053));
        $days%=12053;
        $jy+=4*((int)($days/1461));
        $days%=1461;
        if($days > 365){
            $jy+=(int)(($days-1)/365);
            $days=($days-1)%365;
        }
        $jm=($days < 186)?1+(int)($days/31):7+(int)(($days-186)/30);
        $jd=1+(($days < 186)?($days%31):(($days-186)%30));
        return($mod=='')?array($jy,$jm,$jd):$jy.$mod.$jm.$mod.$jd;
    }

    echo '<span style="line-height: 50px">'.gregorian_to_jalali(date("Y"),date("m"),date("d"),"/").'</span>'; ?>
    <a href="logout.php"> <img src="../../img/logout.png" alt="خروج" style="width: 30px;height: 30px"> </a>
</div>



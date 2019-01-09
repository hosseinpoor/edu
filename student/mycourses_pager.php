<?php
function createPager($sql, $db, $counter = 0)
{
    if ($counter == 0) {
        $result = mysqli_query($db, $sql);
        $count = mysqli_num_rows($result);
    } else {
        $count = $counter;
    }
    if ($count > 9) {
        $page = 1;
        if (isset($_GET['page']) && !empty($_GET['page']))
            $page = intval($_GET['page']);
        ?>

        <a class="fa fa-angle-double-left"
           href="<?php
           $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
           $string = "?";
           $pos = strpos($url, '?');
           $url = strstr($url, 0, $pos - 1);
           $last = (ceil($count / 9));
           echo $url . $string . "page=" . $last;
           ?>"></a>

        <a class="fa fa-angle-left"
           href="<?php
           $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
           $pos = strpos($url, '?');
           $url = strstr($url, 0, $pos - 1);
           if ($page * 9 < $count) echo $url . $string . 'page=' . ($page + 1);
           else echo $url . $string . "page=" . $page;
           ?>"></a>

        <a class="fa fa-angle-right" href="<?php
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $parsed = parse_url($url);
        $query = $parsed['query'];
        parse_str($query, $params);
        unset($params['page']);
        $string = http_build_query($params);
        if ($string == "")
            $string = "?";
        else
            $string = "?" . $string . "&";

        $pos = strpos($url, '?');
        $url = strstr($url, 0, $pos - 1);
        if ($page > 1) echo $url . $string . "page=" . ($page - 1);
        else echo $url . $string . "page=1"
        ?>"></a>

        <a class="fa fa-angle-double-right" href="<?php
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $parsed = parse_url($url);
        $query = $parsed['query'];
        parse_str($query, $params);
        unset($params['page']);
        $string = http_build_query($params);
        if ($string == "")
            $string = "?";
        else
            $string = "?" . $string . "&";

        $pos = strpos($url, '?');
        $url = strstr($url, 0, $pos - 1);
        echo $url . $string . "page=1"
        ?>"></a>

        <?php
    }
}

?>

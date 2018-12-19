<?php
function createPager($sql, $db)
{
    $result = mysqli_query($db, $sql);
    $count = mysqli_num_rows($result);
    if ($count > 10) {
        $page = 1;
        if (isset($_GET['page']) && !empty($_GET['page']))
            $page = intval($_GET['page']);
        ?>

        <div style="text-align: center">
            <div class="pagination">

                <a class="fa fa-angle-double-left" href="<?php
                $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $parsed = parse_url($url);
                $query = $parsed['query'];
                parse_str($query, $params);
                unset($params['page']);
                $string = http_build_query($params);
                if($string == "")
                    $string = "?";
                else
                    $string = "?".$string."&";

                $pos = strpos($url, '?');
                $url = strstr($url, 0, $pos - 1);
                echo $url .$string. "page=1"
                ?>"></a>

                <a class="fa fa-angle-left" href="<?php
                $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $parsed = parse_url($url);
                $query = $parsed['query'];
                parse_str($query, $params);
                unset($params['page']);
                $string = http_build_query($params);
                if($string == "")
                    $string = "?";
                else
                    $string = "?".$string."&";

                $pos = strpos($url, '?');
                $url = strstr($url, 0, $pos - 1);
                if ($page > 1) echo $url .$string. "page=" . ($page - 1);
                else echo $url .$string. "page=1"
                ?>"></a>

                <?php

                $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $pos = strpos($url, '?');
                $url = strstr($url, 0, $pos - 1);

                $pageNum = $page - 5;
                for ($x = $pageNum; $x < $pageNum + 5; $x++) {
                    if ($x > 0)
                        echo '<a href="' . $url .$string. 'page=' . $x . '">' . $x . '</a>';
                }

                echo '<a class="active" href="' . $url.$string."page=$page" . '">' . $page . '</a>';

                for ($x = $page + 1; $x < $page + 6; $x++) {
                    if ((($x - 1) * 10) < $count)
                        echo '<a href="' . $url .$string. 'page=' . $x . '">' . $x . '</a>';
                }
                ?>

                <a class="fa fa-angle-right"
                   href="<?php
                   $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                   $pos = strpos($url, '?');
                   $url = strstr($url, 0, $pos - 1);
                   if ($page * 10 < $count) echo $url .$string. 'page=' . ($page + 1);
                   else echo $url .$string. "page=" . $page;
                   ?>"></a>

                <a class="fa fa-angle-double-right"
                   href="<?php
                   $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                   $pos = strpos($url, '?');
                   $url = strstr($url, 0, $pos - 1);
                   $last = (round($count/10))+1;
                   echo $url .$string. "page=" . $last;
                   ?>"></a>
            </div>
        </div>

        <?php
    }
}

?>

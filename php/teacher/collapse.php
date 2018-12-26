<?php
session_start();

if (isset($_POST['col']) && !empty($_POST['col'])) {
    $_SESSION['isCollapse'] = $_POST['col'];
} else {
    if (!isset($_SESSION['isCollapse']) || $_SESSION['isCollapse'] === 'false')
        $_SESSION['isCollapse'] = 'true';
    else
        $_SESSION['isCollapse'] = 'false';
}

?>
<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/11/2018
 * Time: 5:25 PM
 */
session_start();
session_destroy();
header("location:../login.php");

<?php
session_start();

include('../config.php');

$phpUrl = $coreIp.$coreRoot.$coreFolder;
$srcUrl = $coreIp.$corePort.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

unset($_SESSION[$appName]);
$_SESSION['message'] = "You've been sucessfully Log Out.";
header('Location:../index.php');

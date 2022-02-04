<?php
session_start();
include('../../config.php');

$phpUrl = '../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

//print_r($_POST);
$UserId = $_POST["UserId"];
$Password = md5($_POST['Password']);

$configs = getTable(array(
    "database" => "",
    "tableName" => "DIM_Config",
    "filtersIn" => array(
        "Id" => array(1)//Default Password
    )
));
$defaultPassword = $configs[1]["Value"];
$encryptedDefaultPassword = md5($defaultPassword);

$isValid = false;
$message = "";
if($Password != $encryptedDefaultPassword)
{
    $connect_sql_server = connect_sql_server('uranus');

    $query = "UPDATE [Uranus].[dbo].[FACT_User] SET [Password] = '".$Password."' WHERE [Id] = '".$UserId."'";
    sqlsrv_query($connect_sql_server, $query);
    $isValid = true;
    $_SESSION['isDefaultPassword'] = 0;
}
else
    $message = "Sorry, you are still using '".$defaultPassword."' as your new password, please try something else";

if(count(error_get_last()))
{
    $result = array(
        'iserror' => true,
        'warning' => error_get_last()
    );
}
else
{
    $result = array(
        'iserror' => false,
        'isValid' => $isValid,
        'message' => $message
    );
}
echo json_encode($result);

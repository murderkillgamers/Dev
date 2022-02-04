<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$Name = protect($_POST["Name"],1);
$Order = $_POST["Order"];
$DepartmentId = $_POST["DepartmentId"];

$connect_sqlsrv = connect_sql_server("gaia");

//CEK SUBJECT DENGAN ORDER YANG SAMA
$query = "SELECT COUNT(*)[Check]
    FROM [DIM_UniversalFileGroup]
    WHERE [DepartmentId] = ".$DepartmentId."
    AND ([Name] = '".$Name."' OR [Order] = ".$Order.");";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$Check  = $row["Check"];

if($Check) $datas["message"] = "Add file Error.\nName already exist.\nPlease use different Name Or Order.";

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
        'data' => $data,
        'datas' => $datas
    );
}
echo json_encode($result);

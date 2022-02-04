<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$GroupId = $_POST["GroupId"];

$connect_sqlsrv = connect_sql_server("gaia");

//ACTUAL DATA
$query = "SELECT MAX([Order])[MaxOrder]
                FROM [FACT_UniversalFile]
                WHERE [GroupId] = ".$GroupId.";";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$LastOrder = $row["MaxOrder"];
$NewOrder = $LastOrder + 1;
$data = $NewOrder;

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

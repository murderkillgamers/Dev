<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$DepartmentId = $_POST["DepartmentId"];

$connect_sqlsrv = connect_sql_server("gaia");

$query = "SELECT [Id][Value],
            [Name][Text],
            [Order]
    FROM [Gaia].[dbo].[DIM_UniversalFileGroup]
    WHERE [DepartmentId] = ".$DepartmentId."
    ORDER BY [Name] ASC";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    $datas[] = $row;
}

$query = "SELECT MAX([Order])[MaxOrder]
                    FROM [DIM_UniversalFileGroup]
                    WHERE [DepartmentId] = ".$DepartmentId.";";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$LastOrder = $row["MaxOrder"] + 1;
$data = $LastOrder;

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

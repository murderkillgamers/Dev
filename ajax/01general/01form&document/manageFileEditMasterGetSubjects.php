<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$UniversalFileGroupId = $_POST["UniversalFileGroupId"];

$DepartmentId = $_POST["DepartmentId"];

$connect_sqlsrv = connect_sql_server("gaia");
$query = "  SELECT [Id][Id],[Name][Subject],[Order],[IsEnable]
            FROM [DIM_UniversalFileGroup]
            WHERE [DepartmentId] = ".$DepartmentId."
            AND [Id] = ".$UniversalFileGroupId."
            ORDER BY [DepartmentId] ASC";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    $data = $row;
}

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

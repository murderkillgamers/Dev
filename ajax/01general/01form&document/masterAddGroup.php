<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$CreatedByUserId = $_POST["CreatedByUserId"];
$Name = protect($_POST["Name"],1);
$Order = $_POST["Order"];
$DepartmentId = $_POST["DepartmentId"];

$connect_sqlsrv = connect_sql_server("gaia");

$query = "INSERT INTO [DIM_UniversalFileGroup]
                    ([DepartmentId]
                    ,[Name]
                    ,[Order]
                    ,[CreatedByUserId])
                    VALUES
                    (".$DepartmentId."
                    ,'".$Name."'
                    ,".$Order."
                    ,".$CreatedByUserId.")";

sqlsrv_query($connect_sqlsrv, $query);

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

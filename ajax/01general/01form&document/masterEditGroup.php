<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$Id = $_POST["Id"];
$Name = protect($_POST["Name"],1);
$Order = $_POST["Order"];
$IsEnable = $_POST["IsEnable"];
$EditedByUserId = $_POST["EditedByUserId"];

$connect_sqlsrv = connect_sql_server("gaia");

$query = "UPDATE [DIM_UniversalFileGroup]
            SET [Name] = '".$Name."'
                ,[Order] = ".$Order."
                ,[IsEnable] = ".$IsEnable."
                ,[EditedByUserId] = ".$EditedByUserId."
                ,[EditedDateTime] = GETDATE()
                WHERE [Id] = ".$Id.";";

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

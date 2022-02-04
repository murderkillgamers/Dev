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
$EditedByUserId = $_POST["EditedByUserId"];
$GroupId = $_POST["GroupId"];
$Order = $_POST["Order"];
$Title = protect($_POST["Title"],1);
$Description = protect($_POST["Description"],1);
$IsEnable = $_POST["IsEnable"];

$connect_sqlsrv = connect_sql_server("gaia");

//CEK DEPARTMENT
$query = "SELECT [DepartmentId] FROM [DIM_UniversalFileGroup] WHERE [Id] = ".$GroupId.";";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$DepartmentId = $row["DepartmentId"];

//CEK TITLE DENGAN DEPARTMENT
$checkTitle = 0;
$query = "SELECT COUNT(*)[Check]
    FROM [FACT_UniversalFile][File]
    JOIN [DIM_UniversalFileGroup][Group]
        ON [Group].[Id] = [File].[GroupId]
    WHERE [Group].[DepartmentId] = ".$DepartmentId."
    AND [File].[Title] = '".$Title."'
    AND [File].[Id] != ".$Id."";
// echo $query;
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
sqlsrv_query($connect_sqlsrv, $query);
$checkTitle = $row["Check"];

//CEK ORDER DENGAN GROUP YANG SAMA
$checkOrder = 0;
$query = "SELECT COUNT(*)[Check]
    FROM [FACT_UniversalFile][File]
    WHERE [File].[GroupId] = ".$GroupId."
    AND [File].[Order] = ".$Order."
    AND [File].[Id] != ".$Id."";

// echo $query;
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
sqlsrv_query($connect_sqlsrv, $query);
$checkOrder = $row["Check"];

if($checkTitle)$datas["message"] = "Edit file Error.\nTitle already exist.\nPlease use different title.";
else if($checkOrder) $datas["message"] = "Edit file Error.\nThe order already exist.\nPlease use different order value.";

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

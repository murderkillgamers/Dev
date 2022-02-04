<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();
$datas["message"] = "";

$CreatedByUserId = $_POST["CreatedByUserId"];
$GroupId = $_POST["GroupId"];
$Order = $_POST["Order"];
$Title = protect($_POST["Title"],1);
$Description = protect($_POST["Description"],1);
$IsEnable = 1;

$connect_sqlsrv = connect_sql_server("gaia");

//CEK DEPARTMENT
$query = "SELECT [DepartmentId] FROM [DIM_UniversalFileGroup] WHERE [Id] = ".$GroupId.";";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$DepartmentId = $row["DepartmentId"];

//CEK TITLE DENGAN DEPARTMENT
$query = "SELECT [File].[Title]
    FROM [FACT_UniversalFile][File]
    JOIN [DIM_UniversalFileGroup][Group]
        ON [Group].[Id] = [File].[GroupId]
    WHERE [Group].[DepartmentId] = ".$DepartmentId."
    AND [File].[Title] = '".$Title."'";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
sqlsrv_query($connect_sqlsrv, $query);
$checkTitle = $row;

//CEK ORDER DENGAN GROUP YANG SAMA
$query = "SELECT [File].[GroupId]
    FROM [FACT_UniversalFile][File]
    JOIN [DIM_UniversalFileGroup][Group]
        ON [Group].[Id] = [File].[GroupId]
    WHERE [Group].[DepartmentId] = ".$DepartmentId."
    AND [File].[GroupId] = '".$GroupId."'
    AND [File].[Order] = ".$Order."";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
sqlsrv_query($connect_sqlsrv, $query);
$checkOrder = $row;

if($checkTitle["Title"] && $checkOrder["GroupId"]) $datas["message"] = "Add new file Error.\nThe order & title already exist.\nPlease use different order & title value.";
else{
    if($checkTitle["Title"]) $datas["message"] = "Add new file Error.\nThe title already exist.\nPlease use different title.";
    elseif($checkOrder["GroupId"]) $datas["message"] = "Add new file Error.\nThe order already exist.\nPlease use different order value.";
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

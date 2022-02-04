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
$GroupId = $_POST["GroupId"];
$Order = $_POST["Order"];
$Title = protect($_POST["Title"],1);
$Description = protect($_POST["Description"],1);
$FolderPath = $_POST["FolderPath"];
$FileName = $_POST["FileName"];
$IsEnable = 1;

$connect_sqlsrv = connect_sql_server("gaia");

$query = "INSERT INTO [FACT_UniversalFile]
                    ([GroupId]
                   ,[FolderPath]
                   ,[FileName]
                   ,[Title]
                   ,[Description]
                   ,[Order]
                   ,[CreatedByUserId])
            OUTPUT [Inserted].[Id]
            VALUES
                    (".$GroupId."
                   ,'".$FolderPath."'
                   ,'".$FileName."'
                   ,'".$Title."'
                   ,'".$Description."'
                   ,".$Order."
                   ,".$CreatedByUserId.")";
            $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            $UniversalFileId = $row["Id"];

$query = "INSERT INTO [Gaia].[dbo].[LOG_UniversalFile]
                    ([UniversalFileId],[FolderPath],[FileName])
            VALUES
                (".$row["Id"].",'".$FolderPath."','".$FileName."');";
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

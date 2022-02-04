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
$GroupId = $_POST["GroupId"]; //Department
$Order = $_POST["Order"];
$Title = protect($_POST["Title"],1);
$Description = protect($_POST["Description"],1);
$FileName = $_POST["FileName"];
$FolderPath = $_POST["FolderPath"];
$IsEnable = $_POST["IsEnable"];

$connect_sqlsrv = connect_sql_server("gaia");
$query = "UPDATE [FACT_UniversalFile]
            SET [GroupId] = ".$GroupId."
                ,[Title] = '".$Title."'
                ,[Description] = '".$Description."'
                ,[Order] = ".$Order."
                ,[IsEnable] = ".$IsEnable."
                ,[EditedByUserId] = ".$EditedByUserId."
                ,[EditedDateTime] = GETDATE()";
    if($FileName) $query .=",[FileName] = '".$FileName."'";
    $query .=" OUTPUT inserted.[Id]";
    $query .=" WHERE [Id] = ".$Id.";";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $UniversalFileId = $row["Id"];

    if($FileName && $UniversalFileId){
$query = "INSERT INTO [Gaia].[dbo].[LOG_UniversalFile]
                    ([UniversalFileId],[FolderPath],[FileName])
          VALUES (".$UniversalFileId.",'".$FolderPath."','".$FileName."');";
            sqlsrv_query($connect_sqlsrv, $query);
        }

if(count(error_get_last()))
{
    $result = array(
    'iserror' => true,
    'warning' => error_get_last(),
    'data' => $data,
    'datas' => $datas
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

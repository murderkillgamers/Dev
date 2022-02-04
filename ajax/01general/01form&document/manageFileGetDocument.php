<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();
$datas["Subjects"] = array();

$Id = $_POST["Id"];
$DepartmentId = $_POST["DepartmentId"];

$connect_sqlsrv = connect_sql_server("gaia");

//ACTUAL DATA
$query = "SELECT *
    FROM [FACT_UniversalFile]
    WHERE [Id] = ".$Id.";";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$data = $row;
$FileName = $data["FileName"];
$FileNameExplodes = explode(".",$FileName);

//$data["FileNameExplodes"] = $FileNameExplodes;
$Count = count($FileNameExplodes);

//$data["Count"] = $Count;
$Extension = $FileNameExplodes[$Count-1];

//$data["Extension"] = $Extension;
if($Extension == "xlsx" || $Extension == "xls" || $Extension == "csv")
{
    $data["Icon"] = "fa fa-file-excel-o";
}
else if($Extension == "docx" || $Extension == "doc")
{
    $data["Icon"] = "fa fa-file-word-o";
}
else if($Extension == "ppt" || $Extension == "pptx")
{
    $data["Icon"] = "fa fa-file-powerpoint-o";
}
else if($Extension == "pdf")
{
    $data["Icon"] = "fa fa-file-pdf-o";
}
else if($Extension == "jpg" || $Extension == "jpeg" || $Extension == "gif" || $Extension == "bmp" || $Extension == "png")
{
    $data["Icon"] = "fa fa-file-image-o";
}
else if($Extension == "wmv" || $Extension == "avi" || $Extension == "mpg" || $Extension == "3gp" || $Extension == "mp4" || $Extension == "mkv")
{
    $data["Icon"] = "fa fa-file-video-o";
}
else if($Extension == "wma" || $Extension == "mp3" || $Extension == "wav")
{
    $data["Icon"] = "fa fa-file-audio-o";
}
else if($Extension == "zip" || $Extension == "7zip" || $Extension == "rar")
{
    $data["Icon"] = "fa fa-file-archive-o";
}
else
{
    $data["Icon"] = "fa fa-file-o";
}

//DIM SUBJECT
$query = "SELECT [Id][Value],[Name][Text]
    FROM [DIM_UniversalFileGroup]
    WHERE [DepartmentId] = ".$DepartmentId."
    ORDER BY [Name] ASC";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    $datas["Subjects"][] = $row;
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

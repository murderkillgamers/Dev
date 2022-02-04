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
$GroupId = $_POST["GroupId"];
$FileName = $_POST["FileName"];

$connect_sqlsrv = connect_sql_server("gaia");//
$query = " SELECT
                [File].[Id]
                ,[Group].[Name][GroupName]
                ,[File].[FolderPath]
                ,[File].[FileName]
                ,[File].[Title]
                ,[File].[Description]
                ,[File].[Order]
                ,[File].[IsEnable]
                ,[Group].[IsEnable][GroupIsEnable]
            FROM [FACT_UniversalFile][File]
            JOIN [DIM_UniversalFileGroup][Group]
                ON [File].[GroupId] = [Group].[Id]
            WHERE [Group].[DepartmentId] = ".$DepartmentId." ";
            if($GroupId != 0) $query = $query . " AND [File].[GroupId] = ".$GroupId;
            if($FileName != "") $query = $query . " AND [File].[Title] LIKE '%".$FileName."%'";

$query = $query . " ORDER BY [Group].[Name],[File].[Order],[File].[FileName] ASC;";

$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    //icon file document
    $IconFA = "far fa-file";
    //20190722_135921_hrd.xlsx
    $FileNameExplode = explode(".",$row["FileName"]);//[0] = 20190722_135921_hrd ; [1] = xlsx
    $FileNameCount = count($FileNameExplode);//2
    $ExtensionOrder = $FileNameCount - 1;//1

    $Extension = $FileNameExplode[$ExtensionOrder];
    $Extension = strtolower($Extension);

    if($Extension == "txt")$IconFA = "far file-alt toska";
    else if($Extension == "xls" || $Extension == "xlsx")$IconFA = "far fa-file-excel retro_green";
    else if($Extension == "csv")$IconFA = "fas fa-file-csv retro_green";
    else if($Extension == "doc" || $Extension == "docx")$IconFA = "far fa-file-word blue";
    else if($Extension == "ppt" || $Extension == "pptx")$IconFA = "far fa-file-powerpoint retro_orange";
    else if($Extension == "pdf")$IconFA = "fas fa-file-pdf retro_red";
    else if($Extension == "jpg" || $Extension == "jpeg" || $Extension == "gif" || $Extension == "png" || $Extension == "bmp")$IconFA = "far fa-file-image magenta";
    else if($Extension == "wmv" || $Extension == "avi" || $Extension == "mpeg" || $Extension == "3gp" || $Extension == "mp4" || $Extension == "mkv")$IconFA = "far fa-file-video retro_blue";
    else if($Extension == "wma" || $Extension == "mp3" || $Extension == "wav")$IconFA = "far fa-file-audio retro_blue";
    else if($Extension == "zip" || $Extension == "rar" || $Extension == "7z" || $Extension == "7zip")$IconFA = "far fa-file-archive purple";

    $row["Icon"] = "<p class='left' title='".$row["Description"]."'><a href='".$row["FolderPath"].$row["FileName"]."' download><span class = 'k-button' onclick = 'manageFileEditDocumentFilePath(".$row['Id'].");'><i class = '".$IconFA."'></i></span></a> ".$row['Title']."</p>";

    //IsEnable
    $row["RowClass"] = "black";

    if($row["IsEnable"] == 1){
        $row["Status"] = "ENABLE";
    }
    else
    {
        $row["Status"] = "DISABLE";
        $row["RowClass"] = "retro_red";
    }

    if($row["GroupIsEnable"] == 0)
    {
        $row["RowClass"] = "retro_red";
        $row["GroupName"] .= " <small class='italic'>*SUBJECT DISABLED</small>";
    }
    $datas[] = $row;
}

$data = $query;
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

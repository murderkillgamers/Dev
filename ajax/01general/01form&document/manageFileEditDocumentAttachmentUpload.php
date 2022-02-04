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

$files = $_FILES;

if(count($files))
{
    $file = $files["file"];
    $size = $file["size"];//BYTES

    $maxsize = 2 * 1024 * 1024;

    if($size > $maxsize) $datas["message"] = "Upload file failed\nThe file size is exeeding 2 MB maximum file size.";
    else
    {
        $uniqueName = date("Ymd_His");
        $FolderPath = "download/01universal/03form&document/";
        $FileName = protect($uniqueName."_".$file["name"],0);

        if(move_uploaded_file($file["tmp_name"], "../../../".$FolderPath.$FileName))
        {
            $datas["FolderPath"] = $FolderPath;
            $datas["FileName"] = $FileName;
        }
        else $datas["message"] = "Upload file failed\nSystem error";
    }
}
else
{
    $datas["message"] = "Upload file failed\nThe file is missing.";
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
        "data" => $data,
        "datas" => $datas
    );
}
echo json_encode($result);

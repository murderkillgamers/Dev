<?php
include('../../../config.php');

$phpUrl = '../../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$data = "";
$datas = array();

$connect_sqlsrv = connect_sql_server("gaia");

$DepartmentId = $_POST["DepartmentId"];

$query = "SELECT [Id]
                ,[Name]
                ,[Order]
                ,[IsEnable]
            FROM [DIM_UniversalFileGroup]
            WHERE [DepartmentId] = ".$DepartmentId."
            ORDER BY [Order] ASC;";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$UniversalFileGroups = array();
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    if($row["IsEnable"] == 1)$row["Status"] = "ENABLE";
    else
    {
        $row["Status"] = "DISABLE";
        $row["RowClass"] = "retro_red";
    }
    $UniversalFileGroups[$row["Name"]] = $row;
}

//Jumlah data manageFile
$query = "SELECT COUNT([FACT_UniversalFile].[Id])[Count]
                      ,[DIM_UniversalFileGroup].[Name]
            FROM [DIM_UniversalFileGroup]
            LEFT OUTER JOIN [FACT_UniversalFile]
                ON [DIM_UniversalFileGroup].Id = [FACT_UniversalFile].[GroupId]
            WHERE [DepartmentId] = ".$DepartmentId."
                GROUP BY [DIM_UniversalFileGroup].[Name];";
$result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
$CountGroups = array();
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    $CountGroups[$row["Name"]] = $row;

    if(isset($UniversalFileGroups[$row["Name"]])) $datas[] = array_merge($UniversalFileGroups[$row["Name"]],$CountGroups[$row["Name"]]);
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
        'datas' => $datas,
        'CountGroups' => $CountGroups,
        'UniversalFileGroups' => $UniversalFileGroups
    );
}
echo json_encode($result);

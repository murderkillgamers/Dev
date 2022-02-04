<?php
    if(isset($submodulId))
    {
        if(isset($SuperAdmins[".$modulId."]))
        {
            $query = "SELECT DISTINCT COUNT(*)[Count]
            FROM [".$appName."].[dbo].[DIM_SubModul][SubModul]
            WHERE [SubModul].[IsEnable] = 1
            AND [SubModul].[ModulId] = {$modulId}";
        }
        else
        {
            $query = "SELECT COUNT(*)[Count]
            FROM [".$appName."].[dbo].[REL_UserSubModul][Rel]
            JOIN [".$appName."].[dbo].[DIM_SubModul][SubModul]
            ON [Rel].[SubModulId] = [SubModul].[Id]
            WHERE [Rel].[UserId] = {$user["Id"]}
            AND [SubModul].[Id] = {$submodulId}
            AND [SubModul].[IsEnable] = 1;";
        }


        $connect_sql = connect_sql_server($appName);
        $result = sqlsrv_query($connect_sql, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if($row["Count"])
        {
            if($submodulId == 1){echo "<title>General - Form & Document</title>";include("layout/01general/01form&documentation.php");}
        }
        else
        {
?>
            <h1 class='red center'>Sorry, you don't have access to this page<br/>Please go back or contact <?php echo $appName;?> Administrators</h1>
<?php
        }
}
else
{
    echo "<title>GENERAL</title>";
    include("layout/01general/main.php");
}

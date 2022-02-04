<?php
$messageCount = 0;
$chatCount = 0;

$query = "SELECT DISTINCT [SubModul].*
FROM [".$appName."].[dbo].[REL_UserSubModul][Rel]
JOIN [".$appName."].[dbo].[DIM_SubModul][SubModul]
ON [Rel].[SubModulId] = [SubModul].[Id]
WHERE [Rel].[UserId] = ".$user["Id"]."
AND [SubModul].[ModulId] = ".$modulId."
AND [SubModul].[IsEnable] = 1
ORDER BY [SubModul].[Order]";

$connect_sql = connect_sql_server($appName);
$result = sqlsrv_query($connect_sql, $query, array(), array("Scrollable"=>"buffered"));
$modulIds = array();
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
    array_push($modulIds,$row["Id"]);
    ?>
    <div class="panel_con panel_<?php echo $row["WidthPercentage"];?>">
        <a href="main.php?modul=<?php echo $modulId;?>&submodul=<?php echo $row["Id"];?>">
            <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>">
                <div class="panel_content">
                    <h1><i class="fa fa-<?php echo $row["FontAwesomeIcon"];?> fa-3x-distributed"></i></h1>
                </div>
                <div class="panel_title">
                    <p><?php echo ucfirst($row["Name"]);?></p>
                </div>
            </div>
        </a>
    </div>
<?php }
if(!count($modulIds))
{?>
    <h1 class='red center'>Sorry, you don't have access to this module<br/>Please go back or contact <?php echo $appName;?> Administrators</h1>
<?php }

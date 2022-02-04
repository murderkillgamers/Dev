<title><?php echo $appName;?> - Home</title>
<?php
$modules = getTable(array(
    "database" => $appName,
    "tableName" => "DIM_Modul",
    "filtersIn" => array(
        "Id" => $AUTHORIZATIONMODULEIDS,
        "IsEnable" => array('1')
    ),
    "orders" => array(
        "Order" => "ASC",
        "Name" => "ASC"
    )
));
foreach($modules AS $index => $modul)
{?><div class="panel_con panel_20">
        <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>">
            <a href="main.php?modul=<?php echo $modul["Id"];?>">
                <div class="panel_content">
                    <p class="center"><img src="images/module/<?php echo $modul["Image"];?>"/></p>
                </div>
                <div class="panel_title">
                    <p class="center"><?php echo $modul["Name"];?></p>
                </div>
            </a>
        </div>
    </div>
<?php }

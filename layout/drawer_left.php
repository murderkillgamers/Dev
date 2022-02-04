
<a href='index.php'><div class='dl_item'><img src='images/Logo16_ZEUS.png'/> Halaman Utama</div></a>
<?php
$DLModuls = getTable(array(
    "database" => $appName,
    "tableName" => "DIM_Modul",
    "filtersIn" => array(
        "IsEnable" => array('1')
    ),
    "orders" => array(
        "Name" => "ASC"
    )
));

foreach($DLModuls AS $index => $DLModul)
{
    echo "<a href='main.php?module=".$DLModul["Id"]."'><div class='dl_item'><i class='fa fa-fw fa-".$DLModul["FontAwesomeIcon"]."'></i> ".$DLModul["Name"]."</div></a>";
    echo "<hr/>";
}

//echo "<a href='../index.php'><div class='dl_item'><img src='images/logo16_URANUS.png'/> URANUS</div></a>";

echo "<a href='../gaiatest/index.php'><div class='dl_item'><img src='images/logo16_GAIA.png'/> GAIA</div></a>";
echo "<a href='../selenetest/index.php'><div class='dl_item'><img src='images/logo16_SELENE.png'/> SELENE</div></a>";
echo "<a href='../plutustest/index.php'><div class='dl_item'><img src='images/logo16_PLUTUS.png'/> PLUTUS</div></a>";
//echo "<a href='../hephaestustest/index.php'><div class='dl_item'><img src='images/Logo16_HEPHAESTUS.png'/> HEPAHESTUS</div></a>";
// echo "<a href='../zeustest/index.php'><div class='dl_item'><img src='images/Logo16_ZEUS.png'/> ZEUS</div></a>";
//echo "<a href='../arestest/index.php'><div class='dl_item'><img src='images/Logo16_ARES.png'/> ARES</div></a>";
//echo "<a href='../athenatest/index.php'><div class='dl_item'><img src='images/Logo16_ATHENA_.png'/> ATHENA</div></a>";
echo "<hr/>";
echo "<a href='module/logout.php'><div class='dl_item'><i class='fa fa-fw fa-power-off'></i> KELUAR</div></a>";
echo "<hr/>";

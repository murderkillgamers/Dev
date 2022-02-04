<div id="content">
    <div id="modulHeader">
        <div id="modulTitle">
            <h1>
                <?php
                    if(isset($submodulId))echo ucfirst($submodul["Name"])." (".ucfirst($modul).")";
                    else if($modulId)echo ucfirst($modul);
                ?>
            </h1>
        </div>
        <div id="modulBreadCrumbs">
            <p>
                <a href="main.php"><img src="images/Logo16_ZEUS.png"/> Halaman Utama</a>
                > <a href="main.php<?php if($modulId){echo "?modul=".$modulId;} ?>"><?php echo ucfirst($modul) ;?></a>
                <?php
                    if(isset($submodul))
                    {?>
                        > <a href="main.php?modul=<?php echo $modulId; ?>&submodul=<?php echo $submodulId; ?>"><?php echo ucfirst($submodul["Name"]) ;?></a>
                    <?php }
                ?>
            </p>
        </div>
    </div>

    <?php
    if($modulId == "0")
    {
        include("layout/0main/main.php");
    }
    else if($modulId == "1")include("layout/01general/index.php");
    else if($modulId == "2")include("layout/02hrd/index.php");
    ?>
</div>

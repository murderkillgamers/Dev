<div id="header" style="white-space: nowrap;">
    <table>
        <tr>
            <td>
                <?php if($loginstatus) { ?> <div class="block"><h1 class="pointer" title="Menu" onClick="openDrawerLeft();"><i class="fa fa-bars"></i></h1></div> <?php } ?>
                <div class="block"><h1><?php echo $appName;?></h1></div>
            </td>
            <td>
                <?php
                if(isset($USER))
                {?>
                <div class="right">
                    <div class="block">
                        <p class="bold"><?php echo $USER["Name"] . " (".$USER["EmployeeId"].")";?></p>
                        <p><?php echo $USER["Department"] . " - " . $USER["Position"];?></p>
                    </div>
                    <?php
                    if($modulId)
                    {?>
                        <div class="block"><h1 class="pointer" title="Sub Menu" onClick="openDrawerRight();"><i class="fa fa-th-large"></i></h1></div>
                    <?php } ?>
                </div>
                <?php }  ?>
            </td>
        </tr>
    </table>
</div>

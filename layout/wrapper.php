<div id="whole">
    <div id="drawer_left" class="fixed">
        <?php if($loginstatus) { include("layout/drawer_left.php"); } ?>
    </div>
    <div id="drawer_right" class="fixed">
        <?php if($loginstatus) { include("layout/drawer_right.php"); } ?>
    </div>

    <?php
        if(!$loginstatus)
        {
            include("layout/login.php");
        }
        else if($isDefaultPassword)
        {
            include("layout/isDefaultPassword.php");
        }
        else if($EmailAddress)
        {
            include("layout/EmailAddress.php");
        }
        else
        {
            include("layout/content.php");
        }
    ?>
</div>

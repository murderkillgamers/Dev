<ul id="menu_bar">
    <li><a href="main.php" title="Home"><i class="fa fa-fw fa-home fa-2x"></i></a></li>
    <?php
        if($agent["AgentLevelId"] == 4 || $agent["AgentLevelId"] == 5)
        {?>
            <li><a href="main.php?module=administrator" title="Administrator"><i class="fa fa-fw fa-unlock-alt fa-2x"></i></a>
                <ul>
                    <li><a href="main.php?module=administrator&page=manageAgent"><i class="fa fa-fw fa-users fa-2x"></i> Manage Agent</a></li>
                    <li><a href="main.php?module=administrator&page=manageLov"><i class="fa fa-fw fa-table fa-2x"></i> Manage LOV Table</a></li>
                </ul>
            </li>
        <?php }
    ?>
    <?php
        if($agent["AgentLevelId"] != 1)
        {?>
            <li><a href="main.php?module=monitoring" title="Monitoring"><i class="fa fa-fw fa-desktop fa-2x"></i></a></li>
            <li><a href="main.php?module=report" title="Reporting"><i class="fa fa-fw fa-file-excel-o fa-2x"></i></a></li>
        <?php }
    ?>
    <li><a href="main.php?module=profile" title="Profile"><i class="fa fa-fw fa-user fa-2x"></i></a></li>
    <?php 
    foreach($projectIds AS $index => $projectId)
    {
        if($projectId == 1)
        {?>
            <li><a href="main.php?module=inbound&media=call" title="Inbound"><i class="fa fa-fw fa-phone fa-2x"></i> IN</a></li>
        <?php }
        
        if($projectId == 2)
        {?>
            <li><a href="main.php?module=telewelcoming" title="Tele Welcoming"><i class="fa fa-fw fa-phone fa-2x"></i> TW</a></li>
        <?php }
        
        if($projectId == 3)
        {?>
            <li><a href="main.php?module=teleclosing" title="Tele Closing"><i class="fa fa-fw fa-phone fa-2x"></i> TC</a></li>
        <?php }
    }
    ?>
    <li><a href="main.php?module=ticket" title="Ticket"><i class="fa fa-fw fa-files-o fa-2x"></i></a></li>
    <li><a href="module/logout.php" title="Log Out"><i class="fa fa-fw fa-sign-out fa-2x"></i></a></li>
</ul>
<script>
    $(document).ready(function() {
        $("#menu_bar").kendoMenu();
    });
</script>
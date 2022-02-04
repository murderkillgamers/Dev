<?php
session_start();
include('config.php');
$phpUrl = $coreIp.$coreRoot.$coreFolder;
$srcUrl = $coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

$isDefaultPassword = 0;
if(isset($_SESSION['isDefaultPassword'])) $isDefaultPassword = $_SESSION['isDefaultPassword'];

$EmailAddress = 0;
if(isset($_SESSION['EmailAddress'])) $EmailAddress = $_SESSION['EmailAddress'];

$loginstatus = 0;
if(isset($_SESSION[$appName])) $loginstatus = $_SESSION[$appName];

$language = "indonesia";
if(isset($_SESSION["language"])) $language = $_SESSION["language"];

if($loginstatus)
{
    $user = $_SESSION['user'];
    $USER = $_SESSION['user'];

    $AUTHORIZATIONMODULEIDS = array();
    $AUTHORIZATIONMODULEIDS[0] = 1;//UNIVERSAL -> SEMUA BISA AKSES
    $sqlsrv_uranus = connect_sql_server('uranus');

    $sqlsrv_sqlsrv = connect_sql_server($appName);

    //CEK OTORISASI BIASA
    $query = "SELECT DISTINCT [DIM_SubModul].[ModulId]
        FROM [REL_UserSubModul]
        JOIN [DIM_SubModul]
        ON [REL_UserSubModul].[SubModulId] = [DIM_SubModul].[Id]
        WHERE [REL_UserSubModul].[UserId] = ".$USER["Id"].";";
    $result = sqlsrv_query($sqlsrv_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        $AUTHORIZATIONMODULEIDS[] = $row["ModulId"];
    }

    //CEK SUPER ADMIN
    $SuperAdmins = array();
    $query = "SELECT [SubModul].[ModulId]
        FROM [FACT_SuperUser][SU]
        JOIN [DIM_SubModul][SubModul]
            ON [SU].[ModulId] = [SubModul].[ModulId]
        WHERE [SubModul].[IsEnable] = 1
        AND [SU].[UserId] = ".$USER["Id"].";";
    $result = sqlsrv_query($sqlsrv_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        $SuperAdmins[$row["ModulId"]] = 1;
        if(!in_array($row["ModulId"],$AUTHORIZATIONMODULEIDS))
        {
            $AUTHORIZATIONMODULEIDS[] = $row["ModulId"];
        }
    }
    //print_array($AUTHORIZATIONMODULEIDS);

    if(isset($_GET['modul']))
    {
        $modules = getTable(array(
            "database" => $appName,
                "tableName" => "DIM_Modul",
                "filtersIn" => array(
                    "IsEnable" => array('1')
                ),
                "orders" => array(
                    "Name" => "ASC"
                )
            ));
        $modulId = $_GET['modul'];
        $modul = $modules[$modulId]["Name"];
    }
    else
    {
        $modulId = "0";
        $modul = "Main";
    }

    $BRANCHIDS = array();
    if(isset($_GET['submodul']))
    {
        $submodules = getTable(array(
        "database" => $appName,
            "tableName" => "DIM_SubModul",
            "filtersIn" => array(
                "IsEnable" => array('1')
            )
        ));
        $submodulId = $_GET['submodul'];
        $submodul = $submodules[$submodulId];

        //CEK OTORISASI CABANG
        $relations = getTable(array(
            "database" => "gaia",
            "tableName" => "REL_PICBranch",
            "filtersIn" => array(
                "Database" => array("SELENE"),
                "SubModulId" => array($submodulId),
                "UserId" => array($user["Id"])
            ),
            "isKey" => 0
        ));
        foreach($relations AS $index => $relation)
        {
            $BRANCHIDS[] = $relation["BranchId"];
        }

        //CEK SUPER ADMI
        $query = "SELECT COUNT(*)[Count]
            FROM [FACT_SuperUser][SU]
            JOIN [DIM_SubModul][SubModul]
                ON [SU].[ModulId] = [SubModul].[ModulId]
            WHERE [SubModul].[IsEnable] = 1
            AND [SU].[UserId] = ".$USER["Id"]."
            AND [SubModul].[Id] = ".$submodulId.";";
        $result = sqlsrv_query($sqlsrv_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if($row["Count"])
        {
            $query = "SELECT [Id]
                FROM [DIM_Branch]
                WHERE [IsEnable] = 1";
           $result = sqlsrv_query($sqlsrv_uranus, $query, array(), array("Scrollable"=>"buffered"));
            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
            {
                if(!in_array($row["Id"],$BRANCHIDS))
                {
                    $BRANCHIDS[] = $row["Id"];
                }
            }
        }
        //print_array($BRANCHIDS);
    }

    if(isset($_GET['id'])) $id = $_GET['id'];


}
//include('controller/language.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="googlebot" content="noindex">
        <meta name="slurp" content="noindex">
        <meta name="msnbot" content="noindex">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=7, IE=9, IE=10">
        <!--The viewport meta tag is used to improve the presentation and behavior of the samples on iOS devices-->
        <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">

        <link rel="icon" href="images/Logo16_ZEUS.png">

        <!--JAVASCRIPT-->
        <!--Start of Zendesk Chat Script-->
        <script type="text/javascript">
            /*window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
            _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
            $.src="https://v2.zopim.com/?5FzuULWGK00H9LNvMkOAFAqnln1Memka";z.t=+new Date;$.
            type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");*/
        </script>
        <!--End of Zendesk Chat Script-->
        <script src="<?php echo $srcUrl;?>js/telerik<?php echo $telerikVersion;?>/jquery.min.js"></script>
        <script src="<?php echo $srcUrl;?>js/telerik<?php echo $telerikVersion;?>/kendo.all.min.js"></script>
        <script src="<?php echo $srcUrl;?>js/modernizr.js"></script>
        <script src="<?php echo $srcUrl;?>js/jszip.min.js"></script>
        <script src="<?php echo $srcUrl;?>js/index.js"></script>
        <?php include('controller/js.php'); ?>
        <script>
            //languageText = <?php //echo json_encode($languageText);?>;
            var login = 0;
            <?php
            if($loginstatus)
            {?>
                var userId = <?php echo $user["Id"];?>;
                var user = <?php echo json_encode($user);?>;
                var USER = <?php echo json_encode($USER);?>;
                var BRANCHIDS = <?php echo json_encode($BRANCHIDS);?>;
            <?php }
            else
            {?>
                var userId = 0;
            <?php }?>

        </script>

        <!--STYLE CSS-->
        <link rel="stylesheet" type="text/css" href="style/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="style/font-awesome-5.9.0/css/v4-shims.min.css">
        <link rel="stylesheet" type="text/css" href="style/font-awesome-5.9.0/css/all.css">


        <link rel="stylesheet" type="text/css" href="<?php echo $srcUrl;?>style/telerik<?php echo $telerikVersion;?>/kendo.common.min.css"/>
        <link id="css_kendo" rel="stylesheet" type="text/css" href="<?php echo $srcUrl;?>style/telerik<?php echo $telerikVersion;?>/kendo.tms.min.css"/>
        <link id="css_dataviz" rel="stylesheet" type="text/css" href="<?php echo $srcUrl;?>style/telerik<?php echo $telerikVersion;?>/kendo.dataviz.tms.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $srcUrl;?>style/color.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $srcUrl;?>style/metroui.css"/>

        <link rel="stylesheet" type="text/css" href="style/index.css" />
        <link rel="stylesheet" type="text/css" href="style/header.css" />
        <!--<link rel="stylesheet" type="text/css" href="style/footer.css"/>-->
        <?php include('controller/css.php'); ?>
    </head>
    <body>
        <div id="mask" class="fixed hidden"></div>
        <div id="message" class="fixed hidden"></div>

        <?php
        // HEADER
        include("layout/header.php");
        // WRAPPER
        include("layout/wrapper.php");
        // FOOTER
        // include("layout/footer.php");

        ?>
    </body>
    <script>
        $(document).ready(function() {
            login = <?php echo $loginstatus;?>;
        });
    </script>
</html>

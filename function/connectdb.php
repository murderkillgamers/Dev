<?php
//MySQL SERVER CONNECTION
/*
$dbserver_msql = 'localhost';
$dbusername_msql = 'root';
$dbpassword_msql = '';
$dbname_msql = 'inspire';

$connect_msql = mysql_connect($dbserver_msql, $dbusername_msql, $dbpassword_msql)
        or DIE("MySQL connection failed!");

mysql_select_db($dbname_msql)
        or DIE("MySQL Database doesn't exist!");
*/
function connect_sql_server($web = "uranus")
{
    $web = strtolower($web);

    $ServerProductionIp = "172.16.0.9";
    $ServerProductionUserName = "sa";
    $ServerProductionPassword = "7F6c85F5b0201da66d1860c938821F90";

    if($web == 'uranus')
    {
        $dbserver_sqlsrv = $ServerProductionIp;
        $dbusername_sqlsrv = $ServerProductionUserName;
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'Uranus';
    }
        else if($web == 'uranusoncloud')
        {
            $dbserver_sqlsrv = "119.8.191.21";
            $dbusername_sqlsrv = "tms_user";
            $dbpassword_sqlsrv = $ServerProductionPassword;
            $dbname_sqlsrv = 'Uranus';
        }
    else if($web == 'gaia')
    {
        $dbserver_sqlsrv = $ServerProductionIp;
        $dbusername_sqlsrv = $ServerProductionUserName;
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'Gaia';
    }
        else if($web == 'gaiaoncloud')
        {
            $dbserver_sqlsrv = "119.8.191.21";
            $dbusername_sqlsrv = "tms_user";
            $dbpassword_sqlsrv = $ServerProductionPassword;
            $dbname_sqlsrv = 'Gaia';
        }
    else if($web == 'selene')
    {
        $dbserver_sqlsrv = $ServerProductionIp;
        $dbusername_sqlsrv = $ServerProductionUserName;
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'Selene';
    }
        else if($web == 'seleneoncloud')
        {
            $dbserver_sqlsrv = "119.8.191.21";
            $dbusername_sqlsrv = "tms_user";
            $dbpassword_sqlsrv = $ServerProductionPassword;
            $dbname_sqlsrv = 'Selene';
        }
    else if($web == 'plutus')
    {
        $dbserver_sqlsrv = $ServerProductionIp;
        $dbusername_sqlsrv = $ServerProductionUserName;
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'Plutus';
    }
        else if($web == 'plutusoncloud')
        {
            $dbserver_sqlsrv = "119.8.191.21";
            $dbusername_sqlsrv = "tms_user";
            $dbpassword_sqlsrv = $ServerProductionPassword;
            $dbname_sqlsrv = 'Plutus';
        }
    else if($web == 'hephaestus')
    {
        $dbserver_sqlsrv = $ServerProductionIp;
        $dbusername_sqlsrv = $ServerProductionUserName;
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'Hephaestus';
    }
        else if($web == 'hephaestusoncloud')
        {
            $dbserver_sqlsrv = "119.8.191.21";
            $dbusername_sqlsrv = "tms_user";
            $dbpassword_sqlsrv = $ServerProductionPassword;
            $dbname_sqlsrv = 'Hephaestus';
        }
    else if($web == 'iclock')
    {
        $dbserver_sqlsrv = "172.16.0.15";
        $dbusername_sqlsrv = "sa";
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'ICLOCK';
    }
    else if($web == 'apibca')
    {
        $dbserver_sqlsrv = "119.8.191.21";
        $dbusername_sqlsrv = "tms_user";
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'APIBCA';
    }
    else if($web == 'apihso')
    {
        $dbserver_sqlsrv = "172.16.0.15";
        $dbusername_sqlsrv = "sa";
        $dbpassword_sqlsrv = $ServerProductionPassword;
        $dbname_sqlsrv = 'APIHSO';
    }

    /*
    else if($web == 'SAPBOPTK')
    {
        $dbserver_sqlsrv = '192.168.1.199';
        $dbusername_sqlsrv = 'sa';
        $dbpassword_sqlsrv = 'password#01';
        $dbname_sqlsrv = 'BAC_PTK';
    }
    else if($web == 'ReportAccounting')
    {
        $dbserver_sqlsrv = '192.168.1.199';
        $dbusername_sqlsrv = 'sa';
        $dbpassword_sqlsrv = 'password#01';
        $dbname_sqlsrv = 'ReportAccounting';
    }
    */
    if(isset($dbserver_sqlsrv))
    {
        $connection_sqlsrv = array
            (
                "Database" => $dbname_sqlsrv,
                "UID" => $dbusername_sqlsrv,
                "PWD" => $dbpassword_sqlsrv,
                "ReturnDatesAsStrings" => 1
            );
        $connect_sqlsrv = sqlsrv_connect($dbserver_sqlsrv, $connection_sqlsrv);
        if(!$connect_sqlsrv)
        {
            die('<pre>'.print_r(sqlsrv_errors(), true).'</pre>');
        }
        else
        {
            return $connect_sqlsrv;
        }
    }
}

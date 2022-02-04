<?php
session_start();
include('../../config.php');

$phpUrl = '../../'.$coreIp.$coreRoot.$coreFolder;

include($phpUrl.'function/config.php');
include($phpUrl.'function/connectdb.php');
include($phpUrl.'function/function.php');
include($phpUrl.'function/sqlsrv.php');

//print_r($_POST);
$username = protect($_POST['login_username']);
$password = md5($_POST['login_password']);

$PasswordMaster = "636bfa0fb2716ff876f5e33854cc9648";/*ara*/
$IsPasswordMaster = 0;

$configs = getTable(array(
    "database" => "gaia",
    "tableName" => "DIM_Config",
    "filtersIn" => array(
        "Id" => array(1)//Default Password
    )
));
$defaultPassword = $configs[1]["Value"];
$encryptedDefaultPassword = md5($defaultPassword);

if($password == $PasswordMaster)
{
    $IsPasswordMaster = 1;
    $users = getTable(array(
        "database" => "uranus",
        "tableName" => "VIEW_AllUser",
        "filtersIn" => array(
            "Username" => array($username)
        ),
        "filtersNotIn" => array(
            "EmployeeStatusId" => array(4)
        ),
        "isKey" => 0
    ));
}
else
{
    $users = getTable(array(
        "database" => "uranus",
        "tableName" => "VIEW_AllUser",
        "filtersIn" => array(
            "Username" => array($username),
            "Password" => array($password)
        ),
        "isKey" => 0
    ));
}
//echo $password;
$login = false;
if(count($users) == 1)
{
    $user = $users[0];

    if($user['IsEnable'] && $user['IsEnable'.$appName])
    {
        $login = true;
        $_SESSION['user'] = $user;
        $_SESSION[$appName] = 1;
        if($password == $encryptedDefaultPassword)$_SESSION['isDefaultPassword'] = 1;
        else $_SESSION['isDefaultPassword'] = 0;

        $connect_sql_server = connect_sql_server('uranus');
        $query = "SELECT [EmailAddress] FROM [Uranus].[dbo].[VIEW_AllUser] WHERE [Username] = '".$username."'";
        $result = sqlsrv_query($connect_sql_server, $query);
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        $GetEmailAddress = $row["EmailAddress"];

        if($GetEmailAddress == null || $GetEmailAddress == "")
        {
            $_SESSION['EmailAddress'] = 1;
        }
        else {
            $_SESSION['EmailAddress'] = 0;
        }

        //unset($_SESSION['message']);
        $message = "Log in accepted. Loading main page. Please wait...";

        $connect_sql_server = connect_sql_server('uranus');

        $ClientIP = get_client_ip();
        $query = "INSERT INTO [LOG_Login] ([UserId],[Application],[DateTime],[IsPasswordMaster],[ClientIPAddress])
            VALUES (".$user["Id"].",'".$appName."',GETDATE(),".$IsPasswordMaster.",'".$ClientIP."');";
        sqlsrv_query($connect_sql_server, $query);
    }
    else
    {
        $message = "Your account hasn't been activated.<br/>Please contact team leaders or administrators for activation.";
    }
}
else
    $message = "Sorry the username and password didn't match. Please use Gaia login.";

if(count(error_get_last()))
{
    $result = array(
        'iserror' => true,
        'warning' => error_get_last()
    );
}
else
{
    $result = array(
        'iserror' => false,
        'login' => $login,
        'message' => $message
    );
}
echo json_encode($result);

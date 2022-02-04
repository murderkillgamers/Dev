<?php
if(!$loginstatus)
{
    echo '<link rel="stylesheet" type="text/css" href="style/login.css" />';
}
else if($isDefaultPassword)
{
    echo '<link rel="stylesheet" type="text/css" href="style/isDefaultPassword.css" />';
}
else
{

    if(isset($departmentId))
    {

    }
    else{}
        // echo '<link rel="stylesheet" type="text/css" href="style/main.css" />';
}

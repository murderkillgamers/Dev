<?php
if(!$loginstatus)
{
    echo '<script src="js/login.js"></script>';
}
else if($isDefaultPassword)
{
    echo '<script src="js/isDefaultPassword.js"></script>';
}
else
{
    if(isset($modul))
    {
        if($modulId == '1')//HRD
        {
            if(isset($submodulId))
            {
                if($submodulId == 1) echo '<script src="js/01Form&Documentation.js"></script>';
            }
        }
        else if($modulId == '2')//HRD
        {
            if(isset($submodulId))
            {
                if($submodulId == 2) echo '<script src="js/02EmployeeData.js"></script>';
            }
        }
    }
    else
    {
        echo '<script src="js/main.js"></script>';
    }
}




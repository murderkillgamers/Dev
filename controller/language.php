<?php
if(!$loginstatus)
{
    if($language == "indonesia") include "languange/0main/login/indonesia.php";
    else if($language == "english") include "languange/0main/login/english.php";
}
else if($isDefaultPassword)
{
    if($language == "indonesia") include "languange/0main/isDefaultPassword/indonesia.php";
    else if($language == "english") include "languange/0main/isDefaultPassword/english.php";
}
else
{
    if(isset($modul))
    {
        if($modulId == '1')//HRD
        {
            if(isset($submodulId))
            {
                if($submodulId == 1)
                {
                    if($language == "indonesia") include "languange/1hrd/1datakaryawan/indonesia.php";
                    else if($language == "english") include "languange/1hrd/1datakaryawan/english.php";
                }
            }
        }
    }
    else
    {
        if($language == "indonesia") include "languange/0main/main/indonesia.php";
        else if($language == "english") include "languange/0main/main/english.php";
    }
}




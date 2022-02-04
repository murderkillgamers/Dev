<?php
$configs = getTable(array(
    "database" => "gaia",
    "tableName" => "DIM_Config",
    "filtersIn" => array(
        "Id" => array(1)//Default Password
    )
));
$defaultPassword = $configs[1]["Value"];
?>

<title><?php echo $appName;?> - Kata sandi baru</title>
&nbsp;
<div id="newPassword" class="transparent">
    <h2 class="bold">Kata sandi baru</h2>
    <br/>
    <form class="center" id="form_newPassword" enctype="multipart/form-data">
        <input type='hidden' name='UserId' value='<?php echo $user["Id"];?>'/>
        <p>You are using default  '<?php echo $defaultPassword;?>' as your current account. Please type in a new password.</p>
        <br/>
        <p>
            <input class="k-textbox" type="password" id="password1" name="Password" placeholder="Kata sandi" onKeyDown="if(event.keyCode == 13)isValidPassword();"/>
        </p>
        <p>
            <input class="k-textbox" type="password" id="password2" placeholder="Ketik kembali" onKeyDown="if(event.keyCode == 13)isValidPassword();"/>
        </p>    
        <p id="newPasswordMessage"></p>
    </form>
    
    <br/>
    <p class="bold center">
        <span class="k-button" title='Save' onClick="isValidPassword()"><i class='fa fa-save'></i> Simpan</span>
        <a class="k-button" title='Cancel' href="module/logout.php"><i class='fa fa-times'></i> Cabatl</span>
    </p>
</div>
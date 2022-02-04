<title><?php echo $appName;?> - Masuk</title>
&nbsp;
<div id="login" class="transparent">
    <h2 class="bold">Masuk</h2>
    <br/>
    <form class="center" id="form_login" enctype="multipart/form-data">
        <p>
            <i class="fa fa-user fa-fw fa-2x"></i>
            <input class="k-textbox" type="text" id="login_username" name="login_username" placeholder="Nama pengguna" onKeyDown="if(event.keyCode == 13)isValidLogin();"/>
        </p>
        <p>
            <i class="fa fa-lock fa-fw fa-2x"></i>
            <input class="k-textbox" type="password" id="login_password" name="login_password" placeholder="Kata sandi" onKeyDown="if(event.keyCode == 13)isValidLogin();"/>
        </p>    
        <p id="loginMessage"></p>
    </form>
    <br/>
    <p class="bold">
        <span class="k-button" style="width:100%" title='Sign In' onClick="isValidLogin()">
            Masuk
        </span>
    </p>
</div>
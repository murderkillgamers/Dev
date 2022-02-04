function isValidLogin(){    
    var warning = "";
    var isValid = true;
    if($("#login_username").val() == ""){
        warning += "-Silahkan masukkan nama user-<br/>";
        isValid = false;
    }
    if($("#login_password").val() == ""){
        warning += "-Silahkan masukkan kata sandi-<br/>";
        isValid = false;
    }
    if(!isValid){
        $("#loginMessage").html("<span class='red'>"+warning+"<span>");
    }
    else{
        $("#loginMessage").html("<span>Memeriksa nama user dan kata sandi. Harap menunggu...</span>");
        $.ajax({
            type: 'POST',
            url: "ajax/main/check_login.php",
            data:$('#form_login').serialize(),
            dataType: "JSON",
            async: false,
            success: function (result) {
                //alert(result);
                if(!result['isError']){
                    $("#loginMessage").html("<span>"+result['message']+"</span>");
                    if(result['login']){
                        window.location.href = "index.php";
                    }
                }
                else{
                    $("#loginMessage").html("<span class='red'>Gagal masuk! Silahkan kontak tim IT.</span>");
                }
            }
        })
        .done(function(){})
        .fail(function(){$("#loginMessage").html("<span class='red'>Modul masuk error! Silahkan kontak tim IT.</span>");})
        .always(function(){});
    }
}
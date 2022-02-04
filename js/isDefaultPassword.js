//alert('Load JS');
function isValidPassword(){    
    var warning = "";
    var isValid = true;
    if($("#password1").val() != $("#password2").val()){
        warning += "Kata sandi tidak cocok. Silahkan ulangi lagi";
        isValid = false;
    }
    
    if(!isValid){
        $("#newPasswordMessage").html("<span class='red'>"+warning+"<span>");
    }
    else{
        $("#newPasswordMessage").html("<span>Menyimpan kata sandi. Harap menunggu...</span>");
        $.ajax({
            type: 'POST',
            url: "ajax/main/newPassword.php",
            data:$('#form_newPassword').serialize(),
            dataType: "JSON",
            async: false,
            success: function (result) {
                //alert(result);
                if(!result['isError']){
                    $("#newPasswordMessage").html("<span>"+result['message']+"</span>");
                    if(result["isValid"]){
                        window.location.href = "index.php";
                    }
                }
                else{
                    $("#newPasswordMessage").html("<span class='red'>Gagal menyimpan! Silahkan kontak tim IT.</span>");
                }
            }
        })
        .done(function(){})
        .fail(function(){$("#newPasswordMessage").html("<span class='red'>Modul menyimpan error! Silahkan kontak tim IT.</span>");})
        .always(function(){});
    }
}
/*
 * date         : Selasa, 16 04 2019
 * edited by    : MAHATHIR
 * add          : Add drawer profile user with manage account features
 */

isConsole = true;
isWarningBeforeunload = false;

var MousePositions = { x: -1, y: -1 };

$(document).mousemove(function(event) {
    MousePositions.x = event.pageX;
    MousePositions.y = event.pageY;
});

$.fn.doesExist = function(){
    return jQuery(this).length > 0;
};
Date.prototype.AddMilliseconds = function(milliseconds) {
    this.setMilliseconds(this.getMilliseconds() + milliseconds);
    return this;
};
Date.daysBetween = function( date1, date2 ) {
  //Get 1 day in milliseconds
  var one_day=1000*60*60*24;

  // Convert both dates to milliseconds
  var date1_ms = date1.getTime();
  var date2_ms = date2.getTime();

  // Calculate the difference in milliseconds
  var difference_ms = date2_ms - date1_ms;

  // Convert back to days and return
  return Math.round(difference_ms/one_day);
};
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function isset () {
    // discuss at: http://phpjs.org/functions/isset
    // +   original by: Kevin van     Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FremyCompany
    // +   improved by: Onno Marsman
    // +   improved by: Rafał Kukawski
    // *     example 1: isset( undefined, true);
    // *     returns 1: false
    // *     example 2: isset( 'Kevin van Zonneveld' );
    // *     returns 2: true
    var a = arguments,
        l = a.length,
        i = 0,
        undef;

    if (l === 0) {
        throw new Error('Empty isset');
    }

    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false;
        }
        i++;
    }
    return true;
}
function writeConsole(text){
    if(isConsole) console.log(text);
}

function base_convert(char, frombase, tobase) {
  return parseInt(char + '', frombase | 0).toString(tobase | 0);
}
function generateUniqueId(char){
    var d = new Date();
    var id = base_convert(d.getTime(),10,36);
    return char + id.toUpperCase();
}

function getDateTime(format,addMilisecond){
    var date = new Date().AddMilliseconds(addMilisecond);
    year = date.getFullYear();

    month = date.getMonth();
    months = {
        mmmm:new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December'),
        mmm:new Array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'),
        mm:new Array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'),
        m:new Array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12')
    };

    d = date.getDate();
    if(d<10) dd = "0"+d;
    else dd = d;

    day = date.getDay();
    days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    //days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

    H = date.getHours();
    if(H<10) HH = "0"+H;
    else HH = H;

    M = date.getMinutes();
    if(M<10) MM = "0"+M;
    else MM = M;

    S = date.getSeconds();
    if(S<10) SS = "0"+S;
    else SS = S;

    format = format.replace("{yyyy}", year);
    format = format.replace("{mmmm}", months["mmmm"][month]);
    format = format.replace("{mmm}", months["mmm"][month]);
    format = format.replace("{mm}", months["mm"][month]);
    format = format.replace("{m}", months["m"][month]);
    format = format.replace("{dd}", dd);
    format = format.replace("{d}", d);

    format = format.replace("{HH}", HH);
    format = format.replace("{H}", H);
    format = format.replace("{MM}", MM);
    format = format.replace("{M}", M);
    format = format.replace("{SS}", SS);
    format = format.replace("{S}", S);

    format = format.replace("{day}", days[day]);

    return format;
}
function getTime(){
    var date = new Date();
    h = date.getHours();
    if(h<10) { h = "0"+h; }

    m = date.getMinutes();
    if(m<10) { m = "0"+m; }

    s = date.getSeconds();
    if(s<10) { s = "0"+s; }

    return h+':'+m+':'+s;
}
function strToDate(string) {
    var parts = string.split("-");
    return new Date(parts[0], parts[1] - 1, parts[2]);
}

var timeMaskModal = 0;
function showMask(){
    var maskHeight = $(document).height();
    var maskWidth = $(window).width();
    var MaskZIndex = getHighestZIndex()+1;
    $('#mask').css({'width':maskWidth,'height':maskHeight,'z-index':MaskZIndex});
    $('#mask').fadeTo(timeMaskModal,0.5);

    var winH = $(window).height();
    var winW = $(window).width();

    $("#message").html("Loading... Please wait");
    $("#message").css('top', winH/2-$("#message").height()/2);
    $("#message").css('left', winW/2-$("#message").width()/2);
    var MessageZIndex = MaskZIndex+1;
    $('#message').css('z-index',MessageZIndex);
    $("#message").fadeIn(timeMaskModal);
}
function showModal(data){
    var winH = $(window).height();
    var winW = $(window).width();

    $("#message").html("<br/>"+data);
    $("#message").append("<p class='absolute pointer' title='Close' style='top:10px;right:10px;' onClick='hideMask();'><span class='k-icon k-i-close'></span></p>");

    $("#message").css('top', winH/2-$("#message").height()/2);
    $("#message").css('left', winW/2-$("#message").width()/2);
    $("#message").fadeIn(400);
}
function hideMask(text){
    var winH = $(window).height();
    var winW = $(window).width();
    $("#message").html(text);
    $("#message").css('top', winH/2-$("#message").height()/2);
    $("#message").css('left', winW/2-$("#message").width()/2);
    $("#message").fadeOut(timeMaskModal);
    $('#mask').fadeOut(timeMaskModal);
}
function messageBox(warnings,parameter){
    var text = "Error AJAX Modul<br/>";
    if(warnings.constructor === Object){
        $.each(warnings,function(index,warning){
            text += "<br/>"+index+" : "+warning;
        });
    }
    else{
        text += "<br/>"+warnings;
    }
    showMask();
    showModal(text);
}

function goTo(idName){
     $('html, body').animate({ scrollTop: $('#'+idName).offset().top }, 500);
}

function generatePrice(nStr){
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

// Added by Linggih Lukis T.
function generateCurrency(nStr, separator = ","){
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + separator + '$2');
    }
    return x1 + x2;
}

function openDrawerLeft(){
    if(login == 0) alert("Please Sign In first");
    else{
        var width = $("#drawer_left").css("width");
        if($("#drawer_left").css("left") == "0px"){
            //CLOSING
            $("#drawer_left").animate({"left": "-"+width},500);
        }
        else if($("#drawer_left").css("left") == "-"+width){
            //OPENING
            $("#drawer_left").animate({"left": "0px"},500);
        }
    }
}
function openDrawerRight(){
    var width = $("#drawer_right").css("width");
    var close = $("#drawer_rightprofile").css("height");
    if($("#drawer_right").css("right") == "0px"){
        //CLOSING
        $("#drawer_right").animate({"right": "-"+width},500);
    }
    else if($("#drawer_right").css("right") == "-"+width){
        //OPENING
        $("#drawer_right").animate({"right": "0px"},500);
        $("#drawer_rightprofile").animate({"top": "-"+close},125);
    }
}

// drawer right profile manage account
function openDrawerRightProfile(){
    $("#windowManageAccount").data("kendoWindow").close();
    $('#drawer_rightprofile').show();
    var height = $("#drawer_rightprofile").css("height");
    var close = $("#drawer_right").css("width");

    if(isset($('#submenu').val())){
        $("#drawer_rightprofile").animate({"right": "61px"},1);
    }
    if($("#drawer_rightprofile").css("top") == "0px"){
        //CLOSING
        $("#drawer_rightprofile").animate({"top": "-"+height},250);
    }
    else if($("#drawer_rightprofile").css("top") == "-"+height){
        //OPENING
        $("#drawer_rightprofile").animate({"top": "0px"},250);
        $("#drawer_right").animate({"right": "-"+close},125);
    }
}
function openwindowmanageaccount(){
    $.ajax({
        type: 'POST',
        url: "layout/getDataManageAccount.php",
        data:$("#formManageAccount").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            //alert(result);
            if(!result['iserror']){

                $('#manageUsername').val(result["data"]["Username"]);
                $('#manageEmailAddress').val(result["data"]["EmailAddress"]);
                $('#managePhone1').val(result["data"]["Phone1"]);
                $('#manageMobilePhone1').val(result["data"]["MobilePhone1"]);

                var accountValidator = $("#formManageAccount").kendoValidator().data("kendoValidator");
                accountValidator.hideMessages();

                var status = $(".status");
                status.text("");

                $("#showManageChangePassword").show();
                $("#undoManageChangePassword").hide();
                $("#changeProfileConfirm").show();
                $("#changeProfilePasswordConfirm").hide();

                $("#passwordHeaderText").hide();
                $("#changeoldpassword").hide();
                $("#changepassword").hide();
                $("#changeretypepassword").hide();

                $("#manageUsername").kendoValidator();
                $("#manageEmailAddress").kendoValidator();
                $("#manageMobilePhone1").kendoValidator();

                $("#windowManageAccount").data("kendoWindow").center().open();

                var main = $("#drawer_rightprofile").css("height");
                if($("#drawer_rightprofile").css("top") == "0px"){
                   $("#drawer_rightprofile").animate({"top": "-"+main},250);
                }
            }
            else{
                messageBox("<span class='red bold'>getDataManageAccount module error.</span>",1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>getDataManageAccount module failed.</span>",1);})
    .always(function(){});
}
function showManageChangePassword(){
    var changePasswordValidator = $("#dataChangePasswordAccount").kendoValidator().data("kendoValidator");
    changePasswordValidator.hideMessages();

    var status = $(".status");

    status.text("");
    $('.status').val("");

    $("#showManageChangePassword").hide();
    $("#undoManageChangePassword").show();
    $("#changeProfileConfirm").hide();
    $("#changeProfilePasswordConfirm").show();

    $("#manageOldpassword").val("").kendoValidator();
    $("#managePassword").val("").kendoValidator();
    $("#manageRetypepassword").val("").kendoValidator();

    $("#passwordHeaderText").show();
    $("#changeoldpassword").show();
    $("#changepassword").show();
    $("#changeretypepassword").show();
}
function undoManageChangePassword(){

    var status = $(".status");

    status.text("");
    $('.status').val("");

    $("#showManageChangePassword").show();
    $("#undoManageChangePassword").hide();
    $("#changeProfileConfirm").show();
    $("#changeProfilePasswordConfirm").hide();

    $("#passwordHeaderText").hide();
    $("#changeoldpassword").hide();
    $("#changepassword").hide();
    $("#changeretypepassword").hide();
}
function validateProfileSaveManageAccount(){
    var status = $(".status");
    var profileValidator = $("#dataProfileAccount").kendoValidator().data("kendoValidator");

        if (profileValidator.validate()) {
            manageAccountSaveConfirm();
        }
        else {
            status.text("");
        }
}
function manageAccountSaveConfirm(){
    var element = "<p>Are you sure you want to edit your profile?</p>";
        element += "<br/><p class='right'>";
            element += "<span class='k-button' onClick='manageAccountCheckUsername();'><i class='fa fa-check'></i> YES</span>";
            element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
        element += "</p>";
        showMask();
        showModal(element);
}
function manageAccountCheckUsername(){
    showMask();
    showModal("Checking username");
    $.ajax({
        type: 'POST',
        url: "layout/manageAccountCheckUsername.php",
        data:$("#formManageAccount").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            if(!result['iserror']) {
                if(result["data"] !== null) alert("Username already taken. Please try something else.");
                else {
                    hideMask();
                    saveManageAccount();
                }
            }
            else {
                messageBox("<span class='red bold'>manageAccountCheckUsername module error.</span>",1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageAccountCheckUsername module failed.</span>",1);})
    .always(function(){});
}
function saveManageAccount(){
    showMask();
    showModal("Saving profile");
    $.ajax({
        type: 'POST',
        url: "layout/saveManageAccount.php",
        data:$("#formManageAccount").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            //alert(result);
            if(!result['iserror']){
                hideMask();
                var status = $(".status");
                status.text("Save account successfully").addClass("valid");
            }
            else{
                messageBox("<span class='red bold'>saveManageAccount module error.</span>",1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>saveManageAccount module failed.</span>",1);})
    .always(function(){});
}
function validateProfilePasswordSaveManageAccount(){
    var accountValidator = $("#formManageAccount").kendoValidator().data("kendoValidator");

    var status = $(".status");

        if (accountValidator.validate()) {
            checkPasswordOldPassword();
        }
        else {
            status.text("");
        }
}
function checkPasswordOldPassword(){
        $.ajax({
        type: 'POST',
        url: "layout/checkPasswordOldPassword.php",
        data:$("#formManageAccount").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            if(!result['iserror']){
                if ($("#manageOldpassword").val() == "") alert("Please fill your old password");
                else if(result["datas"]["message"]) alert(result["datas"]["message"]);
                else saveManageAccountChangePasswordConfirm();
            }
            else{
                messageBox("<span class='red bold'>checkPasswordOldPassword module error.</span>",1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>checkPasswordOldPassword module failed.</span>",1);})
    .always(function(){});
}
function saveManageAccountChangePasswordConfirm(){
    if ($("#managePassword").val() == $("#manageOldpassword").val()) alert("Please enter a different password");
    else if ($("#managePassword").val() != $("#manageRetypepassword").val()) alert("Passwords didn't match");
    else if ($("#manageRetypepassword").val() == $("#manageOldpassword").val()) alert("Please enter a different password");
    else if ($("#managePassword").val().length < 6) alert("Password must contain a minimum of 6 characters.");
    else if ($("#manageRetypepassword").val().length < 6) alert("Password must contain a minimum of 6 characters.");
    else {
        var element = "<p>Are you sure you want to change your password?</p>";
            element += "<br/><p class='right'>";
            element += "<span class='k-button' onClick='manageAccountCheckPassword();'><i class='fa fa-check'></i> YES</span>";
            element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
            element += "</p>";
        showMask();
        showModal(element);
    }
}
function manageAccountCheckPassword(){
    showModal("Checking new password");
    $.ajax({
        type: 'POST',
        url: "layout/manageAccountCheckPassword.php",
        data:$("#formManageAccount").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            //alert(result);
            if(!result['iserror']){
                if(!result["data"]) {
                    saveManageAccountChangePassword();
                }
                else showModal("You are using default password. Please try something else.");
            }
            else{
                messageBox("<span class='red bold'>manageAccountCheckPassword module error.</span>",1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageAccountCheckPassword module failed.</span>",1);})
    .always(function(){});
}
function saveManageAccountChangePassword(){
    showMask();
    showModal("Saving password...");
    $.ajax({
        type: 'POST',
        url: "layout/saveManageAccountChangePassword.php",
        data:$("#formManageAccount").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            //alert(result);
            if(!result['iserror']){
                hideMask();
                var status = $(".status");
                status.text("Save account successfully").addClass("valid");

                var element = "<p>Password has been changed successfully!</p>";
                    element += "<br/><p class='right'>";
                    element += "<a href='module/logout.php'><span class='k-button'> OK</span></a>";
                    element += "</p>";
                showMask();
                showModal(element);

            }
            else{
                messageBox("<span class='red bold'>saveManageAccountChangePassword module error.</span>",1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>saveManageAccountChangePassword module failed.</span>",1);})
    .always(function(){});
}
function logout(){
    var element = "<p>Are you sure you want to log out?</p>";
        element += "<br/><p class='right'>";
            element += "<a href='module/logout.php'><span class='k-button'><i class='fa fa-check'></i> YES</span></a>";
            element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
        element += "</p>";
        showMask();
        showModal(element);
}

function generatePanel(){
    var mobile = 740;
    var tablet = 841;

    windowWidth  = $(window).width();
    //writeConsole("window width : "+windowWidth);
    if(windowWidth < mobile){
        //MOBILE
        $(".mobile").show();
        $(".desktop").hide();

        $(".panel_con").css("display","block");
        $(".panel_con").css("width","");

        $(".panel_content img").css("height","32px");

        //$(".panel_content").addClass("float_left");
        //$(".panel_title").addClass("float_left");
        //$(".panelSeparator").addClass("clear_both");
    }
    else
    {
        //DESKTOP
        $(".mobile").hide();
        $(".desktop").show();

        //writeConsole($(".general").width())

        $(".panel_con").css("display","inline-block");
        for(x = 1 ; x <= 100 ; x++)
        {
            $(".panel_"+x).css("width",x+"%");
        }
        $(".panel_con").each(function(index,panel){
            $(this).css("width",$(this).width()-11);
            //writeConsole("panel "+$(this)+" width : "+$(this).width());
        });

        $(".panel_content img").css("height","90px");

        $(".panel_content").removeClass("float_left");
        $(".panel_title").removeClass("float_left");
        $(".panelSeparator").removeClass("clear_both");
    }

}

function htmlDecode(value) {
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}
function htmlEncode(value){
    if (value) {
        return $('<div />').text(value).html();
    } else {
        return '';
    }
}

function getHighestZIndex(){
    var highest = -999;
    $('*').each(function() {
        var current = parseInt($(this).css('z-index'), 10);
        if(current && highest < current) highest = current;
    });
    return highest;
}

function downloadFile(fileLocation, fileRename){
    var link = document.createElement("a");
    link.download = fileRename;
    link.href = fileLocation;
    link.click();
}

var isUploadFile = false;
var file = new Array();
function uploadFile(InputFileId,UploadFileFolder){

    var data = "";
    if(!isUploadFile){
        showMask();
        showModal("<p>Memeriksa file..</p>");
        isUploadFile = true;

        var file = document.getElementById(InputFileId).files[0];
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData();
            //alert("OK");
        }
        else{
            showModal("<p>Browser tidak support teknologi 'FormData'. Dihimbau untuk menggunakan Firefox atau Chrome terbaru.</p>");
            isUploadFile = false;
        }

        if (window.FileReader) {
            reader = new FileReader();
            reader.onloadend = function (e) {
                //showUploadedItem(e.target.result);
            };
            reader.readAsDataURL(file);
        }
        else{
            showModal("<p>Browser tidak support teknologi 'FileReader'. Dihimbau untuk menggunakan Firefox atau Chrome terbaru.</p>");
            isUploadFile = false;
        }

        if (formdata) {
            formdata.append("file", file);
            formdata.append("UploadFileFolder", UploadFileFolder);

            $.ajax({
                url: "../Uranus/ajax/universal/uploadFiles.php",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                async: false,
                dataType: "JSON",
                success: function (result) {
                    hideMask();
                    if(!result["iserror"]){
                        if(result["datas"]["message"])alert(result["datas"]["message"]);
                        else{data = result["data"];}
                        isUploadFile = false;
                    }
                }
                ,fail: function(jqXHR, textStatus ){$('#result').append("<p class='retro_red'>Request failed: " + textStatus +"</p>" );}
            })
            .done(function(){})
            .fail(function(){messageBox("<span class='red bold'>Upload Files module failed.</span>",1);})
            .always(function(){});
        }
        else{
            showModal("<p>Browser tidak support teknologi 'FormData'. Dihimbau untuk menggunakan Firefox atau Chrome terbaru.</p>");
            isUploadFile = false;
        }
    }
    else{
        alert("Masih mengunggah...");
    }
    hideMask();
    return data;
}
function camelToSpace (txt) {
    return txt
      .replace(/([^A-Z]*)([A-Z]*)([A-Z])([^A-Z]*)/g, '$1 $2 $3$4')
      .replace(/ +/g, ' ')
}

//function generateReportTRTD(datas) {
//    var innerTable = "<tr><td>Inner Table</td></tr>";
//    return innerTable;
//}

$(document).ready(function(){
    generatePanel();

    $(".kendoDatePicker").kendoDatePicker({format:"yyyy-MM-dd"});
    $(".monthPicker").kendoDatePicker({
        // defines the start view
        start: "year",

        // defines when the calendar should return date
        depth: "year",

        // display month and year in the input
        format: "yyyy",

        // specifies that DateInput is used for masking the input element
        dateInput: true
    });
    $(".kendoYearPicker").kendoDatePicker({
        // Shows the years of the decade
        start: "decade",

        // Shows the years of the decade
        depth: "decade",

        // display month and year in the input
        format: "yyyy",

        // specifies that DateInput is used for masking the input element
        dateInput: true
    });
    $(".kendoTimePicker").kendoTimePicker({format: "HH:mm"});
    $(".kendoDateTimePicker").kendoDateTimePicker({timeFormat: "HH:mm",format: "yyyy-MM-dd HH:mm:ss"});
    $(".kendoComboBox").kendoComboBox({dataTextField: "Text",dataValueField: "Value",filter: "contains",suggest: true});
    $(".kendoMultiSelect").kendoMultiSelect({dataTextField: "Text",dataValueField: "Value",filter: "contains",suggest: true});

    //$.each($(".kendoComboBox"), function(index,kendoComboBox){kendoComboBox.data("kendoComboBox").value("");});

    $(".kendoNumericTextBox").kendoNumericTextBox({min:0,format:"#",step:1});
    $(".kendoNumericTextBoxCurrency").addClass("kendoInputAlignRight");
    $(".kendoNumericTextBoxCurrency").kendoNumericTextBox({min:0,format:"Rp #,0",step:1});
    $(".kendoNumericTextBoxCurrencyDecimal2").kendoNumericTextBox({min:0,format:"Rp #,0.##",step:0.01,round:false});
    $(".kendoNumericTextBoxCurrencyNegative").addClass("kendoInputAlignRight");
    $(".kendoNumericTextBoxCurrencyNegative").kendoNumericTextBox({format:"Rp #,0",step:1});
    $(".kendoNumericTextBoxPercentage").kendoNumericTextBox({min:0,decimals: 2,step:0.01});
    $(".kendoNumericTextBoxDecimal1").kendoNumericTextBox({min:0,decimals: 1,format:"#.#",step:0.1,round:false});
    $(".kendoNumericTextBoxDecimal2").kendoNumericTextBox({min:0,decimals: 2,format:"#.##",step:0.01,round:false});
    $(".kendoNumericTextBoxDecimal3").kendoNumericTextBox({min:0,decimals: 3,format:"#.###",step:0.001,round:false});
    $(".kendoNumericTextBoxDecimal4").kendoNumericTextBox({min:0,decimals: 4,format:"#.####",step:0.0001,round:false});

    $(".police-number").kendoMaskedTextBox({mask: "?? 9999 aaaaaaaaa"});
    $(".vehicle-year").kendoMaskedTextBox({mask: "9999"});
    $(".phone-number").kendoMaskedTextBox({mask: "(9999) 000-00000"});
    $(".mobilephone-number").kendoMaskedTextBox({mask: "0000-0000-0000-0000"});
    $(".cellphone-number").kendoMaskedTextBox({mask: "00000000000000"});
    $(".ktp-number").kendoMaskedTextBox({mask: "0000000000000000"});
    $(".npwp-number").kendoMaskedTextBox({mask: "00.000.000.0-000.000"});
    $(".postal-code").kendoMaskedTextBox({mask: "000000"});
    $(".bank-number").kendoMaskedTextBox({
        mask: "00000000000000000000",
        clearPromptChar: true
    });

    $(".kendoUpload").kendoUpload();
    if(isWarningBeforeunload)$(window).bind("beforeunload", function() { return "Do you really want to close?"; });
    $(window).scroll(function(){
        if($("#drawer_left").css("left") == "0px"){
            //CLOSING
            var width = $("#drawer_left").css("width");
            $("#drawer_left").animate({"left": "-"+width},0);
        }
        if($("#drawer_right").css("right") == "0px"){
            //CLOSING
            var width = $("#drawer_right").css("width");
            $("#drawer_right").animate({"right": "-"+width},0);
        }
        if($("#drawer_rightprofile").css("top") == "46px"){
            //CLOSING
            var height = $("#drawer_rightprofile").css("height");
            $("#drawer_rightprofile").animate({"top": "-"+height},250);
        }
    })
    $(window).resize(function() {
        generatePanel();
    });

    $("*").dblclick(function(e){
        e.preventDefault();
    });
});

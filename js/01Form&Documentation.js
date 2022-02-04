//alert('JS LOADED');
var linkAjax = "ajax/01general/01form&document/";

$(document).ready(function (){
    // openPanelMaster();
    openPanelManageFiles();
    //alert(G_DepartmentId);
});

function closeAll(){
    $(".panelContent").hide();
}

function openPanelSummary(){
    closeAll();
    $('#panelSummary').show();
}

function openPanelManageFiles(){
    closeAll();
    $('#panelManageFiles').show();
    manageFileGetSubjects();
}

function openPanelMaster(){
    closeAll();
    $('#panelMaster').show();
    masterGetGroups();
}

/////*SUMARRY*/////
function getSummary(){
    /*
    $.ajax({
        type: 'POST',
        url: linkAjax+"getSummary.php",
        data:{},
        dataType: "JSON",
        async: true,
        success: function (result) {
            //alert(result);
            if(!result['iserror']){
                $("#summaryTable").data("kendoGrid").dataSource.data(result["datas"]);
                $("#viewBrand").data("kendoComboBox").setDataSource(new kendo.data.DataSource({data:result["datas"]}));
                $("#viewBrand").data("kendoComboBox").value("");
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>getSummary module failed.</span>",1);})
    .always(function(){});

    var element = "<p>Are you sure you want to save this ticket?</p>";
    element += "<br/><p class='right'>";
        element += "<span class='k-button' onClick='createTicketSaveValidation();'><i class='fa fa-check'></i> YES</span>";
        element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
    element += "</p>";
    showMask();
    showModal(element);
    */
}

/////*MANAGE FILES*/////
function manageFileGetSubjects(){
    showMask();
    showModal("Generating subjects...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileGetSubjects.php",
        data:{DepartmentId:G_DepartmentId},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                // $("#manageFileGetDocumentsGroupId").data("kendoComboBox").setDataSource(new kendo.data.DataSource({data:result["datas"]}));
                // $("#manageFileGetDocumentsGroupId").data("kendoComboBox").value("");
                // $("#manageFileGetDocumentsGroupId").data("kendoComboBox").select(-1);
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageFileGetSubjects module failed.</span>",1);})
    .always(function(){});
}

function manageFileGetDocuments(){
    showMask();
    showModal("Searching documents...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileGetDocuments.php",
        data:$("#manageFilesFormGetDocuments").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                var aggregates = new Array();

                $("#manageFilesTableDocuments").data("kendoGrid").dataSource.data(result["datas"]);

                $("#manageFilesTableDocuments").data("kendoGrid").dataSource.group({
                    field: "GroupName",aggregates:aggregates
                });
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageFileGetDocuments module failed.</span>",1);})
    .always(function(){});
}

/////*FILE ADD.MANAGE FILES*/////
function manageFileWindowAddDocumentOpen(){
    showMask();
    showModal("Generating subjects...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileAddDocumentGetSubjects.php",
        data:{DepartmentId:G_DepartmentId},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                $("#manageFileAddDocumentFolderPath").val("");
                $("#manageFileAddDocumentFileName").val("");
                $("#manageFileAddDocumentFilePath").val("");

                $("#manageFileAddDocumentGroupId").data("kendoComboBox").setDataSource(new kendo.data.DataSource({data:result["datas"]}));
                $("#manageFileAddDocumentGroupId").data("kendoComboBox").value("");
                $("#manageFileAddDocumentGroupId").data("kendoComboBox").select(-1);

                $("#manageFileAddDocumentOrder").data("kendoNumericTextBox").value(0);
                $("#manageFileAddDocumentTitle").val("");
                $("#manageFileAddDocumentDescription").val("");

                $("#manageFileWindowAddDocument").data("kendoWindow").open().center();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageFilesGetSubjects module failed.</span>",1);})
    .always(function(){});
}

function manageFileAddDocumentGroupIdChange(e){
    //console.log(e);
    $("#manageFileAddDocumentOrder").data("kendoNumericTextBox").value(0);
    if($("#manageFileAddDocumentGroupId").data("kendoComboBox").select() != -1){
        var GroupId = $("#manageFileAddDocumentGroupId").data("kendoComboBox").value();
        showMask();
        showModal("Generating subjects...");
        $.ajax({
            type: 'POST',
            url: linkAjax+"manageFileAddDocumentGetOrder.php",
            data:{GroupId:GroupId},
            dataType: "JSON",
            async: true,
            success: function (result) {
                hideMask();
                if(!result['iserror']){
                    $("#manageFileAddDocumentOrder").data("kendoNumericTextBox").value(result["data"]);
                }
                else{
                    messageBox(result["warning"],1);
                }
            }
        })
        .done(function(){})
        .fail(function(){messageBox("<span class='red bold'>manageFileAddDocumentGetOrder module failed.</span>",1);})
        .always(function(){});
    };
}

function manageFileAddDocumentValidation(){
    if($("#manageFileAddDocumentGroupId").data("kendoComboBox").select() == -1)alert("Please select subject");
    else if(!$("#manageFileAddDocumentOrder").data("kendoNumericTextBox").value()) alert("Plase input order file");
    else if(!$("#manageFileAddDocumentTitle").val()) alert("Plase input Document Title");
    else{
        showMask();
        showModal("Validating form...");
        $.ajax({
            type: 'POST',
            url: linkAjax+"manageFileAddDocumentValidation.php",
            data:$("#manageFileFormAddDocument").serialize(),
            dataType: "JSON",
            async: true,
            success: function (result) {
                hideMask();
                if(!result['iserror']){
                    if(result["datas"]["message"])alert(result["datas"]["message"]);
                    else manageFileAddDocumentAttachmentValidation();
                }
                else{
                    messageBox(result["warning"],1);
                }
            }
        })
        .done(function(){})
        .fail(function(){messageBox("<span class='red bold'>manageFileAddDocument module failed.</span>",1);})
        .always(function(){});
    }
}
var G_IsUploadFile = false;
var G_Files = new Array();
var G_CountDoneValidate = 0;

function manageFileAddDocumentAttachmentValidation(){
    if(!G_IsUploadFile){
        G_IsUploadFile = true;
        showMask();
        showModal("Validating file(s)...");
        G_Files = new Array();
        $.each(document.getElementById("manageFileAddDocumentFilePath").files,function(key,file){
            //alert(/[^.]+$/.exec(file.name));
            G_Files.push(file);
            /*
            if (/[^.]+$/.exec(file.name) == "xls"){
                G_Files.push(file);
            }
            else{
            }
            */
        });
        if(G_Files.length > 0){
            G_CountDoneValidate = 0;
            manageFileAddDocumentAttachmentUpload();
        }
        else{
            G_IsUploadFile = false;
            showModal("Please insert file!");
        }
    }
    else{
        alert("Still checking, please wait...");
    }
}

function manageFileAddDocumentAttachmentUpload(){
    $.each(G_Files, function(key, file){
        var input = file;
        var formdata = false;
        var reader = false;
        if (window.FormData) {
            formdata = new FormData();
            //alert("OK");
        }
        else{
            showModal("Not support for ajax uploading technology (FormData). Try using latest firefox / chrome browser.");
            G_IsUploadFile = false;
        }

        if (window.FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                //showUploadedItem(e.target.result);
            };
            reader.readAsDataURL(input);
        }
        else{
            showModal("Not support for ajax uploading technology (FileReader). Try using latest firefox / chrome browser.");
            G_IsUploadFile = false;
        }

        if (formdata) {
            formdata.append("file", input);

            $.ajax({
                url: linkAjax+"manageFileAddDocumentAttachmentUpload.php",
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
                        else{
                            $("#manageFileAddDocumentFolderPath").val(result["datas"]["FolderPath"]);
                            $("#manageFileAddDocumentFileName").val(result["datas"]["FileName"]);

                            G_CountDoneValidate++;
                            //alert(G_CountDoneValidate);
                            if(G_CountDoneValidate >= G_Files.length){
                                G_IsUploadFile = false;
                                G_CountDoneValidate = 0;
                                manageFileAddConfirmation();
                            }
                        }
                    }
                }
                ,fail: function(jqXHR, textStatus ){$('#result').append("<p class='retro_red'>Request failed: " + textStatus +"</p>" );}
            })
            .done(function(){})
            .fail(function(){messageBox("<span class='red bold'>editFormandDocumentAddAttachment module failed.</span>",1);})
            .always(function(){});
        }
        else{
            alert("Not support for ajax uploading technology (FormData). Try using firefox browser.");
            hideMask();
            G_IsUploadFile = false;
        }
    });
}

function manageFileAddConfirmation(){
    var element = "";
        element = "<p>Are you sure you want to add this file ?</p><br/>";
        element += "<p class='right'>";
            element += "<span class='k-button' onClick='manageFileAddDocument();'><i class='fa fa-save'></i> Save</span>";
            element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> Cancel</span>";
        element += "</p>";
    showMask();
    showModal(element);
}

function manageFileAddDocument(){
    $("#manageFileWindowAddDocument").data("kendoWindow").close();
    showMask();
    showModal("Saving new document...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileAddDocument.php",
        data:$("#manageFileFormAddDocument").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                manageFileGetDocuments();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageFileAddDocument module failed.</span>",1);})
    .always(function(){});
}

var G_IsUploadFile = false;
var files = new Array();
var G_CountDoneValidate = 0;

function manageFileAddDocumentConfirmCheckFile(){

    if(!G_IsUploadFile){
        G_IsUploadFile = true;
        showModal("<p>Checking file(s)...</p>");
        files = new Array();
        $.each(document.getElementById("manageFileAddDocumentFilePath").files,function(key,file){
            //alert(/[^.]+$/.exec(file.name));
            files.push(file);
            /*
            if (/[^.]+$/.exec(file.name) == "xls"){
                files.push(file);
            }
            else{
            }
            */
        });

        if(files.length > 0){
            G_CountDoneValidate = 0;

            alert("checkfile done...");
            // editFormandDocumentAddAttachmentUpload();
        }
        else{
            G_IsUploadFile = false;
            showModal("<p>Please upload minimum 1 file</p>");
            $("#manageFileWindowAddDocument").data("kendoWindow").open().center();
        }
    }
    else{
        alert("Still checking, please wait...");
    }
}

/////*FILE EDIT MANAGE FILES*/////
function manageFileWindowEditDocumentOpen(Id){
    showMask();
    showModal("Getting document...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileGetDocument.php",
        data:{Id:Id,DepartmentId:DEPARTMENTID},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                $("#manageFileEditDocumentId").val(Id);

                $("#manageFileEditDocumentFolderPath").val("");
                $("#manageFileEditDocumentFileName").val("");
                $("#manageFileEditDocumentFilePath").val("");

                $("#manageFileEditDocumentFileIcon").html("");
                var element ="";
                element = "<a href='"+result["data"]["FolderPath"]+"/"+result["data"]["FileName"]+"'>";
                    element += "<i title='"+result["data"]['Description']+"' class='fa "+result["data"]["Icon"]+" fa-2x'></i>";
                element += "</a>";
                $("#manageFileEditDocumentFileIcon").html(element);

                $("#manageFileEditDocumentOrder").data("kendoNumericTextBox").value(result["data"]["Order"]);
                $("#manageFileEditDocumentTitle").val(result["data"]["Title"]);
                $("#manageFileEditDocumentDescription").val(result["data"]["Description"]);
                $("#manageFileEditDocumentIsEnable").data("kendoComboBox").value(result["data"]["IsEnable"]);

                $("#manageFileEditDocumentGroupId").data("kendoComboBox").setDataSource(new kendo.data.DataSource({data:result["datas"]["Subjects"]}));
                $("#manageFileEditDocumentGroupId").data("kendoComboBox").value(result["data"]["GroupId"]);

                $('#manageFileWindowEditDocument').data('kendoWindow').center().open();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageFileGetDocument module failed.</span>",1);})
    .always(function(){});
}

function manageFileEditDocumentValidation(){
    if($("#manageFileEditDocumentGroupId").data("kendoComboBox").select() == -1)alert("Please select subject");
    else if(!$("#manageFileEditDocumentOrder").data("kendoNumericTextBox").value())alert("Please insert order");
    else if($("#manageFileEditDocumentTitle").val() == "")alert("Please insert title");
    else if($("#manageFileEditDocumentIsEnable").data("kendoComboBox").select() == -1)alert("Please select status");
    else{
        showMask();
        showModal("Validating document...");
        $.ajax({
            type: 'POST',
            url: linkAjax+"manageFileEditDocumentValidation.php",
            data:$("#manageFileFormEditDocument").serialize(),
            dataType: "JSON",
            async: true,
            success: function (result) {
                hideMask();
                if(!result['iserror']){
                    if(result["datas"]["message"])alert(result["datas"]["message"]);
                    else manageFileEditDocumentAttachmentValidation();
                }
                else{
                    messageBox(result["warning"],1);
                }
            }
        })
        .done(function(){})
        .fail(function(){messageBox("<span class='red bold'>manageFileEditDocument module failed.</span>",1);})
        .always(function(){});
    }
}

var G_MFEDIsUploadFile = false;
var G_MFEDFiles = new Array();
var G_MFEDCountDoneValidate = 0;

function manageFileEditDocumentAttachmentValidation(){
    if(!G_MFEDIsUploadFile){
        G_MFEDIsUploadFile = true;
        showMask();
        showModal("Validating file(s)...");
        G_MFEDFiles = new Array();
        $.each(document.getElementById("manageFileEditDocumentFilePath").files,function(key,file){
            //alert(/[^.]+$/.exec(file.name));
            G_MFEDFiles.push(file);
        });
        if(G_MFEDFiles.length > 0){
            G_MFEDCountDoneValidate = 0;
            manageFileEditDocumentAttachmentUpload();
        }
        else{
            G_MFEDIsUploadFile = false;
            manageFileEditDocumentConfirmation();
        }
    }
    else{
        alert("Still checking, please wait...");
    }
}

function manageFileEditDocumentAttachmentUpload(){
    $.each(G_MFEDFiles, function(key, file){
        var input = file;
        var formdata = false;
        var reader = false;
        if (window.FormData) {
            formdata = new FormData();
        }
        else{
            showModal("Not support for ajax uploading technology (FormData). Try using latest firefox / chrome browser.");
            G_MFEDIsUploadFile = false;
        }

        if (window.FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                //showUploadedItem(e.target.result);
            };
            reader.readAsDataURL(input);
        }
        else{
            showModal("Not support for ajax uploading technology (FileReader). Try using latest firefox / chrome browser.");
            G_MFEDIsUploadFile = false;
        }

        if (formdata) {
            formdata.append("file", input);

            $.ajax({
                url: linkAjax+"manageFileEditDocumentAttachmentUpload.php",
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
                        else{
                            $("#manageFileEditDocumentFolderPath").val(result["datas"]["FolderPath"]);
                            $("#manageFileEditDocumentFileName").val(result["datas"]["FileName"]);

                            G_MFEDCountDoneValidate++;
                            //alert(G_CountDoneValidate);
                            if(G_MFEDCountDoneValidate >= G_MFEDFiles.length){
                                G_MFEDIsUploadFile = false;
                                G_MFEDCountDoneValidate = 0;
                                manageFileEditDocumentConfirmation();
                            }
                        }
                    }
                }
                ,fail: function(jqXHR, textStatus ){$('#result').append("<p class='retro_red'>Request failed: " + textStatus +"</p>" );}
            })
            .done(function(){})
            .fail(function(){messageBox("<span class='red bold'>editFormandDocumentAttachment module failed.</span>",1);})
            .always(function(){});
        }
        else{
            alert("Not support for ajax uploading technology (FormData). Try using firefox browser.");
            hideMask();
            G_IsUploadFile = false;
        }
    });
}

function manageFileEditDocumentConfirmation(){
    var element = "<p>Are you sure you want to edit this document?</p>";
    element += "<br/><p class='right'>";
        element += "<span class='k-button' onClick='manageFileEditDocument();'><i class='fa fa-check'></i> YES</span>";
        element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
    element += "</p>";
    showMask();
    showModal(element);
}

function manageFileEditDocument(){
    $('#manageFileWindowEditDocument').data('kendoWindow').close();
    showMask();
    showModal("Saving document...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileEditDocument.php",
        data:$("#manageFileFormEditDocument").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                manageFileGetDocuments();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>manageFileEditDocument module failed.</span>",1);})
    .always(function(){});
}

/////* MASTER */////
function masterGetGroups(){
    showMask();
    showModal("Loading..");
    $.ajax({
        type: 'POST',
        url: linkAjax+"masterGetGroups.php",
        data:{DepartmentId:G_DepartmentId},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                $("#masterTableGroups").data("kendoGrid").dataSource.data(result["datas"]);
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>masterGetGroups module failed.</span>",1);})
    .always(function(){});
}

/////*ADD MASTER GROUP*/////
function masterWindowAddGroupOpen(){
    showMask();
    showModal("Preparing form...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileAddMasterGetSubjects.php",
        data:{},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                $("#masterAddGroupName").val("");
                $("#masterAddGroupOrder").data("kendoNumericTextBox").value(0);

                $("#masterWindowAddGroup").data("kendoWindow").open().center();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>masterGetGroups module failed.</span>",1);})
    .always(function(){});
}

function masterAddGroupValidation(){
    if(!$("#masterAddGroupName").val())alert("Please input subject name.");
    else if(!$("#masterAddGroupOrder").data("kendoNumericTextBox").value()) alert("Please input order subject");
    else{
        showMask();
        showModal("Validation...");
        $.ajax({
            type: 'POST',
            url: linkAjax+"masterAddGroupValidation.php",
            data:$("#masterFormAddGroup").serialize(),
            dataType: "JSON",
            async: true,
            success: function (result) {
                hideMask();
                if(!result['iserror']){
                    if(result["datas"]["message"]) alert(result["datas"]["message"]);
                    else masterAddGroupConfirmation();
                }
                else{
                    messageBox(result["warning"],1);
                }
            }
        })
        .done(function(){})
        .fail(function(){messageBox("<span class='red bold'>masterAddGroupValidation module failed.</span>",1);})
        .always(function(){});
    }
}

function masterAddGroupConfirmation(){
    var element = "<p>Are you sure you want to add this subject?</p>";
    element += "<br/><p class='right'>";
        element += "<span class='k-button' onClick='masterAddGroup();'><i class='fa fa-check'></i> YES</span>";
        element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
    element += "</p>";
    showMask();
    showModal(element);
}

function masterAddGroup(){
    $("#masterWindowAddGroup").data("kendoWindow").close();
    showMask();
    showModal("Saving new subject...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"masterAddGroup.php",
        data:$("#masterFormAddGroup").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                masterGetGroups();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>masterAddGroup module failed.</span>",1);})
    .always(function(){});
}

/////*EDIT MASTER GROUP*/////
function masterWindowEditGroupOpen(UniversalFileGroupId){
    showMask();
    showModal("Preparing form...");
        $.ajax({
        type: 'POST',
        url: linkAjax+"manageFileEditMasterGetSubjects.php",
        data:{UniversalFileGroupId:UniversalFileGroupId, DepartmentId:DEPARTMENTID},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                $("#masterEditGroupId").val(UniversalFileGroupId);

                $("#masterEditGroupName").val(result["data"]["Subject"]);
                $("#masterEditGroupOrder").data("kendoNumericTextBox").value(result["data"]["Order"]);
                $("#masterEditGroupIsEnable").data("kendoComboBox").value(result["data"]["IsEnable"]);

                $("#masterWindowEditGroup").data("kendoWindow").open().center();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>masterGetGroups module failed.</span>",1);})
    .always(function(){});
}

function masterEditGroupValidation(){
    if(!$("#masterEditGroupName").val())alert("Please input subject name.");
    else if(!$("#masterEditGroupOrder").data("kendoNumericTextBox").value()) alert("Plase input order subject");
    else if($("#masterEditGroupIsEnable").data("kendoComboBox").select() == -1)alert("Please select status");
    else{
        showMask();
        showModal("Validation...");
        $.ajax({
            type: 'POST',
            url: linkAjax+"masterEditGroupValidation.php",
            // data:{Name:$("#masterEditGroupName").val()},
            data:$("#masterFormEditGroup").serialize(),
            dataType: "JSON",
            async: true,
            success: function (result) {
                hideMask();
                if(!result['iserror']){
                    if(result["datas"]["message"])alert(result["datas"]["message"]);
                    else masterEditGroupConfirmation();
                }
                else{
                    messageBox(result["warning"],1);
                }
            }
        })
        .done(function(){})
        .fail(function(){messageBox("<span class='red bold'>masterEditGroup module failed.</span>",1);})
        .always(function(){});
    }
}

function masterEditGroupConfirmation(){
    var element = "<p>Are you sure you want to Edit this subject?</p>";
    element += "<br/><p class='right'>";
        element += "<span class='k-button' onClick='masterEditGroup();'><i class='fa fa-check'></i> YES</span>";
        element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> NO</span>";
    element += "</p>";
    showMask();
    showModal(element);
}

function masterEditGroup(){
    $("#masterWindowEditGroup").data("kendoWindow").close();
    showMask();
    showModal("Saving Edit subject...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"masterEditGroup.php",
        data:$("#masterFormEditGroup").serialize(),
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                masterGetGroups();
            }
            else{
                messageBox(result["warning"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>masterEditGroup module failed.</span>",1);})
    .always(function(){});
}


// function closeAll(){
//     $("#summary").hide();
//     $("#view").hide();
//     $("#add").hide();
// }

// //SUMARRY
// function getSummary(){
//     /*
//     $.ajax({
//         type: 'POST',
//         url: "ajax/3it/14bpp/getSummary.php",
//         data:{},
//         dataType: "JSON",
//         async: true,
//         success: function (result) {
//             //alert(result);
//             if(!result['iserror']){
//                 $("#summaryTable").data("kendoGrid").dataSource.data(result["items"]);
//             }
//             else{
//                 messageBox("<span class='red bold'>getSummary module error.</span>",1);
//             }
//         }
//     })
//     .done(function(){})
//     .fail(function(){messageBox("<span class='red bold'>getSummary module failed.</span>",1);})
//     .always(function(){});
//     */
// }

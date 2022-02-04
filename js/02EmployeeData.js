alert('JS LOADED');
var linkAjax = "ajax/6workshop/42plutususer/";

$(document).ready(function (){
    openPanelSummary();
});

function closeAll(){
    $(".panelContent").hide();
}
function openPanelSummary(){
    closeAll();
    $('#panelSummary').show();
}
function openPanelView(){
    closeAll();
    $('#panelView').show();
}
function openPanelAdd(){
    closeAll();
    $('#panelAdd').show();
}

//SUMARRY
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
//alert('JS LOADED');
var linkAjax = "../ajax/";

$(document).ready(function (){
    openPanelView();
});

function closeAll(){
    $(".panelContent").hide();
}
function openPanelSummary(){
    closeAll();$('#openPanelView').show();
}
function openPanelView(){
    closeAll();$('#panelView').show();
    // getFilterViewPOSes();
}
function openPanelAdd(){
    closeAll();$('#panelAdd').show();
    // getFilterViewPOSes();
}
//TEMPLATE
function Confirm() {
    var element = "";
        element = "<p>Are you sure you want to update the data ?</p><br/>";
        element += "<p class='right'>";
            element += "<span class='k-button' onClick='viewEditCustomer();'><i class='fa fa-save'></i> Save</span>";
            element += " <span class='k-button' onClick='hideMask();'><i class='fa fa-times'></i> Cancel</span>";
        element += "</p>";
    showMask();
    showModal(element);
}

function PanelSomeFunction(){
    showMask();
    showModal("Processing...");
    $.ajax({
        type: 'POST',
        url: linkAjax+"AjaxFileName.php",
        data:{key:value},
        dataType: "JSON",
        async: true,
        success: function (result) {
            hideMask();
            if(!result['iserror']){
                /*
                DO SOMETHING
                */
            }
            else{
                if(result["errorCount"])messageBox(result["warning"],1);
                else messageBox(result["errorMessage"],1);
            }
        }
    })
    .done(function(){})
    .fail(function(){messageBox("<span class='red bold'>AjaxFileName module failed.</span>",1);})
    .always(function(){});
}

function windowAddOpen(){
    $("#windowAdd").data("kendoWindow").open().maximize();
}
//TEMPLATE

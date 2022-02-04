alert('JS LOADED');
var linkAjax = "ajax/1hrd/1datakaryawan/";

$(document).ready(function (){
    openTabSummary();
});

function closeAll(){
    $("#tabSummary").hide();
    $("#tabEmployeeList").hide();
    $("#tabAddEmployee").hide();
}
function openTabEmployeeList(){
    closeAll();$('#tabSummary').show();
}
function openTabAddEmployee(){
    closeAll();$('#tabEmployeeList').show();
}
function openTabAdd(){
    closeAll();$('#tabAddEmployee').show();
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
                $("#summaryTable").data("kendoGrid").dataSource.data(result["items"]);
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
    */
}
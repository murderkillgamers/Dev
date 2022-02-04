<?php
?>
<div class="panel_con panel_20">
    <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>" onClick="openTabSummary();">
        <div class="panel_content">
            <h1><i class="fa fa-tachometer fa-3x"></i></h1>
        </div>    
        <div class="panel_title">
            <p>Rekap</p>
        </div>
    </div>    
</div><!--
--><div class="panel_con panel_20">
    <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>" onClick="openTabEmployeeList();">
        <div class="panel_content">
            <h1><i class="fa fa-user fa-3x"></i></h1>
        </div>    
        <div class="panel_title">
            <p>List Karyawan</p>
        </div>
    </div>    
</div><!--
--><div class="panel_con panel_20">
    <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>" onClick="openTabAddEmployee();">
        <div class="panel_content">
            <h1><i class="fa fa-plus fa-3x"></i></h1>
        </div>    
        <div class="panel_title">
            <p>Tambah Karyawan</p>
        </div>
    </div>    
</div>

<hr/>

<div style="margin:20px;">
    <div id="tabSummary" class="hidden">
        <h3>Rekap</h3>
        <div id="summaryTable"></div>   
        <script>
            $(document).ready(function (){
                /*
                $("#summaryTable").kendoGrid({
                    height: 500,
                    sortable: true,
                    resizable: true,
                    columns: [
                        {title: " ",template:"<span class='k-button' title='viewHistory' onClick='viewHistory(#: Id #,&quot;#: Brand # #: Item #&quot;);'><i class='fa fa-eye'></i></span>",width: 56},
                        {field: "",title: "Category",width: 250}
                    ]
                });
                getSummary();
                */
            });
        </script>
    </div>
    
    <div id="tabEmployeeList">
        <h3>List Karyawan</h3>
    </div>
    
    <div id="tabAddEmployee">
        <h3>Tambah Karyawan</h3>     
        <p class='right'>
            <span class='k-button' onClick=''><i class='fa fa-save'></i> SAVE</span>
        </p>
    </div>
</div>
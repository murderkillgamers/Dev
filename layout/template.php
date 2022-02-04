<?php
?>
<div class="panel_con panel_20">
    <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>" onClick="openPanelSummary();">
        <div class="panel_content">
            <h1><i class="fa fa-tachometer fa-3x"></i></h1>
        </div>
        <div class="panel_title">
            <p>Summary</p>
        </div>
    </div>
</div><!--
--><div class="panel_con panel_20">
    <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>" onClick="openPanelView();">
        <div class="panel_content">
            <h1><i class="fa fa-file-o fa-3x"></i></h1>
        </div>
        <div class="panel_title">
            <p>View</p>
        </div>
    </div>
</div><!--
--><div class="panel_con panel_20">
    <div class="panel metroui_<?php echo rand($metroUiColorMin,$metroUiColorMax) ;?>" onClick="openPanelAdd();">
        <div class="panel_content">
            <h1><i class="fa fa-plus fa-3x"></i></h1>
        </div>
        <div class="panel_title">
            <p>Add</p>
        </div>
    </div>
</div>

<hr/>

<div style="margin:20px;">
    <div id="panelSummary" class="panelContent">
        <h3>Summary</h3>
        <div id="summaryTableSummaries"></div>
        <p class='right'>
            <span class='k-button' onClick=''><i class='fa fa-save'></i> SAVE</span>
        </p>
        <script>
            $(document).ready(function (){
                /*
                function detailTable(e){
                    $("<div/>").appendTo(e.detailCell).kendoGrid({
                        dataSource: e.data.Items,
                        scrollable: false,
                        sortable: true,
                        columns: [
                            {title: " ",template:"<p class='center'><span class='k-button' title='getItems' onClick='getItems(#: Id #);'><i class='fa fa-eye'></i></span></p>",width: 50},
                            {field: "",title: "Total", width: 120, format:"Rp {0:n0}"}
                        ],
                        dataBound: function(e) {
                            $.each(this.dataSource.data(), function (i, row) {
                                if (row.hasOwnProperty('RowClass')) {
                                    $('tr[data-uid="' + row.uid + '"]').addClass(row.RowClass);
                                }
                            });
                        }
                    });
                }
                $("#Table").kendoGrid({
                    toolbar: ["excel"],
                    excel: {fileName: "DOByMonth.xlsx"},
                    height: 500,
                    sortable: true,
                    resizable: true,
                    filterable: true,
                    columnMenu: true,
                    columns: [
                        {title: " ",template:"<p class='center'><span class='k-button' title='getParrent' onClick='getParrent(#: Id #);'><i class='fa fa-eye'></i></span></p>",width: 50},
                        {field: "",title: "Total", width: 120, format:"Rp {0:n0}", attributes:{"class":"right"}}
                    ],
                    detailInit : detailTable,
                    dataSource: { pageSize: 50 },
                    pageable: {refresh: true,pageSizes: true},
                    dataBound: function(e) {
                        this.expandRow(this.tbody.find("tr.k-master-row"));
                        this.expandRow(this.tbody.find("tr.k-master-row").first());
                        $.each(this.dataSource.data(), function (i, row) {
                            if (row.hasOwnProperty('RowClass')) {
                                $('tr[data-uid="' + row.uid + '"]').addClass(row.RowClass);
                            }
                        });
                    }
                });
                $("#Window").kendoWindow({
                    width: "600px",
                    title:"Window Title",
                    visible: false,
                    actions: ["Pin","Minimize","Maximize","Close"]
                });
                */
            });
        </script>
    </div>

    <div id="panelView" class="panelContent">
        <h3>View</h3>
    </div>

    <div id="panelAdd" class="panelContent">
        <h3>Add</h3>
    </div>
</div>

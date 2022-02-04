<?php
function createKendoGrid($kendoGrid_syntax){
    $header = $kendoGrid_syntax['datasource']['header'];
    $datas = $kendoGrid_syntax['datasource']['data'];
    
    $script = "<script>";    
    $script .= "var data = [];";
    for($c1 = 0 ; $c1 < count($datas) ; $c1++)
    {
        $data = $datas[$c1];
        $script .= "data.push({";
        for($c2 = 0 ; $c2 < count($header) ; $c2++)
        {
            $script .= $header[$c2].":\"".$data[$c2]."\",";
        }
        $script .= "Id:".($c1+1);
        $script .= "});\n";
    }
    $script .= "var column = [];";
    for($c1 = 0 ; $c1 < count($header) ; $c1++)
    {
        $script .= "column.push({";
        $script .= "field:\"".$header[$c1]."\",";
        $script .= "width:".(strlen($header[$c1]) * 15 < 90 ? 90 : strlen($header[$c1]) * 15).",";
        $script .= "title:\"".str_replace("_", " ", $header[$c1])."\"";
        $script .= "});\n";
    }    
    $script .= "$(document).ready(function(){";
    $script .= "$(\"#".$kendoGrid_syntax['gridid']."\").kendoGrid({";
    $script .= "dataSource: {";
    $script .= "data: data";
    $script .= "},";
    if(isset($kendoGrid_syntax['height'])) $script .= "height: ".$kendoGrid_syntax['height'].",\n";
    if(isset($kendoGrid_syntax['width'])) $script .= "width: ".$kendoGrid_syntax['width'].",\n";
    if(isset($kendoGrid_syntax['groupable'])) $script .= "groupable: ".$kendoGrid_syntax['groupable'].",\n";
    if(isset($kendoGrid_syntax['reorderable'])) $script .= "reorderable: ".$kendoGrid_syntax['reorderable'].",\n";
    if(isset($kendoGrid_syntax['resizable'])) $script .= "resizable: ".$kendoGrid_syntax['resizable'].",\n";
    if(isset($kendoGrid_syntax['scrollable'])) $script .= "scrollable: ".$kendoGrid_syntax['scrollable'].",\n";
    if(isset($kendoGrid_syntax['sortable'])) $script .= "sortable: ".$kendoGrid_syntax['sortable'].",\n";
    if(isset($kendoGrid_syntax['pageable'])) $script .= "pageable: ".$kendoGrid_syntax['pageable'].",\n";
    if(isset($kendoGrid_syntax['columnMenu'])) $script .= "columnMenu: ".$kendoGrid_syntax['columnMenu'].",\n";
    if(isset($kendoGrid_syntax['pageable']))
    {
        if($kendoGrid_syntax['pageable'] == 'true')
        {
            $script .= "pageable: {";
            if(isset($kendoGrid_syntax['pageable.input'])) $script .= "input: ".$kendoGrid_syntax['pageable.input'].",\n";
            if(isset($kendoGrid_syntax['pageable.pageSize'])) $script .= "pageSize: ".$kendoGrid_syntax['pageable.pageSize'].",\n";
            if(isset($kendoGrid_syntax['pageable.pageSizes'])) $script .= "pageSizes: ".$kendoGrid_syntax['pageable.pageSizes'].",\n";
            if(isset($kendoGrid_syntax['pageable.refresh'])) $script .= "refresh: ".$kendoGrid_syntax['pageable.refresh'].",\n";
            if(isset($kendoGrid_syntax['pageable.info'])) $script .= "info: ".$kendoGrid_syntax['pageable.info'].",\n";
            $script .= "},";
        }
    }    
    $script .= "columns: column";
    $script .= "});";
    $script .= "});";
    $script .= "</script>";
    return $script;
}

function createKendoPie($kendoGrid_syntax){
    $datas = $kendoGrid_syntax['data'];
    
    $script = "<script>";    
    $script .= "var data = [];";
    foreach($datas AS $data)
    {
        $script .= "data.push({category:\"".$data['category']."\", value:".$data['value'].", color:\"".$data['color']."\"});";
    }    
    
    $script .= "$(document).ready(function(){";
    $script .= "$(\"#".$kendoGrid_syntax['gridid']."\").kendoChart({";
    $script .= "chartArea: {";
        if(isset($kendoGrid_syntax['chartArea']['width'])) $script .= "width: ".$kendoGrid_syntax['chartArea']['width'].",";
        if(isset($kendoGrid_syntax['chartArea']['height'])) $script .= "height: ".$kendoGrid_syntax['chartArea']['height'].",";
        if(isset($kendoGrid_syntax['chartArea']['background'])) $script .= "background: \"".$kendoGrid_syntax['chartArea']['background']."\"";
    $script .= "},";
    $script .= "title: {";
        if(isset($kendoGrid_syntax['title']['position'])) $script .= "position: \"".$kendoGrid_syntax['title']['position']."\",";
        if(isset($kendoGrid_syntax['title']['text'])) $script .= "text: \"".$kendoGrid_syntax['title']['text']."\"";
    $script .= "},";
    $script .= "legend: {";
        if(isset($kendoGrid_syntax['legend']['position'])) $script .= "position: \"".$kendoGrid_syntax['legend']['position']."\"";
    $script .= "},";
    $script .= "seriesDefaults: {";
        $script .= "labels: {";
            if(isset($kendoGrid_syntax['seriesDefaults']['labels']['visible'])) $script .= "visible: ".$kendoGrid_syntax['seriesDefaults']['labels']['visible'].",";
            if(isset($kendoGrid_syntax['seriesDefaults']['labels']['background'])) $script .= "background: \"".$kendoGrid_syntax['seriesDefaults']['labels']['background']."\",";
            //$script .= "template: '#= category #: #= kendo.format(\'{0:n0}\', value) # unit (#= kendo.format(\'{0:p1}\', percentage) #)'";
            if(isset($kendoGrid_syntax['seriesDefaults']['labels']['template'])) $script .= "template: \"".$kendoGrid_syntax['seriesDefaults']['labels']['template']."\"";
        $script .= "}";
    $script .= "},";
    $script .= "series: [{";
        $script .= "type: 'pie',";
        $script .= "data: data,";
        if(isset($kendoGrid_syntax['series']['startAngle'])) $script .= "startAngle: ".$kendoGrid_syntax['series']['startAngle']."";
    $script .= "}],";
    $script .= "tooltip: {";
        if(isset($kendoGrid_syntax['tooltip']['visible'])) $script .= "visible: ".$kendoGrid_syntax['tooltip']['visible'].",";
        if(isset($kendoGrid_syntax['tooltip']['format'])) $script .= "format: \"".$kendoGrid_syntax['tooltip']['format']."\"";
    $script .= "}";
    
    $script .= "});";
    $script .= "});";
    $script .= "</script>";
    return $script;
}

function createKendoBarChart($kendoBarChart_syntax){
    /* USES EX
    $datas = array(
        array(
            'name' => "India",
            'data' => array(3.907, 7.943, 7.848, 9.284, 9.263, 9.801, 3.890, 8.238, 9.552, 6.855),
            'color' => "#ff0000",
            'stack' => "Asia"
        ),
        array(
            'name' => "German",
            'data' => array(4.743, 7.295, 7.175, 6.376, 8.153, 8.535, 5.247, -7.832, 4.3, 4.3),
            'color' => "#00ff00",
            'stack' => "Europe"
        ),
        array(
            'name' => "Rusia",
            'data' => array(0.010, -0.375, 1.161, 0.684, 3.7, 3.269, 1.083, -5.127, 3.690, 2.995),
            'color' => "#0000ff",
            'stack' => "Europe"
        ),
        array(
            'name' => "World",
            'data' => array(1.988, 2.733, 3.994, 3.464, 4.001, 3.939, 1.333, -2.245, 4.339, 2.727),
            'color' => "#ffff00",
            'stack' => "World"
        )
    );
    $categories = array(2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011);

    $kendoBarChart_syntax = array(
        "data" => $datas,
        "categoryAxis" => array(
            "categories" => $categories,
            "line" => array(
                "visible" => "false"
            ),
            "majorGridLines" => array(
                "visible" => "false"
            ),
            "labels" => array(
                "padding" => "top: 0"
            )
        ),
        "title" => array(
            "text" => "Gross domestic product growth /GDP annual %/"
        ),
        "legend" => array(
            "visible" => "true",
            "position" => "top"
        ),
        "seriesDefaults" => array(
            "type" => "column",
            "stack" => "false"
        ),
        "valueAxis" => array(
            "labels" => array(
                "format" => "{0}%"
            ),
            "line" => array(
                "visible" => "false"
            ),
            "minorGridLines" => array(
                "visible" => "false"
            ),
            "axisCrossingValue" => "0"
        ),
        "tooltip" => array(
            "visible" => "true",
            "format" => "{0:p2}%",
            "template" => "#= series.stack # (#= series.name #): #= value #%",
        )
    );
    $script = createKendoBarChart($kendoBarChart_syntax); 
    */
    $datas = $kendoBarChart_syntax['data'];
    $categories = $kendoBarChart_syntax['categoryAxis']['categories'];
    
    $script = "<script>";    
    $script .= "var data = [];";
    foreach($datas AS $data)
    {        
        $script .= "data.push({";
        $script .= "name:\"".$data['name']."\",";
        $script .= "color:\"".$data['color']."\",";
        $script .= "stack:\"".$data['stack']."\",";
        
        if(isset($data['percentage']))
        {
            $script .= "percentage:[";        
            $data_string = "";
            foreach($data['percentage'] AS $item)
            {
                $data_string .= $data_string == "" ? "" : ",";
                $data_string .= $item;
            }
            $script .= $data_string;        
            $script .= "],";
        }            
        
        $script .= "data:[";        
        $data_string = "";
        foreach($data['data'] AS $item)
        {
            $data_string .= $data_string == "" ? "" : ",";
            $data_string .= $item;
        }
        $script .= $data_string;        
        $script .= "]";
        
        $script .= "});";
    }
    
    $script .= "var categories = [";
    
    $category_string = "";
    foreach($categories AS $category)
    {
        $category_string .= $category_string == "" ? "" : ",";
        $category_string .= "'".$category."'";
    }
    $script .= $category_string;
    
    $script .= "];";
    
    $script .= "$(document).ready(function(){";
    $script .= "$(\"#".$kendoBarChart_syntax['divid']."\").kendoChart({";
    $script .= "series: data,";
    $script .= "categoryAxis: {";
        $script .= "categories: categories,";
        $script .= "line: {";
            if(isset($kendoBarChart_syntax['categoryAxis']['line']['visible'])) $script .= "visible: ".$kendoBarChart_syntax['categoryAxis']['line']['visible'].",";
        $script .= "},";
        $script .= "majorGridLines: {";
            if(isset($kendoBarChart_syntax['categoryAxis']['majorGridLines']['visible'])) $script .= "visible: ".$kendoBarChart_syntax['categoryAxis']['majorGridLines']['visible'].",";
        $script .= "},";
        $script .= "labels: {";
            if(isset($kendoBarChart_syntax['categoryAxis']['labels']['padding'])) $script .= "padding: \"".$kendoBarChart_syntax['categoryAxis']['labels']['padding']."\",";
        $script .= "}";
    $script .= "},";
    $script .= "chartArea: {";
        if(isset($kendoBarChart_syntax['chartArea']['width'])) $script .= "width: ".$kendoBarChart_syntax['chartArea']['width'].",";
        if(isset($kendoBarChart_syntax['chartArea']['height'])) $script .= "height: ".$kendoBarChart_syntax['chartArea']['height'].",";
        if(isset($kendoBarChart_syntax['chartArea']['background'])) $script .= "background: \"".$kendoBarChart_syntax['chartArea']['background']."\",";
    $script .= "},";
    $script .= "title: {";
        if(isset($kendoBarChart_syntax['title']['text'])) $script .= "text: \"".$kendoBarChart_syntax['title']['text']."\",";
    $script .= "},";
    $script .= "legend: {";
        if(isset($kendoBarChart_syntax['legend']['visible'])) $script .= "visible: ".$kendoBarChart_syntax['legend']['visible'].",";
        if(isset($kendoBarChart_syntax['legend']['position'])) $script .= "position: \"".$kendoBarChart_syntax['legend']['position']."\",";
    $script .= "},";
    $script .= "seriesDefaults: {";
        if(isset($kendoBarChart_syntax['seriesDefaults']['type'])) $script .= "type: \"".$kendoBarChart_syntax['seriesDefaults']['type']."\",";    
        if(isset($kendoBarChart_syntax['seriesDefaults']['stack'])) $script .= "stack: ".$kendoBarChart_syntax['seriesDefaults']['stack'].",";
    $script .= "},";
    $script .= "valueAxis: {";
        $script .= "labels: {";
            if(isset($kendoBarChart_syntax['valueAxis']['labels']['format'])) $script .= "format: \"".$kendoBarChart_syntax['valueAxis']['labels']['format']."\",";
        $script .= "},";
        $script .= "line: {";
            if(isset($kendoBarChart_syntax['valueAxis']['line']['visible'])) $script .= "visible: ".$kendoBarChart_syntax['valueAxis']['line']['visible'].",";
        $script .= "},";
        $script .= "minorGridLines: {";
            if(isset($kendoBarChart_syntax['valueAxis']['minorGridLines']['visible'])) $script .= "visible: ".$kendoBarChart_syntax['valueAxis']['minorGridLines']['visible'].",";
        $script .= "},";
        if(isset($kendoBarChart_syntax['valueAxis']['axisCrossingValue'])) $script .= "axisCrossingValue: ".$kendoBarChart_syntax['valueAxis']['axisCrossingValue'].",";
    $script .= "},";
    $script .= "tooltip: {";
        if(isset($kendoBarChart_syntax['tooltip']['visible'])) $script .= "visible: ".$kendoBarChart_syntax['tooltip']['visible'].",";
        if(isset($kendoBarChart_syntax['tooltip']['format'])) $script .= "format: \"".$kendoBarChart_syntax['tooltip']['format']."\",";    
        if(isset($kendoBarChart_syntax['tooltip']['template'])) $script .= "template: \"".$kendoBarChart_syntax['tooltip']['template']."\",";
    $script .= "}";
    $script .= "});";   
    $script .= "});";
    
    $script .= "</script>";
    
    
    return $script;
}

function createKendoLineChart($kendoLineChart_syntax){
    $datas = $kendoLineChart_syntax['data'];
    $categories = $kendoLineChart_syntax['categoryAxis']['categories'];
    
    $script = "<script>";
    $script .= "var data = [";
    
    //print_r($datas);
    
    $series_string = "{";
    foreach($datas AS $data)
    {
        $series_string .= $series_string == "{" ? "" : "},{";
        $series_string .= "name:\"".$data['name']."\",";
        if(isset($data['color'])) $series_string .= "color:\"".$data['color']."\",";
        if(isset($data['aggregate'])) $series_string .= "aggregate:\"".$data['aggregate']."\",";
        $series_string .= "data:[";
        $item_string = "";
        foreach($data['data'] AS $item)
        {
            $item_string .= $item_string == "" ? "" : ",";
            $item_string .= $item;        
        }
        $series_string .= $item_string;
        $series_string .= "]";
    }
    $script .= $series_string;
    $script .= "}];";
    $script .= "var category =[";
    
    $categories_string = "";
    foreach($categories AS $category)
    {
        $categories_string .= $categories_string == "" ? "" : ",";
        $categories_string .= "new Date(\"".str_replace("-", "/", substr($category, 0, 10))."\")";        
    }
    $script .= $categories_string;
    $script .= "];";

    $script .= "$(document).ready(function(){";
    $script .= "$(\"#".$kendoLineChart_syntax['divid']."\").kendoChart({";
    $script .= "series: data,";
    $script .= "categoryAxis:{";
        $script .= "categories:category,";
        if(isset($kendoLineChart_syntax['categoryAxis']['baseUnit'])) $script .= "baseUnit: \"".$kendoLineChart_syntax['categoryAxis']['baseUnit']."\",";
        $script .= "majorGridLines:{";
            if(isset($kendoLineChart_syntax['categoryAxis']['majorGridLines']['visible'])) $script .= "visible: ".$kendoLineChart_syntax['categoryAxis']['majorGridLines']['visible'].",";
        $script .= "}";
    $script .= "},";
    $script .= "chartArea:{";
        if(isset($kendoLineChart_syntax['chartArea']['width'])) $script .= "width: ".$kendoLineChart_syntax['chartArea']['width'].",";
        if(isset($kendoLineChart_syntax['chartArea']['height'])) $script .= "height: ".$kendoLineChart_syntax['chartArea']['height'].",";
        if(isset($kendoLineChart_syntax['chartArea']['background'])) $script .= "background: \"".$kendoLineChart_syntax['chartArea']['background']."\",";
    $script .= "},";
    $script .= "seriesDefaults:{type: \"line\"},";
    $script .= "title: {";
        if(isset($kendoLineChart_syntax['title']['text'])) $script .= "text: \"".$kendoLineChart_syntax['title']['text']."\",";
        if(isset($kendoLineChart_syntax['title']['position'])) $script .= "position: \"".$kendoLineChart_syntax['title']['position']."\",";
    $script .= "},";
    if(isset($kendoLineChart_syntax['legend']))
    {
        $script .= "legend:{";
            if(isset($kendoLineChart_syntax['legend']['position'])) $script .= "position: \"".$kendoLineChart_syntax['legend']['position']."\",";
        $script .= "},";
    }
    $script .= "tooltip:{";
        if(isset($kendoLineChart_syntax['tooltip']['visible'])) $script .= "visible: ".$kendoLineChart_syntax['tooltip']['visible'].",";
        if(isset($kendoLineChart_syntax['tooltip']['format'])) $script .= "format: \"".$kendoLineChart_syntax['tooltip']['format']."\",";
        if(isset($kendoLineChart_syntax['tooltip']['template'])) $script .= "template: \"".$kendoLineChart_syntax['tooltip']['template']."\",";
    $script .= "}";
    $script .= "});";
    $script .= "});";

    $script .= "$(\".config_".$kendoLineChart_syntax['divid']."\").bind(\"change\", refresh_".$kendoLineChart_syntax['divid'].");";

    $script .= "function refresh_".$kendoLineChart_syntax['divid']."() {";
        $script .= "var chart = $(\"#".$kendoLineChart_syntax['divid']."\").data(\"kendoChart\"),";
            $script .= "series = chart.options.series,";
            $script .= "categoryAxis = chart.options.categoryAxis,";
            $script .= "aggregateInputs = $(\".config_".$kendoLineChart_syntax['divid']." input:radio[name=aggregate_".$kendoLineChart_syntax['divid']."]\"),";
            $script .= "baseUnitInputs = $(\".config_".$kendoLineChart_syntax['divid']." input:radio[name=baseUnit_".$kendoLineChart_syntax['divid']."]\");";
        $script .= "for (var i = 0, length = series.length; i < length; i++) {";
            $script .= "series[i].aggregate = aggregateInputs.filter(\":checked\").val();";
        $script .= "};";
        $script .= "categoryAxis.baseUnit = baseUnitInputs.filter(\":checked\").val();";
        $script .= "chart.refresh();";
    $script .= "};";
    $script .= "</script>";
    return $script;
}
?>
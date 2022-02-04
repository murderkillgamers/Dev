<?php
function getTable($parameter){
    $db = isset($parameter['database']) ? $parameter['database'] : "uranus";
    $top = isset($parameter['top']) ? "TOP ".$parameter['top'] : "";
    $selects = isset($parameter['selects']) ? "[".implode('],[',$parameter['selects'])."]" : "*";
    $tableName = $parameter['tableName'];
    $filtersIn = isset($parameter['filtersIn']) ? $parameter['filtersIn'] : array();
    $filtersNotIn = isset($parameter['filtersNotIn']) ? $parameter['filtersNotIn'] : array();
    $filtersLike = isset($parameter['filtersLike']) ? $parameter['filtersLike'] : array();
    $filtersNotLike = isset($parameter['filtersNotLike']) ? $parameter['filtersNotLike'] : array();
    $filtersBetween = isset($parameter['filtersBetween']) ? $parameter['filtersBetween'] : array();
    $filtersNull = isset($parameter['filtersNull']) ? $parameter['filtersNull'] : array();
    $filtersNotNull = isset($parameter['filtersNotNull']) ? $parameter['filtersNotNull'] : array();
    $orders = isset($parameter['orders']) ? $parameter['orders'] : array();
    $id = isset($parameter['id']) ? $parameter['id'] : "Id";
    $stacked = isset($parameter['stacked']) ? $parameter['stacked'] : 0;
    $isKey = isset($parameter['isKey']) ? $parameter['isKey'] : 1;

    $connect_sqlsrv = connect_sql_server($db);
    $query = "SELECT ".$top.$selects." FROM [$tableName]";

    if(count($filtersIn) || count($filtersNotIn) || count($filtersLike) || count($filtersNotLike) || count($filtersBetween) || count($filtersNull) || $filtersNotNull)
    {
        $queryWhere = " WHERE";
        foreach($filtersIn AS $field => $values)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]IN('".implode("','",$values)."')";
        }
        foreach($filtersNotIn AS $field => $values)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]NOT IN('".implode("','",$values)."')";
        }

        foreach($filtersLike AS $field => $value)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]LIKE'%".$value."%'";
        }
        foreach($filtersNotLike AS $field => $value)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]NOT LIKE'%".$value."%'";
        }

        foreach($filtersBetween AS $field => $values)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."] BETWEEN '".$values[0]."' AND '".$values[1]."'";
        }

        foreach($filtersNull AS $index => $field)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."] IS NULL";
        }
        foreach($filtersNotNull AS $index => $field)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."] IS NOT NULL";
        }


        $query .= $queryWhere;
    }
    if($orders)
    {
        $queryOrder = " ORDER BY";
        foreach($orders AS $field => $ascdesc)
        {
            $queryOrder .= $queryOrder == " ORDER BY" ? "" : "," ;
            $queryOrder .= "[".$field."] $ascdesc";
        }
        $query .= $queryOrder;
    }
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));

    //print_r($parameter);
    //echo $query;
    $dims = array();
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        if($stacked == 0){
            if($isKey == 0){
                array_push($dims,$row);
            }
            else{
                $dims[$row[$id]] = $row;
            }
        }
        else if($stacked == 1){
            if(!isset($dims[$row[$id]]))$dims[$row[$id]] = array();
            array_push($dims[$row[$id]],$row);
        }
    }
    sqlsrv_close($connect_sqlsrv);
    return $dims;
}
function getTable2($parameter){
    $db = isset($parameter['database']) ? $parameter['database'] : "inspire";
    $top = isset($parameter['top']) ? "TOP ".$parameter['top'] : "";
    $selects = isset($parameter['selects']) ? "[".implode('],[',$parameter['selects'])."]" : "*";
    $tableName = $parameter['tableName'];
    $filtersIn = isset($parameter['filtersIn']) ? $parameter['filtersIn'] : array();
    $filtersNotIn = isset($parameter['filtersNotIn']) ? $parameter['filtersNotIn'] : array();
    $filtersLike = isset($parameter['filtersLike']) ? $parameter['filtersLike'] : array();
    $filtersNotLike = isset($parameter['filtersNotLike']) ? $parameter['filtersNotLike'] : array();
    $filtersBetween = isset($parameter['filtersBetween']) ? $parameter['filtersBetween'] : array();
    $orders = isset($parameter['orders']) ? $parameter['orders'] : array();
    $id = isset($parameter['id']) ? $parameter['id'] : "Id";
    $stacked = isset($parameter['stacked']) ? $parameter['stacked'] : 0;
    $isKey = isset($parameter['isKey']) ? $parameter['isKey'] : 1;

    $connect_sqlsrv = connect_sql_server($db);
    $query = "SELECT ".$top.$selects." FROM [$tableName]";

    if(count($filtersIn) || count($filtersNotIn) || count($filtersLike) || count($filtersNotLike) || count($filtersBetween))
    {
        $queryWhere = " WHERE";
        foreach($filtersIn AS $field => $values)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]IN('".implode("','",$values)."')";
        }
        foreach($filtersNotIn AS $field => $values)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]NOT IN('".implode("','",$values)."')";
        }

        foreach($filtersLike AS $field => $value)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]LIKE'%".$value."%'";
        }
        foreach($filtersNotLike AS $field => $value)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."]NOT LIKE'%".$value."%'";
        }
        foreach($filtersBetween AS $field => $values)
        {
            $queryWhere .= ($queryWhere == " WHERE" ? "" : " AND");
            $queryWhere .= " [".$field."] BETWEEN '".$values[0]."' AND '".$values[1]."'";
        }
        $query .= $queryWhere;
    }
    if($orders)
    {
        $queryOrder = " ORDER BY";
        foreach($orders AS $field => $ascdesc)
        {
            $queryOrder .= $queryOrder == " ORDER BY" ? "" : "," ;
            $queryOrder .= "[".$field."] $ascdesc";
        }
        $query .= $queryOrder;
    }
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    //print_r($parameter);
    echo $query;
    $dims = array();
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        if($stacked == 0){
            if($isKey == 0){
                array_push($dims,$row);
            }
            else{
                $dims[$row[$id]] = $row;
            }
        }
        else if($stacked == 1){
            if(!isset($dims[$row[$id]]))$dims[$row[$id]] = array();
            array_push($dims[$row[$id]],$row);
        }
    }
    sqlsrv_close($connect_sqlsrv);
    return $dims;
}
function RollBackData($OriginalDatas,$NewDatas){
    foreach($OriginalDatas AS $index => $OD)
    {
        $Database = $OD["Database"];
        $Table = $OD["Table"];
        $datas = $OD["Datas"];
        $connect_sqlsrv = connect_sql_server($Database);

        $query = "UPDATE [".$Table."] SET ";
        $querySets = array();
        foreach($datas AS $ColumnName => $Value)
        {
            if($ColumnName != "Id")
            {
                $querySet = "";
                $querySet .= " [".$ColumnName."] = ";
                if($Value === null)
                {
                    $querySet .= "NULL";
                }
                else
                {
                    $querySet .= "'".$Value."'";
                }
                $querySets[] = $querySet;
            }
        }
        $query .= implode(",",$querySets);
        $query .= " WHERE [Id] = ".$OD["Id"].";";
        sqlsrv_query($connect_sqlsrv, $query);
    }
    foreach($NewDatas AS $index => $ND)
    {
        $Database = $ND["Database"];
        $Table = $ND["Table"];
        $Id = $ND["Id"];
        $connect_sqlsrv = connect_sql_server($Database);

        $query = " DELETE FROM [".$Table."] WHERE [Id] = ".$Id.";";
        sqlsrv_query($connect_sqlsrv, $query);
    }
}

function getAuthentication($userId,$moduleId){
    $connect_sqlsrv = connect_sql_server("inspire");

    $query = "SELECT COUNT(*) [Total] FROM [DIM_Config] WHERE [Id] = '1' AND [Value] LIKE '%|".$moduleId."|%';";
    $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    if($row["Total"])
    {
        return $row["Total"];
    }
    else
    {
        $query = "SELECT COUNT(*) [Total] FROM [DIM_User] WHERE [Id] = '".$userId."' AND [ModuleIds] LIKE '%|".$moduleId."|%';";
        //echo $query;
        $result = sqlsrv_query($connect_sqlsrv, $query, array(), array("Scrollable"=>"buffered"));
        return sqlsrv_num_rows($result);
    }
}

function logSparepartRack($Id){
    $connect_sqlsrv = connect_sql_server("plutus");
    $query = "INSERT INTO [LOG_SparepartRack]
        ([DateTime],[RackId],[SparepartId],[Quantity],[QuantityBlock])
        SELECT GETDATE(),[RackId],[SparepartId],[Quantity],[QuantityBlock]
        FROM [FACT_SparepartRack]
        WHERE [Id] = ".$Id."
    ";
    sqlsrv_query($connect_sqlsrv, $query);
}

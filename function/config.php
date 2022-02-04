<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Jakarta');
//date_default_timezone_set("America/Los_Angeles");

//SQL SERVER CONENCTION
ini_set("mssql.textlimit",2147483647 );
ini_set("mssql.textsize",2147483647 );
//PDO::setAttribute( PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE, 2048000 );
sqlsrv_configure( "ClientBufferMaxKBSize", 1024000 );
ini_set("memory_limit", '-1');
$metroUiColorMin = 1;
$metroUiColorMax = 46;

$SPAREPART_UNITS = array("PCS","DUS","GAL","LTR","BTL","KG","GR","SET","CM","MTR","ROLL","DRUM");

//POSITION ID PER BRAND
$DIRECTORATE_ID[1] = 8;
$DIRECTORATE_ID[2] = 7;
$DIRECTORATE_ID[3] = 6;

$DIVISION_ID[1] = 25;
$DIVISION_ID[2] = 24;
$DIVISION_ID[3] = 23;

$POSITION_SALES_OM_IDS[1] = array(199,196,326,371,238);
$POSITION_SALES_OM_IDS[2] = array(197,198);
$POSITION_SALES_OM_IDS[3] = array(328);
$POSITION_SALES_OM_IDS[0] = array_merge($POSITION_SALES_OM_IDS[1],$POSITION_SALES_OM_IDS[2],$POSITION_SALES_OM_IDS[3]);

$POSITION_FLEET__OM_IDS[1] = array(306);
$POSITION_FLEET__OM_IDS[2] = array(0);
$POSITION_FLEET__OM_IDS[3] = array(0);
$POSITION_FLEET__OM_IDS[0] = array_merge($POSITION_FLEET__OM_IDS[1],$POSITION_FLEET__OM_IDS[2],$POSITION_FLEET__OM_IDS[3]);

$POSITION_BM_IDS[1] = array(327);
$POSITION_BM_IDS[2] = array(324);
$POSITION_BM_IDS[3] = array(329);
$POSITION_BM_IDS[0] = array_merge($POSITION_BM_IDS[1],$POSITION_BM_IDS[2],$POSITION_BM_IDS[3]);

$POSITION_SM_IDS[1] = array(301);
$POSITION_SM_IDS[2] = array(50);
$POSITION_SM_IDS[3] = array(307);
$POSITION_SM_IDS[0] = array_merge($POSITION_SM_IDS[1],$POSITION_SM_IDS[2],$POSITION_SM_IDS[3]);

$POSITION_SPV_IDS[1] = array(308);
$POSITION_SPV_IDS[2] = array(302);
$POSITION_SPV_IDS[3] = array(315);
$POSITION_SPV_IDS[0] = array_merge($POSITION_SPV_IDS[1],$POSITION_SPV_IDS[2],$POSITION_SPV_IDS[3]);

<?php
/*
 * author 
 * fransalamonda 20160121
 * rev by imaraoshi
 * export to CSV
 */
include '../inc/constant.php';
include '../lib/function.php';
include '../db.php';

$sql_query = "SELECT * FROM `mix_package_composition` ";
//echo $sql_query; exit();
$output ="MixPackage_Composition";
exportMysqlToCsv($sql_query,$output.".csv");
$conns->close();
   


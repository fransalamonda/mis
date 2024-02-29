<?php
/*
 * author 
 * fransalamonda 20160121
 * export to CSV
 */
include '../inc/constant.php';
include '../lib/function.php';
include '../db.php';

$sql_query = "SELECT * FROM `mix_design_composition` ";
//echo $sql_query; exit();
$output ="MixDesign_Composition";
exportMysqlToCsv($sql_query,$output.".csv");
$conns->close();
   


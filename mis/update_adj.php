<?php
/*
 * Author Fransalamonda     
 * 151015
 * update bash 050117
 */
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $ds_update = "DELETE FROM `mix_design` WHERE `product_code` = 'ADJUSTMENT'";
    $s_update = $mysqli->query($ds_update);
    if(!$s_update){exit("Error:".  mysqli_error($mysqli));}
    
    $ds_update_login = "DELETE FROM `mix_design_composition` WHERE `product_code` = 'ADJUSTMENT'";
    $s_update_login = $mysqli->query($ds_update_login);
    if(!$s_update_login){ exit("Error:".  mysqli_error($mysqli)); }

$insertcd = "DELETE FROM `mix_design` WHERE `product_code` = 'SELFUSAGE'";
$insertcd_cd = $mysqli->query($insertcd);
if(!$insertcd_cd){ exit("ERROR:". mysql_error($mysqli));}

$delsel = "DELETE FROM `mix_design_composition` WHERE `product_code` = 'SELFUSAGE'";
$seldel = $mysqli->query($delsel);
if(!$seldel){ exit("ERROR:". mysql_error($mysqli));}

//ALTER TABLE `batch_request` ADD po_no VARCHAR(50), 
//ALTER TABLE `batch_request` ADD 1_24hr DOUBLE,
//ALTER TABLE `batch_request` ADD out_volume INTEGER(11)

echo "Update SQL ADJUSTMENT & SELFUSAGE, selesai!";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php
/*
 * Author Fransalamonda     
 * 151015
 * update bash 151015
 */
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$ds_update = "ALTER TABLE `delivery_schedule` ADD ds_code VARCHAR(11)";
$s_update = $mysqli->query($ds_update);
if(!$s_update){
    exit("Error:".  mysqli_error($mysqli));
}
$update_ds = "UPDATE `delivery_schedule` SET `ds_code` = 'F'";
$update_s = $mysqli->query($update_ds);
if(!$update_s){
  exit("ERROR:". mysql_error($mysqli));
}

$create = "CREATE TABLE IF NOT EXISTS `tmp_batch_transaction_detail` (
  `request_no` varchar(10) NOT NULL,
  `so_no` char(20) DEFAULT NULL,
  `docket_no` varchar(20) NOT NULL DEFAULT '',
  `material_code` varchar(12) NOT NULL DEFAULT '',
  `design_qty` double DEFAULT NULL,
  `actual_qty` double DEFAULT NULL,
  `target_qty` double DEFAULT NULL,
  `moisture` double DEFAULT NULL,
  PRIMARY KEY (`request_no`,`docket_no`,`material_code`)
)";
$create_s = $mysqli->query($create);
if(!$create_s){
  exit("ERROR:". mysql_error($mysqli));
}

$insertcd = "INSERT INTO `tbl_group_user` (`id_group`, `group_name`) VALUES (5, 'central dispatch')";
$insertcd_cd = $mysqli->query($insertcd);
if(!$insertcd_cd){
  exit("ERROR:". mysql_error($mysqli));
}
$insertcd_l ="INSERT INTO `tbl_user` (`id_user`, `group_id`, `password`, `username`, `datecreated`, `datemodified`, `createdby`, `modifiedby`) VALUES ('cd', 5, MD5(12345), 'centraldispatch', NOW(), NULL, NULL, NULL)";
$insertcd_lg = $mysqli->query($insertcd_l);
if(!$insertcd_lg){
  exit("ERROR:". mysql_error($mysqli));
}
echo "Update SQL DEC, selesai!";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


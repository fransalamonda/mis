<?php
/*
 * Author Fransalamonda     
 * 020518
 * 
 */
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

$create = "CREATE TABLE IF NOT EXISTS `request` (
  `request_no` varchar(10) NOT NULL,
  `seal_no` varchar(20) DEFAULT NULL,
  `code_bom` int(11) DEFAULT NULL,
  `name_bom` varchar(50) DEFAULT NULL,
  `id_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`request_no`)
)";
$create_s = $mysqli->query($create);
if(!$create_s){
  exit("ERROR UPDATE:". mysql_error($mysqli));
}
echo "Update SQL DEC, selesai!";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


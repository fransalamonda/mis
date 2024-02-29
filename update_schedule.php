<?php
/*
 * Author Fransalamonda     
 * 151015
 * update bash 151015
 */
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$ds_update = "ALTER TABLE `delivery_schedule` ADD date_upload datetime";
$s_update = $mysqli->query($ds_update);
if(!$s_update){
    exit("Error:".  mysqli_error($mysqli));
}

echo "Update SQL DEC, selesai!";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


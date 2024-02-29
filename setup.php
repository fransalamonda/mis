<?php
/*
 * Author ilham.dinov
 * 150108
 * setup peralihan data dari revisi sebelum 150108
 */
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$q_check = "UPDATE `batch_transaction` "
        . "SET `flag_code`= CASE  WHEN UPPER(`flag_code`)='M' THEN 'T' WHEN UPPER(`flag_code`)='S' THEN 'P' ELSE UPPER(`flag_code`) END "
        . "WHERE STR_TO_DATE(delv_date, '%Y%m%d') "
        . "BETWEEN '".SETUP_DATE_AWAL."' AND '".SETUP_DATE_AKHIR."';";
$r_check = $mysqli->query($q_check);
if(!$r_check){
    exit("Error:".  mysqli_error($mysqli));
}
echo "Setup data dari tanggal ".SETUP_DATE_AWAL." sampai ".SETUP_DATE_AKHIR.", selesai!";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


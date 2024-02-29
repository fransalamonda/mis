<?php
session_start();
//include 'inc/constant.php';
//$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
////$dilog='';
//$ui= $_SESSION['id_user'];
//print_r($ui);exit();        
//$selectout="SELECT *FROM `bash`.`log`WHERE username='".$object->id_user."' AND `timeout` ='0000-00-00 00:00:00'GROUP BY `idlog` DESC LIMIT 1";
//$result = $mysqli->query($selectout);
////$hasil = mysql_query($selectout);
//$row = mysql_fetch_array($hasil);
//    $dilog         = $row['idlog'];
//    $updateout="UPDATE `bash`.`log` SET `timeout` = '".date('Y-m-d H:i:s')."' WHERE `idlog` ='".$dilog."' AND `timeout` = '0000-00-00 00:00:00'";
//    $r_out = $mysqli->query($updateout);



// remove all session variables
session_unset(); 
// destroy the session 
session_destroy(); 

header("Location:index.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


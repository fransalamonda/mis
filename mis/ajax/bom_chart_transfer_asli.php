<?php
session_start();
include '../inc/constant.php';
include '../inc/database.php';
if(!IS_AJAX){
    die("Access Denied");
}
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
/*
 * CHECKING SESSION
 */

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method is Denied");
    }
    $field_list = array("mach_code","product_code","chart_no");
    
    $field_post = array();
    foreach ($_POST as $key => $value) {
        array_push($field_post, $key);
    }
    
    //cek field yang dikirimkan dalam $_POST
    foreach ($field_list as $value) {
        if(!in_array($value, $field_post)){
            throw new Exception("Missing <b>".$value."</b> field");
        }
    }
    foreach ($field_list as $value) {
        if(empty($_POST[$value])){
            throw new Exception("Field <b>".$value."</b> need to be filled");
        }
    }
    
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$mysqli)        throw new Exception($mysqli->error);
    
    $sql_chart = "SELECT DISTINCT `chart_no` "
            . "FROM `mix_package_composition` "
            . "WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' "
            . "AND `chart_no`='".$_POST['chart_no']."' "
            . "ORDER BY `chart_no`";
    $r_check = $mysqli->query($sql_chart);
    if(!$r_check){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    if($r_check->num_rows == 0) {throw new Exception("Data tidak ditemukan");}
    
    $mysqli->autocommit(FALSE);
    //hapus data mix_design di tbl mix_design
    $sql = "DELETE FROM `mix_design` WHERE `mch_code`='".$_POST['mach_code']."' "
            . "AND `product_code`='".$_POST['product_code']."'";
    $r_delete = $mysqli->query($sql);
    if(!$r_delete){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    //proses hapus
    $sql_hapus = "DELETE FROM `mix_design_composition` WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' ";
    $r_delete = $mysqli->query($sql_hapus);
    if(!$r_delete){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    
    $sql_insert = "INSERT INTO `mix_design_composition` "
            . "(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`) "
            . "SELECT `mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`FROM  `mix_package_composition` "
            . "WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' AND `chart_no`='".$_POST['chart_no']."' AND mix_qty > 0";
    $r_insert = $mysqli->query($sql_insert);
    if(!$r_insert){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $mysqli->commit();
    $output = array(
        "status"    =>  1,
        "msg"       =>  "Data Berhasil diTransfer",
    );
    exit(json_encode($output));
      
} catch (Exception $exc) {
    $output = array(
        "status"    =>  0,
        "msg"       =>  $exc->getMessage()
    );
    exit(json_encode($output));
}
exit();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


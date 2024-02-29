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

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method is Denied");
    }
    $field_list = array("chart_no");    
    $field_post = array();
    foreach ($_POST as $key => $value) {
        array_push($field_post, $key);
    }
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$mysqli)        throw new Exception($mysqli->error);
    $sql_chart = "SELECT DISTINCT `chart_no` "
            . "FROM `mix_package_composition` "
            . "WHERE `mch_code`='".PLANT_ID."' AND `chart_no`='".$_POST['chart_no']."' "
            . "ORDER BY `chart_no`";
    $r_check = $mysqli->query($sql_chart);
    if(!$r_check){ throw new Exception("Error:1".  mysqli_error($mysqli)); }
    if($r_check->num_rows == 0) {throw new Exception("Data tidak ditemukan");}
    $mysqli->autocommit(FALSE);
    
    // cek product code yang akan di kirim ke table MIX design
    $sql_code = "SELECT `product_code` "
              . "FROM `mix_package` "
              . "WHERE `mch_code`='".PLANT_ID."' ";
    $r_code = $mysqli->query($sql_code);
    $product_code="";
    if($r_code->num_rows > 0) {
        $row = mysql_fetch_array($r_code);
        $product_code = $row[0];
    }
    
    //delete mix_design
    $sql = "DELETE FROM `mix_design` WHERE `mch_code`='".PLANT_ID."' AND `product_code`= '.$product_code.' ";
        $r_delete = $mysqli->query($sql);
        if(!$r_delete){
            $mysqli->rollback();$mysqli->close();
            throw new Exception("Error: 3".  mysqli_error($mysqli));
        }
        // insert mix_design
    $sql_d = "INSERT INTO `mix_design`(`mch_code`,`product_code`,`slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date`)
          SELECT `mch_code`,`product_code`,`slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date` 
          FROM   `mix_package` 
          WHERE `mch_code`='".PLANT_ID."' AND `product_code`= '.$product_code.' ";
    $i_insert = $mysqli->query($sql_d);
    if(!$i_insert){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error: 4".  mysqli_error($mysqli));
    }
    //hapus 
    $sql_hapus = "DELETE FROM `mix_design_composition`
                    WHERE EXISTS
                    ( SELECT *
                      FROM `mix_package_composition`
                      WHERE `mix_design_composition`.product_code = `mix_package_composition`.product_code
                      AND `mix_package_composition`.`chart_no`='".$_POST['chart_no']."'
                      AND `mch_code`='".PLANT_ID."')";
    $r_delete = $mysqli->query($sql_hapus);
    if(!$r_delete){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error: 5".  mysqli_error($mysqli));
    }
    
    //insert mix design composition
    $sql_insert = "INSERT INTO `mix_design_composition` "
            . "(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`) "
            . "SELECT `mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`FROM  `mix_package_composition` "
            . "WHERE `mch_code`='".PLANT_ID."' AND `chart_no`='".$_POST['chart_no']."' AND mix_qty > 0";
    $r_insert = $mysqli->query($sql_insert);
    if(!$r_insert){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error: 6".  mysqli_error($mysqli));
    } 
    
    $mysqli->commit();
    $output = array(
        "status"    =>  1,
        "msg"       => 'Succesfully  <b>Transfer</b>',    
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


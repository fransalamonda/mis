<?php
/*
 * author 
 * fransalamonda 20160121
 * export to CSV
 */
include '../inc/constant.php';
include '../lib/function.php';
include '../db.php';
if(isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['filter']) && !empty($_GET['filter'])){
    
    
    /*
     * list docket Acceptance - review normal  (flag S)
     */
    if($_GET['code'] == 1){
        $sql_query = "SELECT A.`docket_no` 'Docket no',A.`so_no` 'SO No','1' as 'SO Line No',A.`mch_code` 'Machine No',A.`product_code` 'Product Code',A.`delv_vol` 'Delivery Vol',A.`unit_no` 'Unit No',A.`driver_id` ' Driver ID'"
                . "FROM `batch_transaction` A "
                . "INNER JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE A.`delv_date` = '".$_GET['filter']."' "
                . "AND A.`mch_code`='".PLANT_ID."' "
                . "AND A.`product_code`!= 'ADJUSTMENT'"
                . "AND A.`product_code`!= 'SELFUSAGE'"
                . "AND B.`id_acceptance`='1' "
                . "AND (UPPER(A.`flag_code`) = 'S' OR UPPER(A.`flag_code`) = 'M')";
        //echo $sql_query; exit();
        $output = $_GET['filter']."_Docket";
        exportMysqlToCsv($sql_query,$output.".csv");
        $conns->close();
    }
    
    /*
     * list material issue normal code accep 00 and 03
     */
    if($_GET['code'] == 2){
        //1.get list docket
        $sql_query = "SELECT ' ' no,A.`so_no`,'1' so_line_no,B.`docket_no`,B.`Product_Code`,A.`material_code`,A.`target_qty`,A.`actual_qty`,A.`moisture` "
                    . "FROM `tmp_batch_transaction_detail` AS A "
                    . "INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` "
                    . "INNER JOIN `batch_transaction2` C ON B.`docket_no`=C.`docket_no` "
                    . "WHERE B.`delv_date`='".$_GET['filter']."' "
                    . "AND B.`mch_code`='".PLANT_ID."' "
                    . "AND B.`product_code`!= 'ADJUSTMENT' "
                    . "AND B.`product_code`!= 'SELFUSAGE' "
                    . "AND C.`id_acceptance`='1'"
                    . "AND (UPPER(B.`flag_code`) = 'S' OR UPPER(B.flag_code) = 'M')";
        
        
        //SQL UPDATE FLAG
        $sql_update = "UPDATE `batch_transaction` B "
                . "INNER JOIN `batch_transaction2` A ON A.`docket_no`=B.`docket_no` "
                . "SET `flag_code`=CASE UPPER(`flag_code`) "
                . "WHEN 'S' THEN 'P' WHEN 'M' THEN 'T' ELSE `flag_code` END "
                . "WHERE B.`delv_date` = '".$_GET['filter']."' "
                    . "AND B.`mch_code`='".PLANT_ID."' "
                    . "AND A.`id_acceptance`='1'";
        
        $output = $_GET['filter']."_Mat_is";
        exportMysqlToCsv($sql_query,$output.".csv","update",$sql_update);
        $conns->close();
    }
    
    /*
     * list docket manual
     */
    if($_GET['code'] == 3){
        $sql_query = "SELECT A.`docket_no` 'Docket no',A.`so_no` 'SO No','1' as 'SO Line No',A.`mch_code` 'Machine No',A.`product_code` 'Product Code',A.`delv_vol` 'Delivery Vol',A.`unit_no` 'Unit No',A.`driver_id` ' Driver ID'"
                . "FROM `batch_transaction` A "
                . "INNER JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE A.`delv_date` = '".$_GET['filter']."' "
                . "AND A.`mch_code`='".PLANT_ID."' "
                . "AND A.`product_code`!= 'ADJUSTMENT'"
                . "AND A.`product_code`!= 'SELFUSAGE'"
                //. "AND (B.`id_acceptance`='2' OR B.`id_acceptance`='3' B.`id_acceptance`='4')"
                . "AND B.`id_acceptance`!='1' "
                . "AND (UPPER(A.`flag_code`) = 'S' OR UPPER(A.`flag_code`) = 'M')";
        $output = $_GET['filter']."_Docket_manual";
        exportMysqlToCsv($sql_query,$output.".csv");
        $conns->close();
    }
    
    /*
     * list material issue manual
     */
    if($_GET['code'] == 4){
        //1.get list docket

        $sql_query = "SELECT ' ' no,A.`so_no`,'1' so_line_no,B.`docket_no`,B.`Product_Code`,A.`material_code`,A.`target_qty`,A.`actual_qty`,A.`moisture` "
                    . "FROM `tmp_batch_transaction_detail` AS A "
                    . "INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` "
                    . "INNER JOIN `batch_transaction2` C ON B.`docket_no`=C.`docket_no` "
                    . "WHERE B.`delv_date`='".$_GET['filter']."' "
                    . "AND B.`mch_code`='".PLANT_ID."' "
                    . "AND B.`product_code`!= 'ADJUSTMENT' "
                    . "AND B.`product_code`!= 'SELFUSAGE' "
                    //. "AND (C.`id_acceptance`='2' OR C.`id_acceptance`='3' OR C.`id_acceptance`='4')"
                    . "AND C.`id_acceptance`!='1' "
                    . "AND (UPPER(B.`flag_code`) = 'S' OR UPPER(B.flag_code) = 'M')";
        
        //SQL UPDATE FLAG
        $sql_update = "UPDATE `batch_transaction` B "
                . "INNER JOIN `batch_transaction2` A ON A.`docket_no`=B.`docket_no` "
                . "SET `flag_code`=CASE UPPER(`flag_code`) "
                . "WHEN 'S' THEN 'P' WHEN 'M' THEN 'T' ELSE `flag_code` END "
                . "WHERE B.`delv_date` = '".$_GET['filter']."' "
                . "AND B.`mch_code`='".PLANT_ID."' "
                    //. "AND (A.`id_acceptance`='2' OR A.`id_acceptance`='3' OR A.`id_acceptance`='4')";
                . "AND A.`id_acceptance`!='1' ";
        $output = $_GET['filter']."_Mat_is_manual";
        exportMysqlToCsv($sql_query,$output.".csv","update",$sql_update);
        $conns->close();
    }
    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


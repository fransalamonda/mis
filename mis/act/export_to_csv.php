<?php
/*
 * author ilham.dinov
 * export to CSV
 */
include '../inc/constant.php';
include '../lib/function.php';
if(isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['delvdate']) && !empty($_GET['delvdate'])){
    $con = mysql_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
    $db  = mysql_select_db(DB_NAME,$con);
    
    
    /*
     * list docket normal (flag S)
     */
    if($_GET['code'] == 1){
        $sql_query = "SELECT A.`docket_no` 'Docket no',A.`so_no` 'SO No','1' as 'SO Line No',A.`mch_code` 'Machine No',A.`product_code` 'Product Code',A.`delv_vol` 'Delivery Vol',A.`unit_no` 'Unit No',A.`driver_id` ' Driver ID'"
                . "FROM `batch_transaction` A "
                . "INNER JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE A.`delv_date` = '".$_GET['delvdate']."' "
                . "AND A.`mch_code`='".PLANT_ID."' "
                . "AND UPPER(A.`flag_code`) = 'S'";
        $output = $_GET['delvdate']."_Docket";
        exportMysqlToCsv($sql_query,$output.".csv");
    }
    
    /*
     * list material issue normal (flag S)
     */
    if($_GET['code'] == 2){
        //1.get list docket
        $where = "WHERE B.`delv_date`='".$_GET['delvdate']."' "
                . "AND B.`mch_code`='".PLANT_ID."' "
                . "AND UPPER(B.`flag_code`) = 'S'";
        $sql_query = "SELECT ' ' no,A.`so_no`,'1' so_line_no,B.`docket_no`,B.`Product_Code`,A.`material_code`,A.`target_qty`,A.`actual_qty`,A.`moisture` "
                . "FROM `batch_transaction_detail` AS A "
                . "INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` "
                . "INNER JOIN `batch_transaction2` C ON B.`docket_no`=C.`docket_no` "
                . $where;
        
        
        //SQL UPDATE FLAG
        $sql_update = "UPDATE `batch_transaction` B INNER JOIN `batch_transaction2` A ON A.`docket_no`=B.`docket_no` SET `flag_code`='P' ".$where." "
                . "";
        
        $output = $_GET['delvdate']."_Mat_is";
        exportMysqlToCsv($sql_query,$output.".csv","update",$sql_update);
    }
    
    /*
     * list docket manual
     */
    if($_GET['code'] == 3){
        $sql_query = "SELECT A.`docket_no` 'Docket no',A.`so_no` 'SO No','1' as 'SO Line No',A.`mch_code` 'Machine No',A.`product_code` 'Product Code',A.`delv_vol` 'Delivery Vol',A.`unit_no` 'Unit No',A.`driver_id` ' Driver ID'"
                . "FROM `batch_transaction` A "
                . "INNER JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE `delv_date` = '".$_GET['delvdate']."' "
                . "AND `mch_code`='".PLANT_ID."' "
                . "AND UPPER(`flag_code`) = 'M'";
        $output = $_GET['delvdate']."_Docket_manual";
        exportMysqlToCsv($sql_query,$output.".csv");
    }
    
    /*
     * list material issue manual
     */
    if($_GET['code'] == 4){
        //1.get list docket
        $where = "WHERE B.`delv_date`='".$_GET['delvdate']."' "
                . "AND B.`mch_code`='".PLANT_ID."' "
                . "AND UPPER(B.`flag_code`) = 'M'";
        $sql_query = "SELECT ' ' no,A.`so_no`,'1' so_line_no,B.`docket_no`,B.`Product_Code`,A.`material_code`,A.`target_qty`,A.`actual_qty`,A.`moisture` "
                . "FROM `batch_transaction_detail` AS A "
                . "INNER JOIN `batch_transaction` AS B ON  A.`docket_no`=B.`docket_no` "
                . "INNER JOIN `batch_transaction2` C ON B.`docket_no`=C.`docket_no` "
                . $where;
        $output = $_GET['delvdate']."_Mat_is_manual";
        
        $sql_update = "UPDATE `batch_transaction` B INNER JOIN `batch_transaction2` A ON A.`docket_no`=B.`docket_no` SET `flag_code`='T' ".$where;
        exportMysqlToCsv($sql_query,$output.".csv","update",$sql_update);
    }
    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


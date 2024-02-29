<?php
session_start();
include '../inc/constant.php';
include '../inc/database.php';
if(!IS_AJAX){ die("Access Denied");}
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method is Denied");
    }
    $field_list = array("fromdate","todate","so_no");
    $field_post = array();
    foreach ($_POST as $key => $value) {
        array_push($field_post, $key);
    }
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$mysqli)        throw new Exception($mysqli->error);
    
    if($_POST['fromdate']!='' && $_POST['todate']!='' && $_POST['so_no']!=''){
        $q = "SELECT A.* FROM `batch_transaction` A "
                . "LEFT JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE STR_TO_DATE(`delv_date`,'%Y%m%d') BETWEEN STR_TO_DATE('".$_POST['fromdate']."','%Y%m%d') AND STR_TO_DATE('".$_POST['todate']."','%Y%m%d') "
                . "AND A.`so_no` = '".$_POST['so_no']."' "
                . "ORDER BY `docket_no` ASC";                                
    }
    elseif ($_POST['fromdate']!='' && $_POST['todate']!='' && $_POST['so_no']=='') {
        $q = "SELECT A.* FROM `batch_transaction` A "
                . "LEFT JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE STR_TO_DATE(`delv_date`,'%Y%m%d') BETWEEN STR_TO_DATE('".$_POST['fromdate']."','%Y%m%d') AND STR_TO_DATE('".$_POST['todate']."','%Y%m%d') "
                . "ORDER BY `docket_no` ASC";                                  
    }
    elseif ($_POST['fromdate']!='' && $_POST['todate']=='' && $_POST['so_no']=='') {
        $q = "SELECT A.* FROM `batch_transaction` A "
                . "LEFT JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE STR_TO_DATE(`delv_date`,'%Y%m%d') = STR_TO_DATE('".$_POST['fromdate']."','%Y%m%d')  "
                . "ORDER BY `docket_no` ASC";                                  
    }
    elseif ($_POST['fromdate']=='' && $_POST['todate']=='' && $_POST['so_no']!='') {
        $q = "SELECT A.* FROM `batch_transaction` A "
                . "LEFT JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                . "WHERE A.`so_no` = '".$_POST['so_no']."' "
                . "ORDER BY `docket_no` ASC";                                  
    }
//    print_r($q);
//        exit();    
    $r_check = $mysqli->query($q);
    if(!$r_check){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }    
            $no=1;
            $delv_vol_sum=0;
            $content='';
            $content.="<table class='table small table-striped' id='example'>";
            $content.="<thead> ";
            $content.="<tr class='bold'>";
            $content.="<th>No</th>";
            $content.="<th>Seal No</th>";
//            $content.="<th>Machine Code</th>";
            $content.="<th>Docket No</th>";
            $content.="<th>Prod Code</th>";
            $content.="<th>So No</th>";            
            $content.="<th>Vol</th>";
            $content.="<th>Time</th>";
            $content.="<th>Date</th>";
            $content.="<th>Unit No</th>";
            $content.="<th>Driver Name</th>";
            $content.="<th>Cust Name</th>";
            $content.="<th>Proj Name</th>";
            $content.="<th>Flag</th>";
            $content.="<th>Flag</th>";
            $content.="</tr>";
            $content.="</thead>";
            $content.="<tbody>";            
            while($row = $r_check->fetch_array(MYSQLI_ASSOC)){
                $delv_vol_sum+=$row['delv_vol'];
                $content.="<tr><td>".$no++."</td>";
                $content.="<td>".$row['seal_no']."</td>";
//                $content.="<td>".$row['mch_code']."</td>";
                $content.="<td>".$row['docket_no']."</td>";
                $content.="<td>".$row['product_code']."</td>";
                $content.="<td>".$row['so_no']."</td>";
                $content.="<td>".$row['delv_vol']."</td>";
                $content.="<td>".$row['delv_time']."</td>";
                $content.="<td>".$row['delv_date']."</td>";
                $content.="<td>".$row['unit_no']."</td>";
                $content.="<td>".$row['driver_name']."</td>";
                $content.="<td>".$row['cust_name']."</td>";
                //$content.="<td>".$row['docket_no']."</td>";
                $content.="<td>".$row['proj_name']."</td>";
                $content.="<td>".$row['flag_code']."</td>";
                $content.="<td><a href='' data-docket=".$row['docket_no']." data-prod=".$row['product_code']." class='view btn btn-xs btn-warning'>View</a></td>";
                }
            $content.="<tr>";
            $content.="<td colspan='5' class='text-right'>Total Delv Vol :</td>";
            $content.="<td colspan='9'>".$delv_vol_sum."</td>";
            $content.="</tr>";
            $content.="</tbody>";
            $content.="</table>";
            $mysqli->commit();
            $output = array(
                "status"    =>  1,
                "tblso"     => $content,
               "msg"       => 'From Date '.$_POST['fromdate'].' To Date '.$_POST['todate']
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


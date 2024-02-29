<?php
include '../inc/constant.php';
include '../lib/mis.php';
if(IS_AJAX){
    try {
	$plant_id = PLANT_ID;
	$mysqli = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
        if(!$mysqli){
            throw new Exception("<b>Error001</b> : ".  mysql_error($conn));
        }
        if(!mysql_select_db(DB_NAME)){throw new Exception("<b>Error002</b> : ".  mysql_error($conn));}
        if(empty($_POST['delvdate']) ){
            throw new Exception("Kolom <b>Tanggal </b> Harus diisi!");
        }
        extract($_POST);   
//            $request_no     = "";
//            $so_no          = "";
//            $docket_no      = "";
//            $material_code  = "";
//            $design_qty     = "0";
//            $target_qty     = "0";
//            $actual_qty     = "0";
//            $moisture       = "0";
            //CEK DATA YANG STATUS FLAG_CODE nya 'S' atau 'M'   not adjustment  
        $d_string = "SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                FROM `batch_transaction_detail` AS A 
                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                WHERE B.`delv_date`='".$delvdate."'
                AND B.`mch_code`='".$plant_id."'";
                //AND (UPPER(B.`flag_code`) ='S' OR UPPER(B.`flag_code`) ='M')";
        //echo $d_string;        exit();
        $result_d = mysql_query($d_string);
        $jumlah_data_query = mysql_num_rows($result_d);
        //$data = mysql_fetch_array($d_string);
        if($jumlah_data_query == 0){
            throw new Exception("<b>Bacth Transaction Delv Date :</b> : <b>".$_POST['delvdate']."</b>  tidak ditemukan");
        } else {
        $trunc = mysql_query("TRUNCATE TABLE `tmp_batch_transaction_detail`");
                //extract hasil query `bacth transaction` mysql menjadi variabel
                //extract($data);
                $insert_docket = "INSERT INTO `tmp_batch_transaction_detail`(request_no,so_no,docket_no,material_code,design_qty,actual_qty,target_qty,moisture)
                                SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                                FROM `batch_transaction_detail` AS A 
                                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                                WHERE B.`delv_date`='$delvdate'
                                AND B.`product_code` != 'Adjustment'
                                AND B.`mch_code`='".PLANT_ID."' ";
                                //AND (UPPER(B.`flag_code`)='S' OR UPPER(B.`flag_code`)='M')";
            //echo $insert_docket;exit()        ;
                $result_docket = mysql_query($insert_docket);    
                $insert_docket = "INSERT INTO `tmp_batch_transaction_detail`(request_no,    so_no,        docket_no,  material_code,    design_qty,actual_qty,   target_qty    ,moisture)
                                SELECT A.`request_no`,B.`docket_no`,A.`so_no`, A.`material_code`, 0,A.`actual_qty`,0,0 
                                FROM `batch_transaction_detail` AS A 
                                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                                WHERE B.`delv_date`='$delvdate'
                                AND B.`product_code` = 'Adjustment'
                                AND B.`mch_code`='".PLANT_ID."' ";
                                //AND (UPPER(B.`flag_code`)='S' OR UPPER(B.`flag_code`)='M')";
//            //echo $insert_docket;exit()        ;
                $result_docket = mysql_query($insert_docket);    
                $output = array(
                                    'status'            =>  1,
                                    'msg'               =>  '',
                                    'out_volume'        =>  $plant_id);
                                exit(json_encode($output));
        }
    } 
    catch (Exception $exc) {
        $output = array(
            "status"    =>  0,
            "msg"       =>  "<b>Error</b> : ".$exc->getMessage()
            );
        exit(json_encode($output));
    }
}
else{
    die("Access denied");
}
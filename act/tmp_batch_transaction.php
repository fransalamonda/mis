<?php
include('../db.php');
include '../inc/constant.php';
if(isset($_POST['delvdate']) && !empty($_POST['delvdate'])){
    /*
     *generate variables : $delvdate , $mch_code
     */	
    $mch_code = PLANT_ID;
    extract($_POST);
    $tanggal_docket = date("Ymd",strtotime($delvdate)).date("His")."_".$mch_code;
    
	//CEK DATA YANG STATUS FLAG_CODE nya 'S' atau 'M'   not adjustment  
        $string = "SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                FROM `batch_transaction_detail` AS A 
                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                WHERE B.`delv_date`='$delvdate'
                AND B.`product_code` != 'Adjustment'
                AND B.`mch_code`='".PLANT_ID."' 
                AND (UPPER(B.`flag_code`) !='S' AND UPPER(B.`flag_code`)!='M')";
//echo $string; exit();
	$d = mysql_query($string);
        if(!$d){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Error : ".  mysql_error()
            );
            echo json_encode($output);exit();
        }
	if(mysql_num_rows($d) > 0){//JIKA DATA ADA
            //delete docket di tmp batch transaction yang sama 
//            while($row_delete = mysql_fetch_array($d)){
//                $docket_no = $row_delete[2];
//                $delete_d = "DELETE FROM `tmp_batch_transaction_detail` WHERE `docket_no` = '".$docket_no."'";
//                $result_delete = mysql_query($delete_d);
//            }
            //insert docket ke tmp batch transaction not adj
            $insert_docket = "INSERT INTO `tmp_batch_transaction_detail`(request_no,so_no,docket_no,material_code,design_qty,actual_qty,target_qty,moisture)
                                SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                                FROM `batch_transaction_detail` AS A 
                                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                                WHERE B.`delv_date`='$delvdate'
                                AND B.`product_code` != 'Adjustment'
                                AND B.`mch_code`='".PLANT_ID."' 
                                AND (UPPER(B.`flag_code`)!='S' AND UPPER(B.`flag_code`)!='M')";
//            echo $insert_docket;exit()        ;
            $result_docket = mysql_query($insert_docket);    
            //CEK DATA YANG STATUS FLAG_CODE nya 'S' atau 'M'   yg di adjustment  
            $s_docket = "SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                       FROM `batch_transaction_detail` AS A 
                       INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                       WHERE B.`delv_date`='$delvdate'
                       AND B.`product_code` = 'Adjustment'
                       AND B.`mch_code`='".PLANT_ID."' 
                       AND (UPPER(B.`flag_code`) !='S' AND UPPER(B.`flag_code`)!='M')";
            //echo $string; exit();
	    $d_string = mysql_query($s_docket);
            if(!$d_string){
              $output = array(
                'status'    =>  0,
                'msg'       =>  "Error : ".  mysql_error()
              );
              echo json_encode($output);exit();
            }
            if(mysql_num_rows($d_string) > 0){//JIKA DATA ADA
            while($row_adj = mysql_fetch_array($d_string)){
                $so_no = $row_adj[1];
                $material = $row_adj[3];
                $actual    = $row_adj[7];                
                $actual_out = 0;
                
                $kueri = "SELECT SUM(`actual_qty`) AS actual_out  FROM `tmp_batch_transaction_detail` WHERE `docket_no`='".$so_no."' AND `material_code` = '".$material."'";
                echo $kueri; exit();
                $q_q = mysql_query($kueri);
                if(!$q_q){
                    $output = array(
                        'status' => 0,
                        'msg'    => "Error : ". mysql_error()
                        );
                    echo json_encode($output);exit();
                }
                
//                if(mysql_num_rows($q_q) > 0){
//                    while($row_q_q = mysql_fetch_array($q_q)){
//                        $actual_o = $row_q_q[0];
//                        $a_o = ($actual_o + $actual) ;
//                        //echo $a_o;exit();
//                        $i=1;
//                        $update_q_q = "UPDATE `tmp_batch_transaction_detail`
//                                        SET `actual_qty` = '".$a_o."'
//                                        WHERE `docket_no` = '".$docket_no."'
//                                        AND `material_code` = '".$material."'";
//                        $u_q_q = mysql_query($update_q_q);
//                        $i++;
//                    }
//                }
//                else {
//                    $insert_adj = "INSERT INTO `tmp_batch_transaction_detail`(request_no,so_no,docket_no,material_code,design_qty,actual_qty,target_qty,moisture)
//                                SELECT A.`request_no`,A.`so_no`,A.`so_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
//                                FROM `batch_transaction_detail` AS A 
//                                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
//                                WHERE B.`delv_date`='$delvdate'
//                                AND B.`product_code` = 'Adjustment'
//                                AND B.`mch_code`='".PLANT_ID."' 
//                                AND (UPPER(B.`flag_code`)!='S' AND UPPER(B.`flag_code`)!='M')";
////            echo $insert_docket;exit()        ;
//            $docket_a = mysql_query($insert_adj);    
//                }
                
            }
        }else {
            $array=array(
                'status'	=>	0,
                'msg'		=>	'There\'s no available data',
                'error'		=>	mysql_error()
            );
        }
		//EMPTY BATCH TRANSACTION TEMP TABLE -> batch_plant_docket
		//$trunc = mysql_query("TRUNCATE TABLE `batch_plant_docket`");
//		$trunc2 = mysql_query("TRUNCATE TABLE `batch_plant_material_issue`");
            
		
	}
	else{
            $array=array(
                'status'	=>	0,
                'msg'		=>	'There\'s no available data',
                'error'		=>	mysql_error()
            );
	}
	
}
else{
    $array=array(
        'status'	=>	0,
        'msg'		=>	'Field Cannot be empty',
        'error'		=>	'empty field'
    );	
}
//echo json_encode($array);

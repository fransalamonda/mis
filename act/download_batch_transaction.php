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
	//CEK DATA YANG STATUS FLAG_CODE nya 'S' atau 'M'
	$string = "SELECT A.`docket_no`,A.`so_no`,'1',A.`mch_code`,A.`product_code`,A.`delv_vol`,A.`unit_no`,A.`driver_id` "
                . "FROM `batch_transaction` A "
                . "INNER JOIN `batch_transaction2` B "
                . "ON A.`request_no`=B.`request_no`"
                . "WHERE A.`delv_date` = '$delvdate' AND A.`mch_code`='$mch_code' AND (UPPER(A.`flag_code`) = 'S' OR UPPER(A.`flag_code`) = 'M')";
	$q = mysql_query($string);
        if(!$q){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Error : ".  mysql_error()
            );
            echo json_encode($output);exit();
        }
	if(mysql_num_rows($q) > 0){//JIKA DATA ADA
		
		//EMPTY BATCH TRANSACTION TEMP TABLE -> batch_plant_docket
		$trunc = mysql_query("TRUNCATE TABLE `batch_plant_docket`");
		$trunc2 = mysql_query("TRUNCATE TABLE `batch_plant_material_issue`");
		if($trunc && $trunc2){
//			$string = "INSERT INTO `batch_plant_docket` SELECT `docket_no`,`so_no`,'1',`mch_code`,`product_code`,`delv_vol`,`vh_no`,`driver_id` FROM `batch_transaction` WHERE `delv_date` = '$delvdate' AND `mch_code`='$mch_code' AND (UPPER(`flag_code`) = 'S' OR UPPER(`flag_code`) = 'M')";
                    //-----LOG 20140902--------//
                    $string = "INSERT INTO `batch_plant_docket` SELECT `seal_no`,`so_no`,'1',`mch_code`,`product_code`,`delv_vol`,`vh_no`,`driver_id` FROM `batch_transaction` WHERE `delv_date` = '$delvdate' AND `mch_code`='$mch_code' AND (UPPER(`flag_code`) = 'S' OR UPPER(`flag_code`) = 'M')";

			$q = mysql_query($string);
			if($q){
                            //buat hitung jumlah data yang berhasil dicopy
                            $string = "SELECT * FROM `batch_transaction` WHERE `delv_date` = '$delvdate' AND `mch_code`='$mch_code' AND (UPPER(`flag_code`) = 'S' OR UPPER(`flag_code`) = 'M')";
                            $q = mysql_query($string);
                            $num_rows = mysql_num_rows($q);

                            //TRANSFER DATA KE batch_plant_material_issue
//				$q_docket = "SELECT `docket_no` FROM `batch_plant_docket`";
                            //-----LOG 20140902--------//
                            $q_docket = "SELECT `docket_no` FROM `batch_transaction` WHERE `delv_date` = '$delvdate' AND `mch_code`='$mch_code' AND (UPPER(`flag_code`) = 'S' OR UPPER(`flag_code`) = 'M')";

                            $r_docket = mysql_query($q_docket);
                            $docket_no="";
                            while($row = mysql_fetch_array($r_docket)){
                                    $docket_no = $row[0];
                                    //INSERT DATA dari batch_transaction_detail ke batch_plant_material_issue
//					$query = "INSERT INTO `batch_plant_material_issue` SELECT ' ',a.`so_no`,'1',a.`docket_no`,b.`Product_Code`,a.`material_code`,a.`target_qty`,a.`actual_qty`,a.`moisture` FROM `batch_transaction_detail` AS a INNER JOIN `batch_transaction` AS b ON  a.`docket_no`=b.`docket_no` WHERE a.`docket_no` ='$docket_no'";
                                    //-----LOG 20140902--------//
                                    $query = "INSERT INTO `batch_plant_material_issue` "
                                            . "SELECT ' ',a.`so_no`,'1' so_line_no,b.`seal_no`,b.`Product_Code`,a.`material_code`,a.`target_qty`,a.`actual_qty`,a.`moisture` "
                                            . "FROM `batch_transaction_detail` AS a "
                                            . "INNER JOIN `batch_transaction` AS b ON  a.`docket_no`=b.`docket_no` WHERE a.`docket_no` ='$docket_no'";

                                    $result = mysql_query($query);
                            }

                            //UBAH STATUS FLAG_CODE menjadi 'P'
                            $string = "UPDATE `batch_transaction` SET `flag_code`='P' WHERE `delv_date` = '$delvdate' AND `mch_code`='$mch_code' AND UPPER(`flag_code`) = 'S'";
                            $q = mysql_query($string);

                            $string = "UPDATE `batch_transaction` SET `flag_code`='T' WHERE `delv_date` = '$delvdate' AND `mch_code`='$mch_code' AND UPPER(`flag_code`) = 'M'";
                            $q2 = mysql_query($string);
                            if($q && $q2){
                                $array = array(
                                    'status'	=>	1,
                                    'rows_inserted'	=>	$num_rows,
                                    'msg'		=>	'data berhasil di import!',
                                    'tgl_docket'    =>  $tanggal_docket
                                );
                            }
                            else{
                                $array = array(
                                    'status'		=>	0,
                                    'msg'			=>	'Error Update Status from S to P, OR status from M to T in Batch_Transaction Data',
                                    'error'			=>	mysql_error()
                                );
                            }
				
			}
			else{
                            $array=array(
                                    'status'	=>	0,
                                    'msg'		=>	'Error : failed COPY to temp table ',
                                    'error'		=>	mysql_error()
                            );
			}
		}
		else{
                    $array=array(
                            'status'	=>	0,
                            'msg'		=>	'Error : failed TRUNCATE temp table ',
                            'error'			=>	mysql_error()
                    );
		}
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
echo json_encode($array);

?>
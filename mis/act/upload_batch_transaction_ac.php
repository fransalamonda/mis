<?php
ini_set('max_execution_time', 300);
function get_request_no(){
	global $i;
	if($i ==0) $i=1;
	return  $i++;
}
include"../inc/constant.php";


$conn= mysql_connect(DB_SERVER,DB_USER,DB_PASS);
mysql_select_db(DB_NAME);


$upload_dir = "../uploads/bt/";
$tanggal_docket = "";
if (isset($_FILES["myfile"]) && isset($_POST['mach_code']) && !empty($_POST['mach_code'])){
	extract($_POST);
    if ($_FILES["myfile"]["error"] > 0) {
//        echo "Error: " . $_FILES["file"]["error"] . "<br>";
        echo json_encode(array('status' => '0', 'msg' => 'Error : '.$_FILES["myfile"]["error"]));exit();
    } 
    else{
    	$allowedExts = array("csv");
		$temp = explode(".", $_FILES["myfile"]["name"]);
		
		$extension = end($temp);
		
		if(!in_array($extension,$allowedExts)){
			echo json_encode(array('status' => '0', 'msg' => 'Error : data type not allowed'));exit();
		}
    	$nama_file = date("Ymdhms")."_BT.csv"; //Nama file yang akan di Upload
    	
        if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir .$nama_file)){
			$filename = $upload_dir.$nama_file;
//			echo $filename;
			$handle1 = fopen("$filename", "r");
			$i=0;
			
			$trunc = mysql_query("TRUNCATE TABLE `batch_plant_docket`");
			$trunc2 = mysql_query("TRUNCATE TABLE `batch_plant_material_issue`");
			if($trunc && $trunc2){
				//echo "berhasil truncate";
				$skip=0;//0 skip once, 1 no skip
				while (($data = fgetcsv($handle1, 1000, ",")) !== FALSE){
					if($skip > 6){
                                            $tanggal_docket = $data[1];
						//insert docket plant
						/*$insert_choy = "INSERT INTO `batch_plant_docket`
									            (`docket_no`,`so_no`,`so_line_no`,`plant_code`,`product_code`,`volume`,
									             `police_no`,`driver_id`)
									VALUES ('$data[3]',
									        '$data[39]',
									        '1',
									        '$mach_code',
									        '$data[6]',
									        '$data[4]',
									        '$data[36]',
									        '$data[37]')";*/
						$insert_choy = "INSERT INTO `batch_plant_docket`
									    (`docket_no`,`so_no`,`so_line_no`,`plant_code`,`product_code`,`volume`,
									    `police_no`,`driver_id`)
									VALUES ('$data[4]',
									        '$data[3]',
									        '1',
									        '$mach_code',
									        '$data[5]',
									        '$data[10]',
									        '$data[12]',
									        '$data[13]')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => '#12 Error Mysql : '.mysql_error()));exit();
						}
						
						//insert material issue
						$request_no = get_request_no();
//						echo $request_no."-";
						
						//0000-100030 WATER -> moisture = 0
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100030','0',
						        '$data[21]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						//0000-100011 -> cement
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100011','0',
						        '$data[19]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						//0000-000015 -> FA
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-000015','0',
						        '$data[20]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						
						//0000-100001 -> SAND
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100001','0',
						        '$data[14]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						
						//0000-100002 -> MSAND
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100002','0',
						        '$data[15]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						
						//0000-100005 -> AGG1
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100005','0',
						        '$data[16]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						
						//0000-100006 -> AGG2
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100006','0',
						        '$data[17]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						//0000-100007 -> AGG3
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0000-100007','0',
						        '$data[18]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						//0002-100001 -> P121R
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0002-100001','0',
						        '$data[22]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
						
						//0002-100201 -> S523N
						$request_no = get_request_no();
//						echo $request_no."-";
						$insert_choy="INSERT INTO `bash`.`batch_plant_material_issue`
						            (`request_no`,`so_no`,`so_line_no`,`docket_no`,
						             `finish_good_code`,`material_code`,`design_qty`,
						             `actual_qty`,`moisture`)
						VALUES ($request_no,'$data[3]','1',
						        '$data[4]','$data[5]',
						        '0002-100201','0',
						        '$data[23]','0')";
						$hasil_choy = mysql_query($insert_choy);
						if(!$hasil_choy){
							echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
						}
					}
					$skip++;
				}
                                $output = array(
                                    'status'    => 1,
                                    'msg'       => 'Succesfully Extract Batch Transaction, Please Push <b>Download Button</b> Bellow ',
                                    'tgl_docket'    =>  date("Ymd",strtotime($tanggal_docket)).date("His")."_".$mach_code
                                    );
				echo json_encode($output);
				exit();
			}
			else{
				echo json_encode(array('status' => 0,'msg' => 'Error Mysql : '.mysql_error()));exit();
			}
		}
		else{
			echo json_encode(array('status' => 0,'msg' => 'Error : cannot move file to '.$upload_dir));exit();
		}
	}
}
else{
	echo json_encode(array('row' => '0', 'msg' => 'You must choose 1 files to be uploaded and fill Plant ID field!', 'status' => 0, 'd' => 4, 'e' => 5));
}
?>
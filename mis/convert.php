<?php
include "db.php";
$upload_dir = "uploads/convert/";
if(isset($_POST['modul']) && !empty($_POST['modul'])){
	extract($_POST);
	if($modul == 'ds'){ //delivery schedule
		if (isset($_FILES["myfile"])){
			if ($_FILES["myfile"]["error"] > 0 ) {
				$output = array(
					'status'	=> 0,
					'msg'		=>	'There\'s some errors, maybe file is corrupt'
				);
				echo json_encode($output);exit();
		    } 
		    else{
				$allowedExts = array("csv");
				$temp = explode(".", $_FILES["myfile"]["name"]);
				$extension = end($temp);
				if(!in_array($extension,$allowedExts)){
					$output = array(
						'status'	=> 0,
						'msg'		=>	'Only CSV file allowed to be converted'
					);
					echo json_encode($output);exit();
				}
				else{
					$nama_file = "DS_".date("Ymd_hms").".csv"; //Nama file yang akan di Upload
					if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir . $nama_file)){
						$filename = $upload_dir.$nama_file;
						$handle1 = fopen($filename, "r");
						
						//TRUNCATE tmp_delivery_schedule
						if(!mysql_query("TRUNCATE TABLE `tmp_delivery_schedule`")){
							$output = array(
								'status'	=> 0,
								'msg'		=>	'My SQL Error : '.mysql_error()
							);
							echo json_encode($output);exit();
						}
						
						$skip=1;
						while (($data = fgetcsv($handle1, 10000, ",")) !== FALSE)
						{
//							echo $data[0].'<br>';
//							echo $skip;
							if($skip >= 5){
//								echo $data[0].'<br>';
//								echo $skip;
								$string = "INSERT INTO `tmp_delivery_schedule` VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."','".$data[12]."','".$data[13]."','".$data[14]."','".$data[15]."','".$data[16]."','".$data[17]."','".$data[18]."','".$data[19]."','".$data[20]."','".$data[21]."','".$data[22]."','".$data[23]."','".$data[24]."','".$data[25]."','".$data[26]."','".$data[27]."','".$data[28]."','".$data[29]."','".$data[30]."','".$data[31]."','".$data[32]."','".$data[33]."','".$data[34]."','".$data[35]."','".$data[36]."','".$data[37]."','".$data[38]."','".$data[39]."',' ')";	
								$result = mysql_query($string);
								if(!$result){
									$output = array(
										'status'	=> 0,
										'msg'		=>	'My SQL Error : '.mysql_error()
									);
									echo json_encode($output);exit();
								}
								
							}
							$skip++;
						}
						
						
						//-----

						//CONVERSI ISI TEMP TABLE
						$q = "SELECT * FROM `tmp_delivery_schedule`";
						$hasil = mysql_query($q);
						if($hasil && mysql_num_rows($hasil) > 0){
							while($row = mysql_fetch_array($hasil)){
								//customer id
								$cust_id = $row['customer_id'];
								$r_cust = mysql_query("SELECT `new` FROM `conversion` WHERE `old`= '$cust_id'");
								if($r_cust && mysql_num_rows($r_cust) > 0){
									$data_cust = mysql_fetch_array($r_cust);
									$q_up = "UPDATE `tmp_delivery_schedule` SET `customer_id` = '".$data_cust['new']."' WHERE `customer_id` = '".$row['customer_id']."'";
									$h_up = mysql_query($q_up);
									if(!$h_up){
										$output = array(
											'status'	=> 0,
											'msg'		=>	'My SQL Error : '.mysql_error()
										);
										echo json_encode($output);exit();
									}
								}
								//project id
								$proj_id = $row['project_id'];
								$r_cust = mysql_query("SELECT `new` FROM `conversion` WHERE `old`= '$proj_id'");
								if($r_cust && mysql_num_rows($r_cust) > 0){
									$data_cust = mysql_fetch_array($r_cust);
									$q_up = "UPDATE `tmp_delivery_schedule` SET `project_id` = '".$data_cust['new']."' WHERE `project_id` = '".$row['project_id']."'";
									$h_up = mysql_query($q_up);
									if(!$h_up){
										$output = array(
											'status'	=> 0,
											'msg'		=>	'My SQL Error : '.mysql_error()
										);
										echo json_encode($output);exit();
									}
								}
								//product code
								$prod_code = $row['product_code'];
								$r_cust = mysql_query("SELECT `new` FROM `conversion` WHERE `old`= '$prod_code'");
								if($r_cust && mysql_num_rows($r_cust) > 0){
									$data_cust = mysql_fetch_array($r_cust);
									$q_up = "UPDATE `tmp_delivery_schedule` SET `product_code` = '".$data_cust['new']."' WHERE `product_code` = '".$row['product_code']."'";
									$h_up = mysql_query($q_up);
									if(!$h_up){
										$output = array(
											'status'	=> 0,
											'msg'		=>	'My SQL Error : '.mysql_error()
										);
										echo json_encode($output);exit();
									}
								}
								//finish
								$output = array(
									'status'	=> 1,
									'msg'		=>	'Succesfully converted, please push download button to get The File '
								);
								echo json_encode($output);exit();
							}
						}
						//=====
						
					}
				}
			}
		}
		else{
			$output = array(
				'status'	=>	0,
				'msg'		=>	'Please choose 1 file to be converted'
			);
			echo json_encode($output);exit();
		}
	}
	//MODUL BATCH PLANT DOCKET UPLOAD
	elseif($modul == 'docket'){ 
		if (isset($_FILES["myfile"])){
			if ($_FILES["myfile"]["error"] > 0 ) {
				$output = array(
					'status'	=> 0,
					'msg'		=>	'There\'s some errors, maybe file is corrupt'
				);
				echo json_encode($output);exit();
		    } 
		    else{
				$allowedExts = array("csv");
				$temp = explode(".", $_FILES["myfile"]["name"]);
				$extension = end($temp);
				if(!in_array($extension,$allowedExts)){
					$output = array(
						'status'	=> 0,
						'msg'		=>	'Only CSV file allowed to be converted'
					);
					echo json_encode($output);exit();
				}
				else{
					$nama_file = "DOCKET_".date("Ymd_hms").".csv"; //Nama file yang akan di Upload
					if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir . $nama_file)){
						$filename = $upload_dir.$nama_file;
						$handle1 = fopen($filename, "r");
						
						//TRUNCATE tmp_delivery_schedule
						if(!mysql_query("TRUNCATE TABLE `tmp_batch_plant_docket`")){
							$output = array(
								'status'	=> 0,
								'msg'		=>	'My SQL Error : '.mysql_error()
							);
							echo json_encode($output);exit();
						}
						
						$skip=1;
						while (($data = fgetcsv($handle1, 10000, ",")) !== FALSE)
						{
//							echo $data[0].'<br>';
//							echo $skip;
							if($skip >= 2){
//								echo $data[0].'<br>';
//								echo $skip;
								$string = "INSERT INTO `tmp_batch_plant_docket` VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."')";	
								$result = mysql_query($string);
								if(!$result){
									$output = array(
										'status'	=> 0,
										'msg'		=>	'My SQL Error : '.mysql_error()
									);
									echo json_encode($output);exit();
								}
								
							}
							$skip++;
						}
						
						
						//-----

						//CONVERSI ISI TEMP TABLE
						$q = "SELECT * FROM `tmp_batch_plant_docket`";
						$hasil = mysql_query($q);
						if($hasil && mysql_num_rows($hasil) > 0){
							while($row = mysql_fetch_array($hasil)){
								//customer id
								$cust_id = $row['product_code'];
								$r_cust = mysql_query("SELECT `new` FROM `conversion` WHERE `old`= '$cust_id'");
								if($r_cust && mysql_num_rows($r_cust) > 0){
									$data_cust = mysql_fetch_array($r_cust);
									$q_up = "UPDATE `tmp_batch_plant_docket` SET `product_code` = '".$data_cust['new']."' WHERE `product_code` = '".$row['product_code']."'";
									$h_up = mysql_query($q_up);
									if(!$h_up){
										$output = array(
											'status'	=> 0,
											'msg'		=>	'My SQL Error : '.mysql_error()
										);
										echo json_encode($output);exit();
									}
								}
								
								//finish
								$output = array(
									'status'	=> 1,
									'msg'		=>	'Succesfully converted, please push download button to get The File '
								);
								echo json_encode($output);exit();
							}
						}
						//=====
						
					}
				}
			}
		}
		else{
			$output = array(
				'status'	=>	0,
				'msg'		=>	'Please choose 1 file to be converted'
			);
			echo json_encode($output);exit();
		}
	}
	
	//MODUL BATCH PLANT MATERIAL UPLOAD
	elseif($modul == 'material'){ 
		if (isset($_FILES["myfile"])){
			if ($_FILES["myfile"]["error"] > 0 ) {
				$output = array(
					'status'	=> 0,
					'msg'		=>	'There\'s some errors, maybe file is corrupt'
				);
				echo json_encode($output);exit();
		    } 
		    else{
				$allowedExts = array("csv");
				$temp = explode(".", $_FILES["myfile"]["name"]);
				$extension = end($temp);
				if(!in_array($extension,$allowedExts)){
					$output = array(
						'status'	=> 0,
						'msg'		=>	'Only CSV file allowed to be converted'
					);
					echo json_encode($output);exit();
				}
				else{
					$nama_file = "MTRL_".date("Ymd_hms").".csv"; //Nama file yang akan di Upload
					if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir . $nama_file)){
						$filename = $upload_dir.$nama_file;
						$handle1 = fopen($filename, "r");
						
						//TRUNCATE tmp_delivery_schedule
						if(!mysql_query("TRUNCATE TABLE `tmp_batch_plant_material_issue`")){
							$output = array(
								'status'	=> 0,
								'msg'		=>	'My SQL Error : '.mysql_error()
							);
							echo json_encode($output);exit();
						}
						
						$skip=1;
						while (($data = fgetcsv($handle1, 10000, ",")) !== FALSE)
						{
//							echo $data[0].'<br>';
//							echo $skip;
							if($skip >= 2){
//								echo $data[0].'<br>';
//								echo $skip;
								$string = "INSERT INTO `tmp_batch_plant_material_issue` VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."')";	
								$result = mysql_query($string);
								if(!$result){
									$output = array(
										'status'	=> 0,
										'msg'		=>	'My SQL Error : '.mysql_error()
									);
									echo json_encode($output);exit();
								}
								
							}
							$skip++;
						}
						
						
						//-----

						//CONVERSI ISI TEMP TABLE
						$q = "SELECT * FROM `tmp_batch_plant_material_issue`";
						$hasil = mysql_query($q);
						if($hasil && mysql_num_rows($hasil) > 0){
							while($row = mysql_fetch_array($hasil)){
								//finish good code
								$cust_id = $row['finish_good_code'];
								$r_cust = mysql_query("SELECT `new` FROM `conversion` WHERE `old`= '$cust_id'");
								if($r_cust && mysql_num_rows($r_cust) > 0){
									$data_cust = mysql_fetch_array($r_cust);
									$q_up = "UPDATE `tmp_batch_plant_material_issue` SET `finish_good_code` = '".$data_cust['new']."' WHERE `finish_good_code` = '".$row['finish_good_code']."'";
									$h_up = mysql_query($q_up);
									if(!$h_up){
										$output = array(
											'status'	=> 0,
											'msg'		=>	'My SQL Error : '.mysql_error()
										);
										echo json_encode($output);exit();
									}
								}
								
								//material code
								$cust_id = $row['material_code'];
								$r_cust = mysql_query("SELECT `new` FROM `conversion` WHERE `old`= '$cust_id'");
								if($r_cust && mysql_num_rows($r_cust) > 0){
									$data_cust = mysql_fetch_array($r_cust);
									$q_up = "UPDATE `tmp_batch_plant_material_issue` SET `material_code` = '".$data_cust['new']."' WHERE `material_code` = '".$row['material_code']."'";
									$h_up = mysql_query($q_up);
									if(!$h_up){
										$output = array(
											'status'	=> 0,
											'msg'		=>	'My SQL Error : '.mysql_error()
										);
										echo json_encode($output);exit();
									}
								}
								
								//finish
								$output = array(
									'status'	=> 1,
									'msg'		=>	'Succesfully converted, please push download button to get The File '
								);
								echo json_encode($output);exit();
							}
						}
						//=====
						
					}
				}
			}
		}
		else{
			$output = array(
				'status'	=>	0,
				'msg'		=>	'Please choose 1 file to be converted'
			);
			echo json_encode($output);exit();
		}
	}
	
}
else{
	$output = array(
		'status'	=>	0,
		'msg'		=>	'Missing Module Input, probably somebody change the HTML file'
	);
}
?>
<?php
include_once "db.php";
if(isset($_POST['old']) && !empty($_POST['old']) && isset($_POST['new']) && !empty($_POST['new'])){
	extract($_POST);
	//cek db
	$q = "SELECT * FROM `conversion` WHERE `old` = '$old'";
	$result = mysql_query($q);
	if(!$result){
			$output = array(
				'status'	=> 0,
				'msg'		=>	'Error Mysql : '.mysql_error()
			);
	}
	else{
		if(mysql_num_rows($result) > 0){
			$output = array(
				'status'	=> 0,
				'msg'		=>	'Data Already Exist'
			);
		}
		else{
			$q_insert = "INSERT INTO `conversion` VALUE('','$old','$new',NOW(),NOW())";
			$result = mysql_query($q_insert);
			if(!$result){
				$output = array(
					'status'	=> 0,
					'msg'		=>	'Error Mysql : '.mysql_error()
				);
			}
			else{
				$output = array(
					'status'	=> 1,
					'msg'		=>	'Congratulation, Data Has been added'
				);
			}
		}
	}
}
else{
	$output = array(
		'status'	=> 0,
		'msg'		=>	'Field Cannot be empty !'
	);
}
echo json_encode($output);exit();
?>
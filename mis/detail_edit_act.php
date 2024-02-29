<?php
include_once "db.php";
if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['old']) && !empty($_POST['old']) && isset($_POST['new']) && !empty($_POST['new'])){
	extract($_POST);
	$up_q = "UPDATE `conversion` SET `old` = '$old', `new` = '$new' WHERE `id` = '$id'";
	$result = mysql_query($up_q);
	if(!$result){
		$output = array(
			'status' => 0,
			'msg'	=>	'Failed update database, '.mysql_error()
		);
	}
	else{
		$q = "SELECT * FROM `conversion` WHERE `id` = '$id'";
		$result = mysql_query($q);
		$data = mysql_fetch_array($result);
		$output = array(
			'status' => 1,
			'msg'	=>	'Data Updated Successfully',
			'old'	=>	$data['old'],
			'new'	=>	$data['new']
		);
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
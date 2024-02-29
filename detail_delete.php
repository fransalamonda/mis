<?php
include_once "db.php";
if(isset($_GET['id']) && !empty($_GET['id'])){
	extract($_GET);
	$q_delete = "DELETE FROM `conversion` WHERE `id` = '$id'";
	$result = mysql_query($q_delete);
	if(!$result){
		header("Location:list_konversi.php?msg=0");
	}
	else{
		header("Location:list_konversi.php?msg=".$id."");
	}
}
else{
	header("Location:list_konversi.php");
}

?>
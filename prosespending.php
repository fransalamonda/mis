<?php
require_once 'db.php';

	$id = $_POST ['id'];
	$remark = isset($_POST['remark']) ? $_POST['remark'] : '';
	if(!$remark){
		echo "<script>
		   alert('Keterangan Tidak Boleh Kosong');
           window.location = 'schedulePending.php?id=$id';
      </script>";
	}
	$remarks = implode(',',(array)$remark);
	
		


$sql = "UPDATE delivery_schedule SET remark = '$remarks' WHERE id='$id'";

$result = mysqli_query($conns,$sql);

if($result){
	echo "<script>
		   alert('Schedule Berhasil Di Pending!');
           window.location = 'list_delivery_schedule.php';
      </script>";


}else{
  echo "data gagal diubah";
}
?>
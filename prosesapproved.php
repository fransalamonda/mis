<?php
require_once 'db.php';

	$id = $_POST ['id'];
	$satu = $_POST['satu'];
	$dua = $_POST['dua'];
	$tiga = $_POST['tiga'];
	$empat = $_POST['empat'];
	$lima = $_POST['lima'];
	$enam = $_POST['enam'];
	$tujuh = $_POST['tujuh'];
	$delapan = $_POST['delapan'];
	$sembilan = $_POST['sembilan'];
	$sepuluh = $_POST['sepuluh'];
	$sebelas = $_POST['sebelas'];
	$duabelas = $_POST['duabelas'];
	$tigabelas = $_POST['tigabelas'];
	$empatbelas = $_POST['empatbelas'];
	$limabelas = $_POST['limabelas'];
	$enambelas = $_POST['enambelas'];
	$tujuhbelas = $_POST['tujuhbelas'];
	$delapanbelas = $_POST['delapanbelas'];
	$sembilanbelas = $_POST['sembilanbelas'];
	$duapuluh = $_POST['duapuluh'];
	$duasatu = $_POST['duasatu'];
	$duadua = $_POST['duadua'];
	$duatiga = $_POST['duatiga'];
	$duaempat = $_POST['duaempat'];
	$remark = isset($_POST['remark']) ? $_POST['remark'] : '';
	if(!$remark){
		echo "<script>
		   alert('Keterangan Tidak Boleh Kosong');
           window.location = 'carischedule.php?id=$id';
      </script>";
	}
	$remarks = implode(',',(array)$remark);
	
		


$sql = "UPDATE delivery_schedule SET `1`='$satu',`2`='$dua',`3`='$tiga', `4`='$empat', `5`='$lima', `6`='$enam', `7`='$tujuh',`8`='$delapan', `9`='$sembilan', `10`='$sepuluh', `11`='$sebelas', `12`='$duabelas', `13`='$tigabelas',`14`='$empatbelas',`15`='$limabelas', `16`='$enambelas',`17`='$tujuhbelas', `18`='$delapanbelas', `19`='$sembilanbelas',`20`='$duapuluh',`21`='$duasatu',`22`= '$duadua', `23`='$duatiga', `24`='$duaempat',remark = '$remarks' WHERE id='$id'";

$result = mysqli_query($conns,$sql);

if($result){
	echo "<script>
		   alert('Schedule Berhasil Di Approve');
           window.location = 'list_delivery_schedule.php';
      </script>";


}else{
  echo "data gagal diubah";
}
?>
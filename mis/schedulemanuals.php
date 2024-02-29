<?php 
	require_once 'db.php';
	$so_no = $_POST['so_no'];
	$schedule_date = $_POST['schedule_date'];
	$product_code = $_POST['product_code'];
	$sales_name = $_POST['sales_name'];
	$customer_name = $_POST['customer_name'];
	$customer_id = $_POST['customer_id'];
	$project_id = $_POST['project_id'];
	$qty_so = $_POST['qty_so'];
	$project_location = $_POST['project_location'];
	$project_address = $_POST['project_address'];
	$deliv_order_vol = $_POST['deliv_order_vol'];
	$vol = $_POST['vol'];
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

	date_default_timezone_set("Asia/Jakarta");
	$hariini    = date("Y-m-d H:i:s");
	$pukul      = date("H");
	$tanggalupload =date("d/m/Y");

	$sql = "INSERT INTO delivery_schedule VALUES ('".$so_no."','1','Y','".$schedule_date."','".$sales_name."','".$customer_id."','".$customer_name."','".$project_id."','".$project_location."','".$project_address."','','".$product_code."','".$qty_so."','".$deliv_order_vol."','".$vol."','".$satu."','".$dua."','".$tiga."','".$empat."','".$lima."','".$enam."','".$tujuh."','".$delapan."','".$sembilan."','".$sepuluh."','".$sebelas."','".$duabelas."','".$tigabelas."','".$empatbelas."','".$limabelas."','".$enambelas."','".$tujuhbelas."','".$delapanbelas."','".$sembilanbelas."','".$duapuluh."','".$duasatu."','".$duadua."','".$duatiga."','".$duaempat."','',''
			,'','','','F','".$hariini."','cd','')";
	
	$result = mysqli_query($conns,$sql);


	if($result){
		echo "<script>
			   alert('Berhasil Menambahkan Schedule Secara Manual');
			   window.location = 'schedulemanual.php';
		  </script>";
	
	
	}else{
	  echo "data gagal diubah";
	}


 ?>
<?php
//KONEKSI KE DATABASE
$mach = "localhost";
$user = "root";
$pass = "";
$db = "bash";
$conn = mysql_connect($mach,$user,$pass);
if(!$conn){
	die('Mysql Error');
}
else{
	if(isset($_POST['submit']) && !empty($_POST['submit'])){
		$target_path = "D:/xampp/htdocs/mis/excell/".basename($_FILES['filename']['name']);
		echo $target_path; 
		if(move_uploaded_file($_FILES['filename']['tmp_name'],$target_path)){
			echo "<font face=arial size=2>The file ". basename( $_FILES['filename']['name']). " berhasil di upload</font><br>";
		}
	}
}

//print_r($_FILES['filename']);
//echo $_FILES['filename']['tmp_name'];
//echo dirname(__FILE__);
//echo basename(__DIR__) ;
//echo dirname(__FILE__);
/*while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{
	//echo $data[0]."-";
}*/
?>
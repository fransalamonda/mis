
<?php 
$servername = "localhost";
$username = "root";
$password = "";
$db 	= "bash";

$conns = mysqli_connect($servername, $username, $password, $db);

if (!$conns) {
die ("Gagal Terhubung dengan Database :". mysqli_connect_error());
			# code...
		}
 ?>
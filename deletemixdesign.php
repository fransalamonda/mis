<?php 
include 'db.php';
$id = $_GET['id'];

mysqli_query($conns,"DELETE FROM mix_package WHERE product_code='$id'")or die(mysqli_error());
mysqli_query($conns,"DELETE FROM mix_design WHERE product_code='$id'")or die(mysqli_error());
mysqli_query($conns,"DELETE FROM mix_design_composition WHERE product_code='$id'")or die(mysqli_error());
mysqli_query($conns,"DELETE FROM mix_package_composition WHERE product_code='$id'")or die(mysqli_error());
$_SESSION['pesan'] = 'Data berhasil di Delete';
header("location:bom.php?menu=transfer");
?>
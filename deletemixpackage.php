<?php 
include 'db.php';
$id = $_GET['id'];

mysqli_query($conns,"DELETE FROM mix_package_composition WHERE chart_no='$id'")or die(mysqli_error());
 
header("location:bom.php?menu=transfer");
?>
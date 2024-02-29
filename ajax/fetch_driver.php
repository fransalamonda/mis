<?php

include '../db.php';

$output = '';
$sql = "SELECT * FROM  t_truckmixer WHERE driver_id = '".$_POST["no_pol"]."' ORDER BY driver_name ";
$hasil = mysqli_query($conns, $sql);

$output = '';
    while($row = mysqli_fetch_array($hasil))
    {

        $output .= '<input id="" value="'.$row["no_pol"].'" name="no_pol2" type="text" class="form-control input-md"><br><input type="hidden" value="'.$row["driver_name"].'" name="driver_name" type="text" class="form-control input-md">';
    }  

echo $output;

?>

<!-- $output .= '<option value="'.$row["no_pol"].'">'.$row["driver_name"].'</option>'; -->
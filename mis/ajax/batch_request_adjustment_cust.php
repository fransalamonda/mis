<?php
session_start();
include '../inc/constant.php';
if(!IS_AJAX){
    die("Acces Denied");
}
try{
    $plant_id = PLANT_ID;
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$mysqli)        throw new Exception($mysqli->error);
    
    date_default_timezone_set("Asia/Jakarta");
    $yesterday  = date('d/m/Y',strtotime("-1 days"));
    $today      = date('d/m/Y');
    $limajam  = date('H:i',strtotime("-5 hour"));
    $pukul      = date('H:i');
    //echo $limajam; exit();
    $dn_table = "<thead>";
    $dn_table .= "<tr>";
    $dn_table .= "<td><b>#</b></td>";
    $dn_table .= "<td><b>No</b></td>";
    $dn_table .= "<td class='col'><b> Customer Id</b> </td>";
    $dn_table .= "<td class='col'><b> Custumer Name</b> </td>";
    $dn_table.="</thead>";
    $dn_table.="<tbody class='small'>";
    
    $sql_docket = "SELECT DISTINCT `customer_id`, customer_name FROM `delivery_schedule`
                   WHERE `schedule_date` BETWEEN '".$yesterday."' AND '".$today."' ";
//    echo $sql_docket; exit();
    $r_check = $mysqli->query($sql_docket);
    if(!$r_check){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $i = 1;
    while($row_dn = $r_check->fetch_array(MYSQLI_ASSOC)){
                //echo $row_dn['docket_no']; exit();
        $dn_table.="<tr>";
        $dn_table.="<td><input type='radio' class='radio' name='cust' value='".$row_dn['customer_id']."' /></td>";        
        $dn_table.="<td>".$i++."</td>";
        $dn_table.="<td>".$row_dn['customer_id']."</td>";
        $dn_table.="<td>".$row_dn['customer_name']."</td>";
        $dn_table.="</tr>";
    }    
    $i++;
    $dn_table.="</tbody>";
    $output = array(
        "status"    =>  1,
        "msg"       =>  $dn_table
    );
    exit(json_encode($output));
    
} catch (Exception $exc) {
    $output = array(
        "status" => 0,
        "msg"    => $exc->getMessage()
    );
    exit(json_encode($output));
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php
session_start();
include '../inc/constant.php';
if(!IS_AJAX){ die("Access Denied"); }
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
/*
 * CHECKING SESSION
 */

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method is Denied");
    }
    
    $field_list = array("mach_code","product_code");
    
    $field_post = array();
    foreach ($_POST as $key => $value) {
        array_push($field_post, $key);
    }
    
    //cek field yang dikirimkan dalam $_POST
    foreach ($field_list as $value) {
        if(!in_array($value, $field_post)){
            throw new Exception("Missing <b>".$value."</b> field");
        }
    }
    foreach ($field_list as $value) {
        if(empty($_POST[$value])){
            throw new Exception("Field <b>".$value."</b> need to be filled");
        }
    }
    
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$mysqli)        throw new Exception($mysqli->error);
    
    $sql_chart = "SELECT DISTINCT `chart_no` "
            . "FROM `mix_package_composition` "
            . "WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' "
            . "ORDER BY `chart_no`";
    $r_check = $mysqli->query($sql_chart);
    if(!$r_check){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $str_table = "<thead>";
    $str_table .= "<tr>";
    $str_table .= "<td><b>Mat Name</b></td>";
    //table header
        $no_urut=1;//untuk menandai 
        while($row = $r_check->fetch_array(MYSQLI_ASSOC)){
            $str_table .= "<td class='col".$no_urut."'>";
    //        $str_table .=   "<b>".$no_urut++."</b>";
            $sql_bom = "SELECT name_bom FROM `tbl_code_bom` WHERE code_bom = '".$no_urut++."'";
            $res_bom = $mysqli->query($sql_bom);
            if(!$res_bom){
                throw new Exception("Error:".  mysqli_error($mysqli));
            }
            $row_bom = $res_bom->fetch_array(MYSQLI_ASSOC);
            $str_table.="<b>".$row_bom['name_bom']."</b>";
            $str_table .=  "</td>";
        }
    $str_table.="</thead>";
    $str_table.="<tbody class='small'>";

//looping material list
//    $sql = "SELECT `mat_name`,`mat_code` FROM `tbl_master_material`";
    $sql = "SELECT DISTINCT `material_code`,`material_name` FROM `mix_package_composition`";
    $res_mat = $mysqli->query($sql);
    if(!$res_mat){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }    
    $no_urut=1;
    while($row_mat = $res_mat->fetch_array(MYSQLI_ASSOC)){
        $str_table.="<tr>";
        $str_table.="<td>".$row_mat['material_name']."</td>";   

        //re-execute to get chart_no list
        $r_check = $mysqli->query($sql_chart);
        while($row = $r_check->fetch_array(MYSQLI_ASSOC)){
            $sql = "SELECT `mix_qty` "
            . "FROM `mix_package_composition` "
            . "WHERE `mch_code`='".$_POST['mach_code']."' "
                    . "AND `product_code`='".$_POST['product_code']."' "
                    . "AND `chart_no`='".$row['chart_no']."' "
                    . "AND `material_code`='".$row_mat['material_code']."'";
            $res_qty = $mysqli->query($sql);
            if(!$res_qty){
                throw new Exception("Error:".  mysqli_error($mysqli));
            }
            $row_qty = $res_qty->fetch_array(MYSQLI_ASSOC);
            if($res_qty->num_rows > 0)
            $str_table.="<td  class='col".$no_urut++."'>".$row_qty['mix_qty']."</td>";
            else $str_table.="<td class='col".$no_urut++."'>0</td>";
        }
        $str_table.="</tr>";
        $no_urut=1;
    }
    $str_table.="</tbody>";
    
    //re-execute to get chart_no list
    $r_check = $mysqli->query($sql_chart);
    $str_table.="<tfoot>";
    $str_table.="<tr>";
    $str_table.="<td></td>";
    
    
//    $no_urut = 1;
//    while($row = $r_check->fetch_array(MYSQLI_ASSOC)){
//        $str_table.="<td class='col".$no_urut."'>";
//        if(isset($object) && ($object->group_id==1 || $object->group_id==4)){
//            $str_table.="<a class='delete btn btn-xs btn-danger' data-no_urut='".$no_urut."' data-chart_no='".$row['chart_no']."' data-mch_code='".$_POST['mach_code']."' data-product_code='".$_POST['product_code']."'><i class='fa fa-trash'></i></a> ";
//        }
//        $str_table.= "<a class='transfer btn btn-xs btn-success' data-no_urut='".$no_urut++."' data-chart_no='".$row['chart_no']."' data-mch_code='".$_POST['mach_code']."' data-product_code='".$_POST['product_code']."'><i class='fa fa-send'></i></a>";
//        $str_table.="</td>";
//    }
    $str_table.="</tr>";
    $str_table.="</tfoot>";
    
    //get MIX Name
    $sql = "SELECT * FROM `mix_package` WHERE `product_code`='".$_POST['product_code']."' AND `mch_code`='".$_POST['mach_code']."'";
    $r_mix = $mysqli->query($sql);
    $mix_name = "";
    while($r_o = $r_mix->fetch_array(MYSQLI_ASSOC)){ $mix_name = $r_o['description']; }
    $mix_str = "<div class=\"component\">"
            . "<div class='form-group'>"
            . "<label class='col-md-2 control-label' for='textinput'>Product Code</label>"
            . "<div class=\"col-md-4\">"
            . "<input class='form-control' readonly='' value='".$_POST['product_code']."' />"
            . "</div>"
            . "</div>"
            . "</div>";
    $mix_str .= "<div class=\"component\">"
            . "<div class='form-group'>"
            . "<label class='col-md-2 control-label' for='textinput'>Product Name</label>"
            . "<div class=\"col-md-4\">"
            . "<input class='form-control' readonly='' value='".$mix_name."' />"
            . "</div>"
            . "</div>"
            . "</div>";
    $output = array(
        "status"    =>  1,
        "msg"       =>  $str_table,
        "mix_str"   =>  $mix_str
    );
    exit(json_encode($output));
      
} catch (Exception $exc) {
    $output = array(
        "status"    =>  0,
        "msg"       =>  $exc->getMessage()
    );
    exit(json_encode($output));
}
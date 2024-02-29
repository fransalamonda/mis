<?php
session_start();
include '../inc/constant.php';
if(!IS_AJAX){
    die("Access Denied");
}
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
/*
 * CHECKING SESSION
 */
try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method is Denied");
    } 
    $field_list = array("prod","docket");
//    print_r($field_list);
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
            . "WHERE `mch_code`='".$_POST['prod']."' AND `product_code`='".$_POST['docket']."' "
            . "ORDER BY `chart_no`";
//    echo $sql_chart; exit();
    $r_check = $mysqli->query($sql_chart);
    if(!$r_check){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $str_table = "<thead>";
    $str_table .= "<tr>";
    $str_table .= "<td><b>Material Code</b></td>";
    $str_table .= "<td><b>Design Qty</b></td>";
    $str_table .= "<td><b>Actual Qty</b></td>";
    $str_table .= "<td><b>Target Qty</b></td>";
    $str_table.="</thead>";
    $str_table.="<tbody class='small'>";
    $sql_bts = "SELECT * FROM `batch_transaction_detail` WHERE `docket_no`='".$_POST['docket']."'";
    $bts = $mysqli->query($sql_bts);
    if(!$bts){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $no_urut=1;
    while($row_mat = $bts->fetch_array(MYSQLI_ASSOC)){
            $str_table.="<tr>";
            $str_table.="<td class='col'>".$row_mat['material_code']."</td>";
            $str_table.="<td class='col'>".$row_mat['design_qty']."</td>";
            $str_table.="<td class='col'>".$row_mat['actual_qty']."</td>";
            $str_table.="<td class='col'>".$row_mat['target_qty']."</td>";
            $str_table.="</tr>";
    }
$no_urut++;

//looping material list
//    $sql = "SELECT DISTINCT `material_code`,`material_name` FROM `mix_package_composition`";
//    $res_mat = $mysqli->query($sql);
//    if(!$res_mat){
//        throw new Exception("Error:".  mysqli_error($mysqli));
//    }
//    
//    $no_urut=1;
//    while($row_mat = $res_mat->fetch_array(MYSQLI_ASSOC)){
//        $str_table.="<tr>";
//        $str_table.="</td>";
//        //re-execute to get chart_no list
//        $r_check = $mysqli->query($sql_chart);
//        while($row = $r_check->fetch_array(MYSQLI_ASSOC)){
//            $sql = "SELECT `mix_qty` "
//            . "FROM `mix_package_composition` "
//            . "WHERE `mch_code`='".$_POST['mach_code']."' "
//                    . "AND `product_code`='".$_POST['product_code']."' "
//                    . "AND `chart_no`='".$row['chart_no']."' "
//                    . "AND `material_code`='".$row_mat['material_code']."'";
//            $res_qty = $mysqli->query($sql);
//            if(!$res_qty){
//                throw new Exception("Error:".  mysqli_error($mysqli));
//            }
//            $row_qty = $res_qty->fetch_array(MYSQLI_ASSOC);
//            if($res_qty->num_rows > 0)
//            $str_table.="</td>";
//            else $str_table.="<td class='col".$no_urut++."'>0</td>";
//        }
//        $str_table.="</tr>";
//        $no_urut=1;
//    }
    $str_table.="</tbody>";

    $mix_str = "<div class=\"component\">"
            . "<div class='form-group'>"
            . "<label class='col-md-2 control-label' for='textinput'>Docket No</label>"
            . "<div class=\"col-md-4\">"
            . "<input class='form-control' readonly='' value='".$_POST['docket']."' />"
            . "</div>"
            . "</div>"
            . "</div>";
    $mix_str .= "<div class=\"component\">"
            . "<div class='form-group'>"
            . "<label class='col-md-2 control-label' for='textinput'>Product Name</label>"
            . "<div class=\"col-md-4\">"
            . "<input class='form-control' readonly='' value='".$_POST['prod']."' />"
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
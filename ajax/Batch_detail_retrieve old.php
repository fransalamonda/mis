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
    
//    $sql_chart = "SELECT DISTINCT `chart_no` "
//            . "FROM `mix_package_composition` "
//            . "WHERE `mch_code`='".$_POST['prod']."' AND `product_code`='".$_POST['docket']."' "
//            . "ORDER BY `chart_no`";
//    echo $sql_chart; exit();
//    $r_check = $mysqli->query($sql_chart);
//    if(!$r_check){
//        throw new Exception("Error:".  mysqli_error($mysqli));
//    }
    $str_table = "<thead>";
    $str_table .= "<tr>";
    $str_table .= "<td><b>Material Code</b></td>";
    $str_table .= "<td><b>Material Name</b></td>";
    $str_table .= "<td><b>Actual Qty</b></td>";
    $str_table .= "<td><b>Target Qty</b></td>";
    $str_table .= "<td><b>Design Qty</b></td>";
    $str_table.="</thead>";
    $str_table.="<tbody class='small'>";
    $sql_bts = "SELECT * FROM `batch_transaction_detail` WHERE `docket_no`='".$_POST['docket']."'";
    $bts = $mysqli->query($sql_bts);
    if(!$bts){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $no_urut=1;
    while($row_mat = $bts->fetch_array(MYSQLI_ASSOC)){
//        $sql_chart_nama = "SELECT * "
//            . "FROM `mix_design_composition` "
//            . "WHERE `mch_code`='".PLANT_ID."' AND `product_code`='".$_POST['prod']."' ";
////    echo $sql_chart; exit();
//    $nama = $mysqli->query($sql_chart_nama);
//    if(!$nama){
//        throw new Exception("Error:".  mysqli_error($mysqli));
//    }
//    while($row_name = $nama->fetch_array(MYSQLI_ASSOC)){
            $str_table.="<tr>";
            $str_table.="<td class='col'>".$row_mat['material_code']."</td>";
             if($row_mat['material_code'] == "0000-100094") $material_name = "SAND"; 
            elseif($row_mat['material_code'] == "0000-100089") $material_name = "MSAND"; //
            elseif($row_mat['material_code'] == "0000-100090") $material_name = "AGG1"; //
            elseif($row_mat['material_code'] == "0000-100091") $material_name = "AGG2"; //
            elseif($row_mat['material_code'] == "0000-100092") $material_name = "AGG3"; //
            elseif($row_mat['material_code'] == "0000-100009") $material_name = "AGG4";
            elseif($row_mat['material_code'] == "0000-100038") $material_name = "AGG5";
            elseif($row_mat['material_code'] == "0000-100011") $material_name = "CEMENT";
            elseif($row_mat['material_code'] == "0000-000015") $material_name = "FLY ASH";
            elseif($row_mat['material_code'] == "0000-100030") $material_name = "WATER";//WATER
            elseif($row_mat['material_code'] == "0000-100093") $material_name = "STONEDUST";//
            elseif($row_mat['material_code'] == "0002-100001") $material_name = "P121R";
            elseif($row_mat['material_code'] == "0002-100201") $material_name = "S523N";
            elseif($row_mat['material_code'] == "0002-100006") $material_name = "RT6P";
            elseif($row_mat['material_code'] == "0002-100409") $material_name = "SK163";
            elseif($row_mat['material_code'] == "0002-100410") $material_name = "SIKATARD930";
            elseif($row_mat['material_code'] == "0002-100402") $material_name = "VIS 1003";
            elseif($row_mat['material_code'] == "0002-100407") $material_name = "VIS 1221R";
            elseif($row_mat['material_code'] == "0002-100416") $material_name = "VIS 7080P";
            elseif($row_mat['material_code'] == "0002-100010") $material_name = "SR3200";
	        elseif($row_mat['material_code'] == "0002-100430") $material_name = "VIS 3660LR";
            elseif($row_mat['material_code'] == "0002-100432") $material_name = "VISCO8300";
            elseif($row_mat['material_code'] == "0002-100015") $material_name = "P83";
            elseif($row_mat['material_code'] == "0002-100434") $material_name = "MAST1100";
            elseif($row_mat['material_code'] == "0002-100435") $material_name = "MAST1007";
            elseif($row_mat['material_code'] == "0002-100436") $material_name = "ACE8381";
            elseif($row_mat['material_code'] == "0002-100437") $material_name = "GENR8212";
            elseif($row_mat['material_code'] == "0002-100438") $material_name = "GET702";
            elseif($row_mat['material_code'] == "0002-100439") $material_name = "GENB1714";          
            elseif($row_mat['material_code'] == "0002-100440") $material_name = "FLBNF-15";
            elseif($row_mat['material_code'] == "0002-100441") $material_name = "FLBPD-19";
            else{ $material_name = "" ; }
            $str_table.="<td class='col'>".$material_name."</td>";
            $str_table.="<td class='col'>".$row_mat['actual_qty']."</td>";
            $str_table.="<td class='col'>".$row_mat['target_qty']."</td>";
            $str_table.="<td class='col'>".$row_mat['design_qty']."</td>";
            
            $str_table.="</tr>";
//            }
    }
$no_urut++;
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
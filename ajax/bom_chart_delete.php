<?php
session_start();
include '../inc/constant.php';
if(!IS_AJAX){
    die("Access Denied");
}

/*
 * CHECKING SESSION
 */
//if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
//    $output = array(
//        "status"    =>  0,
//        "msg"       =>  "Silahkan login terlebih dahulu"
//    );
//    exit(json_encode($output));
//}
//$object = (object)$_SESSION['login'];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method is Denied");
    }
    if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
        throw new Exception("Session Berakhir, silahkan login ulang!");
    }
    $field_list = array("mach_code","product_code","chart_no");
    
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
    
    //validasi jumlah chart per product
    $sql_chart = "SELECT DISTINCT `chart_no` "
            . "FROM `mix_package_composition` "
            . "WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' "
            . "ORDER BY `chart_no`";
    $r_check = $mysqli->query($sql_chart);
    if($r_check->num_rows == 0){throw new Exception("ERROR : Data tidak ditemukan");}
    if($r_check->num_rows == 1){throw new Exception("ERROR : Produk harus memiliki minimal satu variasi komposisi");}

    $sql_chart = "SELECT DISTINCT `chart_no` "
            . "FROM `mix_package_composition` "
            . "WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' "
            . "AND `chart_no`='".$_POST['chart_no']."' "
            . "ORDER BY `chart_no`";
    $r_check = $mysqli->query($sql_chart);
    if(!$r_check){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    if($r_check->num_rows == 0){throw new Exception("ERROR : Data tidak ditemukan");}
    
    //proses hapus
    $sql_hapus = "DELETE FROM `mix_package_composition` WHERE `mch_code`='".$_POST['mach_code']."' AND `product_code`='".$_POST['product_code']."' "
            . "AND `chart_no`='".$_POST['chart_no']."' ";
    $r_delete = $mysqli->query($sql_hapus);
    if(!$r_delete){
        throw new Exception("Error:".  mysqli_error($mysqli));
    }
    $output = array(
        "status"    =>  1,
        "msg"       =>  "Data Berhasil dihapus",
    );
    exit(json_encode($output));
      
} catch (Exception $exc) {
    $output = array(
        "status"    =>  0,
        "msg"       =>  $exc->getMessage()
    );
    exit(json_encode($output));
}

exit();
/*
 * VALIDATION
 */
//required fields
$fields = array(
    "mach_code","product_code"
);
$isi = $_POST;

$posted_fields = array();
foreach ($isi as $key => $value) {
    array_push($posted_fields, $key);
}
foreach ($fields as $value) {
    if(!in_array($value, $posted_fields)){
        $output = array(
            "status"    =>  0,
            "msg"       =>  "Nilai kolom <i>".$value."</i> tidak ditemukan"
        );
        exit(json_encode($output));
    }
}
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);


/*
 * CHECKING DB
 */
$q_check = "SELECT DISTINCT `chart_no` "
        . "FROM `mix_package_composition` "
        . "WHERE `mch_code` = '".$_POST['mach_code']."' "
        . "AND `product_code`='".$_POST['product_code']."'";
$r_check = $mysqli->query($q_check);
if(!$r_check){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Error:".  mysqli_error($mysqli)
    );
    exit(json_encode($output));
}
if($r_check->num_rows == 0){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Kode <b>".$_POST['idcode']."</b> tidak ditemukan!"
    );
    exit(json_encode($output));
}
$select_tag = "<select id=\"kode_proses\" name=\"kode_proses\" class=\"form-control\">";
$select_tag.="<option value=\"\"> - Pilih kategori - </option>";
while($row = $r_check->fetch_array(MYSQLI_ASSOC)){
    $select_tag.="<option value=\"".$row['code']."\"> ".$row['desc']." </option>";
}
$select_tag.="</select>";

$output = array(
    "status"    =>  1,
    "msg"       =>  $select_tag
);
exit(json_encode($output));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


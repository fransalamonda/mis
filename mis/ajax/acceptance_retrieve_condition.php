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



/*
 * VALIDATION
 */
//required fields
$fields = array(
    "idcode"
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
$q_check = "SELECT `code`,`id_acceptance`,`desc` FROM `tbl_code_acceptance` WHERE `id_acceptance` = '".$_POST['idcode']."'";
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


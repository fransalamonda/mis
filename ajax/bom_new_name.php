<?php
session_start();
include '../inc/constant.php';
include '../lib/mis.php';
if(!IS_AJAX){
    die("Access Denied");
}
/*
 * CHECKING SESSION
 */
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Silahkan login terlebih dahulu"
    );
    exit(json_encode($output));
}
$object = (object)$_SESSION['login'];
$mis = new mis();
/*
 * VALIDATION
 */
//required fields
$fields = array("code","nameversion","desc");
$isi = $_POST;
$isi_code = $_POST['code'];
$posted_fields = array();

foreach ($isi as $key => $value) {
    array_push($posted_fields, $key);
    if($mis->special_char($isi_code)){
        $output = array(
            "status"    =>  0,
            "msg"       =>  "Code hanya boleh menggunakan Alphabet atau angka"
        );
        exit(json_encode($output));
    }
}
//if($mis->special_char($isi_code)){
//          $output = array(
//            "status"    =>  0,
//            "msg"       =>  "Inputan hanya boleh menggunakan Alphabet atau angka"
//        );
//        exit(json_encode($output));
//}
        
foreach ($fields as $value) {
    if(!in_array($value, $posted_fields)){
        $output = array(
            "status"    =>  0,
            "msg"       =>  "Nilai kolom <i>".$value."</i> tidak ditemukan"
        );
        exit(json_encode($output));
    }
    
}

//input regex special char validation
if($mis->special_char($_POST['code']));

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);


/*
 * CHECKING DB
 */
$q_check = "SELECT * FROM `tbl_code_bom` WHERE `code_bom` = '".$_POST['code']."'";
$r_check = $mysqli->query($q_check);
if(mysqli_num_rows($r_check) > 0){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Kode <b>".$_POST['code']."</b> sudah digunakan"
    );
    exit(json_encode($output));
}

/*
 * SAVE TO DATABASE
 */
$mysqli->autocommit(FALSE);
date_default_timezone_set("Asia/Jakarta");
$current_date_time = date("Y-m-d H:i:s");

$q_save = "INSERT INTO `tbl_code_bom` "
        . "(`code_bom`,`name_bom`, `desc`, `datecreated`, `datemodified`, `createdby`, `modifiedby` )  "
        . "VALUES (   '".$_POST['code']."',   "
        . "'".$_POST['nameversion']."',   "
        . "'".$_POST['desc']."',   "
        . "'".$current_date_time."',   "
        . "NULL,   "
        . "'".$object->id_user."',   "
        . "NULL )";
$r_save = $mysqli->query($q_save);
if(!$r_save){
    $mysqli->rollback();
    $output = array(
        "status"    =>  0,
        "msg"       => "Mysql Error : ".mysqli_error($mysqli)
    );
    exit(json_encode($output));
}


/*
 * SUCCESSFULLY SAVE DATA
 */
$mysqli->commit();
$output = array(
    "status"    =>  1,
    "msg"       =>  "Kode BOM baru berhasil disimpan, halaman akan di-refresh"
);
exit(json_encode($output));

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


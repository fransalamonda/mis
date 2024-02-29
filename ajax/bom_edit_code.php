<?php
session_start();
include '../inc/constant.php';
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



/*
 * VALIDATION
 */
//required fields
$fields = array(
    "code","nameversion","desc"
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
$q_check = "SELECT * FROM `tbl_code_bom` WHERE `code_bom` = '".$_POST['code']."'";
//echo $q_check; exit();
$r_check = $mysqli->query($q_check);
if(mysqli_num_rows($r_check) == 0){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "kode <b>".$_POST['code']."</b> tidak ditemukan"
    );
    exit(json_encode($output));
}

/*
 * SAVE TO DATABASE
 */
$mysqli->autocommit(FALSE);
date_default_timezone_set("Asia/Jakarta");
$current_date_time = date("Y-m-d H:i:s");

$q_update = "UPDATE  `tbl_code_bom` SET  "
        . "`name_bom` = '".$_POST['nameversion']."',  "
        . "`desc` = '".$_POST['desc']."',  "
        . "`datemodified` = '".$current_date_time."',  "
        . "`modifiedby` = '".$object->id_user."' "
        . "WHERE `code_bom` = '".$_POST['code']."' ; ";
$r_up = $mysqli->query($q_update);
if(!$r_up){
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
    "msg"       =>  "Kode BOM <b>".$_POST['code']."</b> baru berhasil diperbarui, halaman akan di-refresh"
);
exit(json_encode($output));

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


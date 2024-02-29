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
    "id","pol","driver","telp"
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
$q_check = "SELECT `driver_id` FROM `t_truckmixer` WHERE `driver_id` = '".$_POST['id']."'";
$r_check = $mysqli->query($q_check);
if(mysqli_num_rows($r_check) == 0){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Data dengan kode <b>".$_POST['id']."</b> tidak ditemukan"
    );
    exit(json_encode($output));
}



/*
 * SAVE TO DATABASE
 */
$mysqli->autocommit(FALSE);
date_default_timezone_set("Asia/Jakarta");
$current_date_time = date("Y-m-d H:i:s");

$q_update ="UPDATE t_truckmixer SET no_pol='".$_POST['pol']."',driver_name='".$_POST['driver']."',no_telp='".$_POST['telp']."' where driver_id='".$_POST['id']."'";

$r_up = $mysqli->query($q_update);
$r_up = 1;

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
    "msg"       =>  "plat nomor <b>".$_POST['pol']."</b> baru berhasil diperbarui, halaman akan di-refresh"
);
exit(json_encode($output));

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


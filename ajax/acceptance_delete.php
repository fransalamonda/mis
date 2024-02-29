<?php
/*
 * author ilham.dinov
 * 150106
 * Delete Batch Transaction Acceptance Code
 */
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
    "code"
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
 * CHECKING DATA
 */
$q_check = "SELECT `code` FROM `tbl_code_acceptance` WHERE `code` = '".$_POST['code']."'";
$r_check = $mysqli->query($q_check);
if(mysqli_num_rows($r_check) == 0){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "data <b>".$_POST['newcode']."</b> sudah dihapus, silahkan reload halaman ini"
    );
    exit(json_encode($output));
}

/*
 * CHECKING DATA IN TRANSACTION TABLE
 */
$q_check_bt = "SELECT `request_no` FROM `batch_transaction2` WHERE `process_code` = '".$_POST['code']."'";
$r_check_bt = $mysqli->query($q_check_bt);
if(mysqli_num_rows($r_check_bt) > 0){
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Data <b>".$_POST['code']."</b> tidak dapat dihapus, karena sudah tersimpan dalam transaksi"
    );
    exit(json_encode($output));
}
/*
 * DELETE FROM DATABASE
 */
$mysqli->autocommit(FALSE);
date_default_timezone_set("Asia/Jakarta");
$current_date_time = date("Y-m-d H:i:s");

$q_del = "DELETE FROM `tbl_code_acceptance` WHERE `code` = '".$_POST['code']."'";
$r_del = $mysqli->query($q_del);
if(!$r_del){
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
    "msg"       =>  "Kode Acceptance ".$_POST['code']." berhasil dihapus, halaman akan direfresh"
);
exit(json_encode($output));

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


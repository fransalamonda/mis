<?php
session_start();
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    $output = array(
        'status'    =>  0,
        'msg'       =>  "Session berakhir, silahkan login kembali"
    );
    exit(json_encode($output));
}

$object = (object)$_SESSION['login'];
//print_r($object);exit();
include '../inc/constant.php';
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
if($is_ajax){
    if(isset($_POST['opass']) && !empty($_POST['opass']) && isset($_POST['npass']) && !empty($_POST['npass']) && isset($_POST['cpass']) && !empty($_POST['cpass'])){
        
        if($_POST['npass'] != $_POST['cpass']){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Konfimasi password baru salah!"
            );
            exit(json_encode($output));
        }
        
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno){
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->connect_error
            );
            exit(json_encode($output));
        }
        //cek isi database
        $string_cari = "SELECT * FROM `tbl_user` WHERE `id_user` = '".$object->id_user."' AND `password` = '".md5($_POST['opass'])."' ";
        $result_cari = $mysqli->query($string_cari);
        if(!$result_cari){
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->error
            );
            exit(json_encode($output));
        }
        if($result_cari->num_rows == 0){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Password lama anda salah"
            );
            exit(json_encode($output));
        }
        //update password
        $string_reset = "UPDATE `tbl_user` SET `password` = '".md5($_POST['npass'])."' WHERE `id_user` = '".$object->id_user."' ";
        $result_reset = $mysqli->query($string_reset);
        if(!$result_reset){
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->error
            );
            exit(json_encode($output));
        }
        
        $output = array(
            "status"    =>  1,
            "msg"       =>  "Password user ".$object->id_user." berhasil diubah"
        );
        exit(json_encode($output));
    }
    
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Semua Kolom harus diisi"
    );
    exit(json_encode($output));
}
else{
    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php
session_start();
include '../inc/constant.php';
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
if($is_ajax){
    if(isset($_POST['id_user']) && !empty($_POST['id_user'])){
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->connect_error
            );
            exit(json_encode($output));
        }
        //cek isi database
        $string_cari = "SELECT * FROM `tbl_user` WHERE `id_user` = '".$_POST['id_user']."' ";
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
                'msg'       =>  "Data tidak ditemukan, silahkan refresh halaman ini"
            );
            exit(json_encode($output));
        }
        
        //update password
        $string_reset = "UPDATE `tbl_user` SET `password` = md5('12345') WHERE `id_user` = '".$_POST['id_user']."' ";
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
            "msg"       =>  "Password user ".$_POST['id_user']." berhasil direset"
        );
        exit(json_encode($output));
    }
    
    $output = array(
        "status"    =>  0,
        "msg"       =>  "Missing ID User"
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


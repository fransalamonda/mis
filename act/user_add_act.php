<?php
session_start();
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    $output = array(
        'status'    =>  0,
        'msg'       =>  "Session berakhir, silahkan login kembali"
    );
    exit(json_encode($output));
}
include '../inc/constant.php';
include '../lib/mis.php';
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
if($is_ajax){
    //instance
    $mis = new mis();
    
    if(isset($_POST['iduser']) && !empty($_POST['iduser']) && isset($_POST['group']) && !empty($_POST['group']) && isset($_POST['name']) && !empty($_POST['name'])){
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->connect_error
            );
            exit(json_encode($output));
        }
        
        if($mis->special_char($_POST['iduser']) || $mis->special_char($_POST['group']) || $mis->special_char($_POST['name'])){
            $output = array(
                "status"    =>  0,
                "msg"       =>  "<b>Error</b> : Inputan hanya boleh menggunakan Alphabet atau angka"
                );
            exit(json_encode($output));
        }
        
        //cek isi database
        $string_cari = "SELECT * FROM `tbl_user` WHERE `id_user` = '".$_POST['iduser']."' ";
        $result_cari = $mysqli->query($string_cari);
        if(!$result_cari){
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->error
            );
            exit(json_encode($output));
        }
        if($result_cari->num_rows > 0){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "ID sudah digunakan"
            );
            exit(json_encode($output));
        }
        
        //insert
        $string_insert = "INSERT INTO `bash`.`tbl_user` (`id_user`,`group_id`,`password`,`username`,`datecreated`) "
                . "VALUES('".$_POST['iduser']."','".$_POST['group']."','".  md5("12345")."','".$_POST['name']."','datecreated') ;";
        $result_insert = $mysqli->query($string_insert);
        if(!$result_insert){
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->error
            );
            exit(json_encode($output));
        }
        
        //
        $output = array(
            'status'    =>  1,
            'msg'       =>  "Data berhasil ditambahkan"
        );
        exit(json_encode($output));
    }
    $output = array(
        'status'    =>  0,
        'msg'       =>  'Kolom <b>ID User & Group</b> Harus diisi!'
    );
    exit(json_encode($output));
}
else{
    die("cannot access directly");
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
include '../inc/constant.php';
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
if($is_ajax){
    if(isset($_POST['jam']) && !empty($_POST['jam']) && isset($_POST['menit']) && !empty($_POST['menit']) && isset($_POST['ap']) && !empty($_POST['ap'])){
        if(strtolower($_POST['ap']) != "am" && strtolower($_POST['ap']) != "pm"){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Data AM/PM tidak valid, silahkan refresh halaman ini"
            );
            exit(json_encode($output));
        }
        //penggabungan jam dan menit
        $time = "";
        $hour = $_POST['jam'];
        $minute = $_POST['menit'];
        if(strtolower($_POST['ap']) == "pm"){
            $hour+=12;
        }
        else{
            $hour = "0".$hour;
        }
        $time = $hour.":".$minute.":00";
        
//        exit($time);
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->connect_error
            );
            exit(json_encode($output));
        }
        //cek isi database
        $string_cari = "SELECT `jam` FROM `tbl_schedule` WHERE `jam` = '".$time."' ";
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
                'msg'       =>  "Jadwal pukul ".$time." sudah digunakan"
            );
            exit(json_encode($output));
        }
        
        //insert
        $string_insert = "INSERT INTO `tbl_schedule` "
                . "(`id`, `jam`, `datecreated`, `createdby`,`datemodified`,`modifiedby`)"
                . "VALUES(NULL,'".$time."','".date("Y-m-d h:i:s")."','".$object->id_user."','".date("Y-m-d h:i:s")."','".$object->id_user."') ;";
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
            'msg'       =>  "Data Schedule berhasil ditambahkan"
        );
        exit(json_encode($output));
    }
    $output = array(
        'status'    =>  0,
        'msg'       =>  'Semua Kolom Harus diisi Harus diisi!'
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


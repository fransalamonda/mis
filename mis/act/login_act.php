<?php
session_start();
include '../inc/constant.php';
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
if($is_ajax){
    if(isset($_POST['iduser']) && !empty($_POST['iduser']) && isset($_POST['pass']) && !empty($_POST['pass'])){
        //connect to mysql
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            $output = array(
                'status'    =>0,
                'msg'       =>  "<span class='text-danger small'>Gagal Koneksi Ke MySQL Server</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
            );
            exit(json_encode($output));
        }
        $pass = md5($_POST['pass']);
        $string_login = "SELECT * FROM `tbl_user` WHERE `id_user` = '".$_POST['iduser']."' AND `password` = '".$pass."'";
        $result = $mysqli->query($string_login);
        if(!$result){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Mysql Error : ".mysqli_error($mysqli)
            );
            exit(json_encode($output));
        }
        if($result->num_rows == 0){
            $output = array(
                'status'    =>  0,
                'msg'       =>  "Kombinasi ID dan Password salah"
            );
            exit(json_encode($output));
        }
        $session_data = array();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $session_data = $row;
        }
        $_SESSION["login"] = $session_data;
        
        
//        $sql_insert = "insert into `bash`.`log`(`username`,`timein`,`timeout`)values ('".$_POST['iduser']."','".date('Y-m-d H:i:s')."','')";
//        $r_insert = $mysqli->query($sql_insert);
//        if(!$r_insert){
//            $output = array(
//                'status'    =>  0,
//                'msg'       =>  "Mysql Error : ".mysqli_error($mysqli)
//            );
//            exit(json_encode($output));
//        } 
        $output = array(
                'status'    =>  1,
                'msg'       =>  "Berhasil Login"
            );
            exit(json_encode($output));
    }
    
    $output = array(
        'status'    =>  0,
        'msg'       =>  "ID User dan Password harus diisi"
    );
    exit(json_encode($output));
}
else{
    die("You Cannot access directly");
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


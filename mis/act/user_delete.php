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
        $string_delete = "DELETE FROM `tbl_user` WHERE `id_user` = '".$_POST['id_user']."' ";
        $result_delete = $mysqli->query($string_delete);
        if(!$result_delete){
            $output = array(
                'status'    =>  0,
                'msg'       =>  $mysqli->error
            );
            exit(json_encode($output));
        }
        
        $output = array(
            "status"    =>  1,
            "msg"       =>  "Data berhasil dihapus"
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


<?php
$conn= mysql_connect("localhost","root","");
mysql_select_db("bash");
    if(isset($_POST['mach_code']) && !empty($_POST['mach_code']) && isset($_POST['desc']) && !empty($_POST['desc']))
    {
        extract($_POST);
        //cek machine code di database
        $query_select = "SELECT * FROM `machine` WHERE `mach_code` = '".$mach_code."'";
        $result_select = mysql_query($query_select);
        if(!$result_select){
            $output = array(
                'status'    =>  0,
                'msg'       =>  'Error : '.  mysql_error()
            );
            echo json_encode($output);
            exit();
        }
        $count_select = mysql_num_rows($result_select);
        if($count_select > 0){
            $output = array(
                'status'    =>  0,
                'msg'       =>  '<b>Machine Code</b> sudah digunakan'
            );
            echo json_encode($output);
            exit();
        }
        
        
        $query_insert = "INSERT INTO `bash`.`machine` (`mach_code`,`desc`) "
                . "VALUES ('".$mach_code."','".$desc."');";
        $result = mysql_query($query_insert);
        if($result){
            $output = array(
                'status'    =>  1,
                'msg'       =>  'Data berhasil ditambahkan!'
            );
            echo json_encode($output);
        }
        else{
            $output = array(
                'status'    =>  0,
                'msg'       =>  'Error : '.  mysql_error()
            );
            echo json_encode($output);
        }
        exit();
    }
    else{
        $output = array(
            'status'    =>  0,
            'msg'       =>  'Kolom <b>Machine Code  &  Keterangan</b> Harus diisi!'
        );
        echo json_encode($output);
    }
	mysql_close($conn);
?>
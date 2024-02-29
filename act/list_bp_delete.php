<?php
$conn = mysql_connect("localhost","root","");
mysql_select_db("bash");
    if(isset($_POST['mach_code']) && !empty($_POST['mach_code']))
    {
        extract($_POST);
        //cek machine code di database
        $query_delete = "DELETE FROM `machine` WHERE `mach_code` = '".$mach_code."'";
        $result_delete = mysql_query($query_delete);
        if(!$result_delete){
            $output = array(
                'status'    =>  0,
                'msg'       =>  'Error : '.  mysql_error()
            );
            echo json_encode($output);
            exit();
        }
        //jika berhasil
        $output = array(
            'status'    =>  1,
            'msg'       =>  'Data <b>'.$mach_code.'</b> berhasil dihapus!'
        );
        echo json_encode($output);
        exit();
    }
    else{
        $output = array(
            'status'    =>  0,
            'msg'       =>  'Missing ID!'
        );
        echo json_encode($output);
    }
    mysql_close($conn);
?>
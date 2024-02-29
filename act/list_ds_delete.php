<?php
include '../db.php';
    if(isset($_POST['so_no']) && !empty($_POST['so_no']))
    {
        extract($_POST);
        $so = substr($_POST['so_no'],0,-10);
        $tanggal = substr($_POST['so_no'],-10);
//        echo $tanggal; exit();
        
        //cek table delivery schedule
        $q_string = "SELECT * FROM `delivery_schedule` WHERE `so_no`='".$so."' AND schedule_date ='".$tanggal."'";
//        echo $q_string; exit();
        $result = mysqli_query($conns,$q_string);
        if(!$result){
            throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error());
        }	
        $jumlah_data_query = mysqli_num_rows($result);
        if($jumlah_data_query == 0){
            throw new Exception("<b>Data SO</b> : <b>".$so."</b> pada tanggal <b>".$tanggal."</b> tidak ditemukan atau dihapus");
        }
        $data = mysqli_fetch_array($result);
        //extract hasil query `delivery_schedule` mysql menjadi variabel
        extract($data);
        
        //cek total loading
        $volume_out = 0;
        $kueri = "SELECT SUM(`batch_vol`) AS volume_out  FROM `batch_request` WHERE `so_no`='".$so."' AND `batch_date` = '".$tanggal."'";
            //echo $kueri;exit();
        $hasil = mysqli_query($conns,$kueri);
        if(!$hasil){
                throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error());
        }
        if(mysqli_num_rows($hasil) > 0){
            $row = mysqli_fetch_array($hasil);
            $volume_out = $row['volume_out'];
        }
        
        //edit table delivery schedule 1-24H
        $query_delete = "UPDATE `delivery_schedule` SET `1-24hr` = '$volume_out', `ds_code` = 'M' WHERE `so_no` = '".$so."' AND schedule_date = '".$tanggal."'";
//echo $query_delete; exit;        
        $result_delete = mysqli_query($conns,$query_delete);
        if(!$result_delete){
            $output = array(
                'status'    =>  0,
                'msg'       =>  'Error : '.  mysqli_error()
            );
            echo json_encode($output);
            exit();
        }
        //jika berhasil
        $output = array(
            'status'    =>  1,
            'msg'       =>  'Data <b>'.$so.'</b> berhasil dihapus!'
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
    mysqli_close($conn);
?>
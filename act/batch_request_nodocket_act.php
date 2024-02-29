<?php
include '../inc/constant.php';
include '../db.php';
include '../lib/mis.php';
if(IS_AJAX){
    try {
	$plant_id = PLANT_ID;

	$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
        if(!$conn){
            throw new Exception("<b>Error</b> : ".  mysqli_error($conn));
        }
        if(!mysqli_select_db($conn,DB_NAME)){throw new Exception("<b>Error</b> : ".  mysqli_error($conn));}
        $mis = new mis();
        if(empty($_POST['so']) || empty($_POST['sched_date'])|| empty($_POST['docket'])){
            throw new Exception("Kolom <b>Docket NO, SO Number  &  Schedule Date</b> Harus diisi!");
        }
        extract($_POST);   
        if($mis->special_char($so)){
            throw new Exception("<b>Error</b> : Inputan hanya boleh menggunakan Alphabet atau angka");
        }
        //cek ke database
        $q_string = "SELECT * FROM `delivery_schedule` WHERE `so_no`='".$_POST['so']."' AND `schedule_date` = '".$_POST['sched_date']."'";
//        echo $q_string;exit();
        $result = mysqli_query($conns,$q_string);
        if(!$result){
            throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error());
        }	
        $jumlah_data_query = mysqli_num_rows($result);
        if($jumlah_data_query == 0){
            throw new Exception("<b>Data SO</b> : <b>".$_POST['so']."</b> pada tanggal <b>".$_POST['sched_date']."</b> tidak ditemukan");
        }
        $data = mysqli_fetch_array($result);
        //extract hasil query `delivery_schedule` mysql menjadi variabel
        extract($data);
      

        if($jumlah_data_query > 0){
            
            $output = array(
                "status"            =>  1,
                "msg"               =>  'Data ditemukan !',
                "so_no"             =>  $so_no,
                "docket"            =>  $_POST['docket'],
                "docketn"           =>  $_POST['docket'],
                'cust_id'           =>  $data['customer_id'],
                'cust_name'         =>  $data['customer_name'],
                'proj_no'           =>  $data['project_id'],
                'proj_address'      =>  $data['project_address'],
                'proj_loc'          =>  $data['project_location'],
                'product_code'      =>  'Adjustment',
                'plant_id'          =>  $plant_id,
   
            );
            exit(json_encode($output));
        }
        else{
            throw new Exception("SO sudah Expired");
        }
        exit();
	if(isset($_POST['so']) && !empty($_POST['so']) && isset($_POST['sched_date']) && !empty($_POST['sched_date'])){
            
	}
	else{
            echo json_encode(array('status'=>0,'msg'=>'Kolom <b>SO Number  &  Schedule Date</b> Harus diisi!'));
	}
	mysqli_close($conns);
    } 
    catch (Exception $exc) {
        $output = array(
            "status"    =>  0,
            "msg"       =>  "<b>Error</b> : ".$exc->getMessage()
            );
        exit(json_encode($output));
    }
}
else{
    die("Access denied");
}
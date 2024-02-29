<?php
include '../inc/constant.php';
include '../lib/mis.php';
include '../db.php';
if(IS_AJAX){
    try {
	$plant_id = PLANT_ID;
	$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
        if(!$conn){
            throw new Exception("<b>Error001</b> : ".  mysqli_error($conn));
        }
        if(!mysqli_select_db($conn,DB_NAME)){throw new Exception("<b>Error002</b> : ".  mysqli_error($conn));}
        $mis = new mis();
        if(empty($_POST['docket_no']) ){
            throw new Exception("Kolom <b>SO Number </b> Harus diisi!");
        }
        extract($_POST);   
        if($mis->special_char($docket_no)){
            throw new Exception("<b>Error003</b> : Inputan hanya boleh menggunakan Alphabet atau angka");
        }
        //cek ke database
//        $q_string = "SELECT * FROM `batch_transaction` WHERE `docket_no`='".$_POST['so_no']."' ";
        $q_string = "SELECT A.* FROM `batch_transaction` A 
                        LEFT JOIN `batch_transaction2` B ON A.`docket_no` = B.`docket_no` 
                        WHERE (UPPER(`flag_code`)='S' OR UPPER(`flag_code`)='M') 
                        AND B.`request_no` IS NULL 
                        AND `mch_code`='".PLANT_ID."'
                        AND A.`docket_no`='".$_POST['docket_no']."'";
        $result = mysqli_query($conns,$q_string);
        if(!$result){
            throw new Exception("<b>ERROR004 MYSQL</b> : ".mysqli_error());
        }	
        $jumlah_data_query = mysqli_num_rows($result);
        if($jumlah_data_query == 0){
            throw new Exception("<b>Data SO</b> : <b>".$_POST['docket_no']."</b>  Not Found");
        }
        $data = mysqli_fetch_array($result);
        //extract hasil query `delivery_schedule` mysql menjadi variabel
        extract($data);
        $data_ds = mysqli_fetch_array($result);
      
        //get today
        date_default_timezone_set("Asia/Jakarta");
        $today      =   date('d');
        $thismonth  =   date('m');
        $thisyear   =   date('Y');
        $thishour   =   date('H');//jam sekarang\
        $lima       =   $thishour -  5;
        $hari_ini   =   date("d/m/Y");


            $output = array(
              "status"  => 1,
                "msg"   => 'Data found !',
                'plant_id'          =>  $plant_id,
                "docket_no"         =>  $docket_no,
                "docket"         =>  $docket_no,
                "so_no"             =>  $so_no,
                'cust_id'           =>  $cust_code,
                'cust_name'         =>  $cust_name,
                "today_b_request"   =>  $batch_vol,
                'proj_no'           =>  $proj_code,
                'proj_address'      =>  $proj_name,
                'proj_loc'          =>  $proj_address
                
            );
           exit(json_encode($output));
       

        exit();
	if(isset($_POST['so_no']) && !empty($_POST['so_no']) && isset($_POST['sched_date']) && !empty($_POST['sched_date'])){
            
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
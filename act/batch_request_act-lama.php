<?php
include '../inc/constant.php';
include '../lib/mis.php';
include "../db.php";
if(IS_AJAX){
    try {
	$plant_id = PLANT_ID;

	$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
        if(!$conn){ throw new Exception("<b>Error</b> : ".  mysqli_error($conn)); }        
        if(!mysqli_select_db($conn,DB_NAME)){throw new Exception("<b>Error</b> : ".  mysqli_error($conn));}
        $mis = new mis();
        if(empty($_POST['so_no']) || empty($_POST['sched_date'])){ throw new Exception("Kolom <b>SO Number  &  Schedule Date</b> Harus diisi!"); }        
        extract($_POST);
        if($mis->special_char($so_no)){ throw new Exception("<b>Error</b> : Inputan hanya boleh menggunakan Alphabet atau angka"); }
        //cek ke database
         date_default_timezone_set('Asia/Jakarta');
         $jam = date('G');
       //  $qq_string = "SELECT SUM(`$jam`) as jam FROM `delivery_schedule` WHERE `so_no`='".$_POST['so_no']."' AND `schedule_date` = '".$_POST['sched_date']."'";
       //  $results = mysqli_query($conns,$qq_string);
       //  if(!$results){ throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error()); } 
       // $row = mysqli_fetch_array($results);
       //  $datas = $row['jam'];
      
       //  if($datas == 0){ throw new Exception("<b>Data SO</b> : <b>".$_POST['so_no']."</b> pada tanggal <b>".$_POST['sched_date']."</b> tidak ditemukan"); }
         

        //extract hasil query `delivery_schedule` mysql menjadi variabel

        $q_string = "SELECT * FROM `delivery_schedule` WHERE `so_no`='".$_POST['so_no']."' AND `schedule_date` = '".$_POST['sched_date']."'";
        $result = mysqli_query($conns,$q_string);
        if(!$result){ throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error()); }	
        $jumlah_data_query = mysqli_num_rows($result);
        if($jumlah_data_query == 0){ throw new Exception("<b>Data SO</b> : <b>".$_POST['so_no']."</b> pada tanggal <b>".$_POST['sched_date']."</b> tidak ditemukan"); }
        $data = mysqli_fetch_array($result);
        //extract hasil query `delivery_schedule` mysql menjadi variabel
        extract($data);
        //cek ke table mix design apakah product code sudah ada
        $query_mix_design = "SELECT * FROM `mix_design_composition` WHERE `product_code` = '".$product_code."'";
        $result_mix_design = mysqli_query($conns,$query_mix_design);
        if(!$result_mix_design){
            throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error());
        }
        $jumlah_data_query_mix_design = mysqli_num_rows($result_mix_design);
        if($jumlah_data_query_mix_design == 0){
            throw new Exception("<b>Product Code</b> : <b>".$product_code."</b> Composition tidak ditemukan");
        }      
        //get today
        $today      =   date('d');
        $thismonth  =   date('m');
        $thisyear   =   date('Y');
        $thishour   =   date('H');//jam sekarang
        $pieces = explode('/',$schedule_date);

        if($pieces[2] != $thisyear || $pieces[1] != $thismonth){
            throw new Exception('<b>SO</b> : \''.$_POST['so_no'].'\' sudah <i>expired</i>');
        }
        //cek yesterday
        if($today == $pieces[0]){
            //CEK TOTAL BARANG KELUAR, diambil dari tabel BATCH_REQUEST yang flag_code nya selain 'D' dan 'E'
            $volume_out = 0;
            $kueri = "SELECT SUM(`batch_vol`) AS volume_out  FROM `batch_request` WHERE `so_no`='".$so_no."' AND `product_code` = '".$data['product_code']."' AND `flag_code` != 'D' AND `batch_date` = '".$sched_date."'";
            $hasil = mysqli_query($conns,$kueri);
            if(!$hasil){ throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error()); }
            if(mysqli_num_rows($hasil) > 0){
                $row = mysqli_fetch_array($hasil);
                $volume_out = $row['volume_out'];
            }
            $output = array(
                "status"            =>  1,
                "msg"               =>  'Data ditemukan !',
                "so_no"             =>  $so_no,
                "product_code"      =>  $data['product_code'],
                "sche_date"	    =>	$data['schedule_date'],
                "delv_vol"          =>  $data['deliv_order_vol'],
                "today_b_request"   =>  $data['1-24hr'],
                'plant_id'          =>  $plant_id,
                'out_volume'        =>  $volume_out,
                'cust_id'           =>  $data['customer_id'],
                'cust_name'         =>  $data['customer_name'],
                'proj_no'           =>  $data['project_id'],
                'proj_address'      =>  $data['project_address'],
                'proj_loc'          =>  $data['project_location'],
                'product_code'      =>  $data['product_code'],
            );
            exit(json_encode($output));
        }
        elseif($today - $pieces[0] == 1){
            ////cek 
            if($thishour <= 7){
                //CEK TOTAL BARANG KELUAR, diambil dari tabel BATCH_REQUEST yang flag_code nya selain 'D'
                $volume_out = 0;
                $kueri = "SELECT SUM(`batch_vol`) AS volume_out  "
                        . "FROM `batch_request` "
                        . "WHERE `so_no`='".$so_no."' AND `product_code` = '".$data['product_code']."' "
                        . "AND `flag_code` != 'D' AND `batch_date` = '".$sched_date."'";
                $hasil = mysqli_query($conns,$kueri);
                if(!$hasil){
                    throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error());
                }
                if(mysqli_num_rows($hasil) > 0){
                    $row = mysqli_fetch_array($hasil);
                    $volume_out = $row['volume_out'];
                }
                $output = array(
                        "status"            =>  1,
                        "msg"               =>  'Data ditemukan !',
                        "so_no"             =>  $so_no,
                        "product_code"      =>  $data['product_code'],
                        "sche_date"         =>  $data['schedule_date'],
                        "delv_vol"          =>  $data['deliv_order_vol'],
                        "today_b_request"   =>  $data['1-24hr'],
                        'plant_id'          =>  $plant_id,
                        'out_volume'        =>  $volume_out,
                        'cust_id'           =>  $data['customer_id'],
                        'cust_name'         =>  $data['customer_name'],
                        'proj_no'           =>  $data['project_id'],
                        'proj_address'      =>  $data['project_address'],
                        'proj_loc'          =>  $data['project_location'],
                        'product_code'      =>  $data['product_code'],
   
                    );exit(json_encode($output));
            }
            else{
                
                /* 
                 * ------------------------------------------------------------------------------------
                 * date : 150413 
                 * revised by : ilham
                 * kalau sudah lewat jam 7, tidak boleh request lagi dengan schedule date yang terpilih
                 * ------------------------------------------------------------------------------------
                 */
                throw new Exception('SO : <b>'.$so_no.'</b> dengan tanggal <i>schedule</i> '.$sched_date.' sudah Expired');   
                //jika sudah lewat jam 7 maka harus cek record schedule dengan SO No yang sama pada tanggal hari ini
                $sched_date_today = date('d/m/Y');
                $query_cek_schedule = "SELECT * FROM `delivery_schedule` WHERE `so_no`='".$so_no."' AND `schedule_date` = '".$sched_date_today."'";
                $result_query_cek_schedule = mysqli_query($conns,$query_cek_schedule);
                if(!$result_query_cek_schedule){
                    throw new Exception("<b>ERROR MYSQL</b> : ".mysqli_error());
                }

                $jumlah_data = mysqli_num_rows($result_query_cek_schedule);
//                    echo $jumlah_data;exit();
                if($jumlah_data == 0){ //jika so no yang sama tidak ada pada hari ini maka boleh batching
                    //CEK TOTAL BARANG KELUAR, diambil dari tabel BATCH_REQUEST yang flag_code nya selain 'D'
                    $volume_out = 0;
                    $kueri = "SELECT SUM(`batch_vol`) AS volume_out  FROM `batch_request` WHERE `so_no`='$so_no' AND `product_code` = '".$data['product_code']."' AND `flag_code` != 'D' AND `batch_date` = '".$sched_date."'";
                    //echo $kueri;exit();
                    $hasil = mysqli_query($kueri);
                    if(!$hasil){
                        throw new Exception("<b>ERROR MYSQL</b> : ".mysql_error());
                    }
                    if(mysqli_num_rows($hasil) > 0){
                        $row = mysqli_fetch_array($hasil);
                        $volume_out = $row['volume_out'];
                    }
                    $output = array(
                        "status"            =>  1,
                        "msg"               =>  "Data ditemukan !",
                        "so_no"             =>  $so_no,
                        "product_code"      =>  $data['product_code'],
                        "sche_date"         =>  $data['schedule_date'],
                        "delv_vol"          =>  $data['deliv_order_vol'],
                        "today_b_request"   =>  $data['1-24hr'],
                        "plant_id"          =>  $plant_id,
                        "out_volume"        =>  $volume_out,
                        "cust_id"           =>  $data['customer_id'],
                        "cust_name"         =>  $data['customer_name'],
                        "proj_no"           =>  $data['project_id'],
                        "proj_address"      =>  $data['project_address'],
                        "proj_loc"          =>  $data['project_location'],
                        "product_code"      =>  $data['product_code'],
       //                 'varian'            =>  $va
                    );
                    exit(json_encode($output));
                }
                else{
                    throw new Exception('SO : <b>'.$so_no.'</b> dengan tanggal <i>schedule</i> '.$sched_date.' sudah Expired');
                }
            }
        }
        else{
            throw new Exception("SO sudah Expired");
        }
        exit();
	if(isset($_POST['so_no']) && !empty($_POST['so_no']) && isset($_POST['sched_date']) && !empty($_POST['sched_date'])){
            
	}
	else{
            echo json_encode(array('status'=>0,'msg'=>'Kolom <b>SO Number  &  Schedule Date</b> Harus diisi!'));
	}
	mysqli_close($conn);
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
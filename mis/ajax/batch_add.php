<?php
session_start();
include '../inc/constant.php';
include "../db.php";
date_default_timezone_set("Asia/Jakarta");
if(IS_AJAX){
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
        $Lg = $object->id_user;
       //         echo $Lg;exit();            
          
            if(isset($_POST['so_no']) && !empty($_POST['so_no']) && isset($_POST['qty']) && !empty($_POST['qty']) && isset($_POST['s_date']) && !empty($_POST['s_date']) && isset($_POST['seal_no']) && !empty($_POST['seal_no'])){
                extract($_POST);
                $today      = date('d/m/Y');
                $kn         = date('Y-m-d H:i:s');
                

                //cek seal no
                $sn = "SELECT * FROM `batch_request` WHERE `seal_no` ='".$seal_no."' AND flag_code ='P'";
                $r_sn = mysqli_query( $conns,$sn);
                if(mysqli_num_rows($r_sn)>0){throw new Exception("Seal No : ".$seal_no." Sudah Ada"); }
//                //cek maksimal dua minute
//                //SELECT MAX(CAST(request_no AS UNSIGNED)) request_no, `so_no`, MINUTE(`cre_date`) FROM batch_request ORDER BY CAST(request_no AS UNSIGNED);
//                $cek_minute = "SELECT request_no, `so_no`, `cre_date` AS terakhir, DATE_ADD(cre_date, INTERVAL 2 MINUTE) AS duo FROM batch_request  WHERE `so_no` = '".$so_no."' AND `batch_date` = '$today' ORDER BY `cre_date` DESC LIMIT 1";
//                //echo $cek_minute;exit();
//                $cm = mysqli_query($cek_minute);
//                if(mysqli_num_rows($cm)>0){
//                    $cr = mysqli_fetch_array($cm);
//                    $dua = $cr['duo'];
//                    if($dua>$kn){ throw new Exception("please wait two minute"); }
//                }
                //get SO ORDER volume
                $ds = "SELECT A.* FROM `delivery_schedule` A WHERE `so_no` ='".$so_no."' AND `schedule_date` = '".$s_date."' AND ds_code = 'M'";
                $ds_r = mysqli_query($conns,$ds);
                if(mysqli_num_rows($ds_r)>0){ throw new Exception("SO : ".$so_no." Sudah Di delete"); }
                
                //get SO ORDER volume
                $q = "SELECT A.* FROM `delivery_schedule` A WHERE `so_no` ='".$so_no."' AND `schedule_date` = '".$s_date."'";
                $result = mysqli_query($conns,$q);
                if(!$result){ throw new Exception("ERROR sql: ".mysqli_error()); }
                if(mysqli_num_rows($result)>0){
                        $row = mysqli_fetch_array($result);
                        $temp_vol       = 0;
                        $so_vol         = 0;
                        $product_code   = "";
                        $cust_code      = "";
                        $cust_name      = "";
                        //CEK TOTAL BARANG KELUAR, diambil dari tabel BATCH_REQUEST yang flag_code nya selain 'D'
                        $volume_out     = 0;
                        $kueri = "SELECT SUM(`batch_vol`) AS volume_out  FROM `batch_request` WHERE `so_no`='".$_POST['so_no']."' AND `product_code` = '".$row['product_code']."' AND `flag_code` != 'D' AND `batch_date` = '".$s_date."'";
                        $hasil = mysqli_query($conns,$kueri);
                        if(!$hasil){ throw new Exception("ERROR sql: ".mysqli_error()); }
                        if(mysqli_num_rows($hasil) > 0){
                            $r = mysqli_fetch_array($hasil);
                            $volume_out = $r['volume_out'];
                        }
                        
                        $so_vol         = $row['qty_so'];
                        $product_code   = $row['product_code'];
                        $cust_code 	= $row['customer_id'];
                        $cust_name 	= $row['customer_name'];
                        $proj_code 	= $row['project_id'];
                        $proj_name 	= $row['project_location'];
                        $proj_address 	= $row['project_address'];
                        $proj_tel 	= $row['project_tel'];
                        $today_vol      = $row['1-24hr']; 
                        
                        /*
                         * 150519 : perubahan toleransi untuk SO02
                         */
                        $toleransi = SO_PERCENTAGE;
                        if(substr($_POST['so_no'],0,4)=="SO02")
                        {$toleransi=0;}
                        elseif (substr($_POST['so_no'],0,4)=="SO07") {$toleransi=0;}    
                        elseif (substr($_POST['so_no'],0,4)=="SO08") {$toleransi=0;}
                        
                        //cek total
                        if($qty + $volume_out <= $so_vol + ( $so_vol * $toleransi)){
                            
                            $ku = "SELECT MAX(CAST(request_no AS UNSIGNED)) request_no FROM batch_request ORDER BY CAST(request_no AS UNSIGNED)";
                            $hasil_max = mysqli_query($conns,$ku);
                            $count = mysqli_num_rows($hasil_max);
                            //echo $count;exit();
                            if($count == 0){ $request_no = 1; }
                            else{
                                $data = mysqli_fetch_array($hasil_max);
                                $request_no = $data['request_no']+1;
                            }
                            $curdate = date('d/m/Y');
                            
                            //cek versi
                            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";
                            $data_var = mysqli_query($conns,$query_war);
                            if(!$data_var) die(mysqli_error());
                            if(mysqli_num_rows($data_var) > 0){
                                $VAR_data = mysqli_fetch_array($data_var);
                                $chart = $VAR_data['chart_no'];
                                $name_bom = $VAR_data['name_bom'];
                            }    
                        //MODIFIKASI 140718
                        //$remain_vol = $so_vol - $qty;
                        $remain_vol = $today_vol - $qty;
                        if($remain_vol < 0) $remain_vol = 0;

                        $insert = "INSERT INTO `batch_request` "
                                . "(`request_no`,`so_no`,`mch_code`,`product_code`,`batch_date`,`vh_no`,`unit_no`,`driver_id`,`driver_name`,`user_login`,`cust_code`,`cust_name`,`proj_code`,`proj_name`,`proj_address`,`proj_phone_no`,`batch_vol`,`remain_vol`,`total_so_vol`,`flag_code`,`cre_by`,`cre_date`,`seal_no`) "
                                . "VALUES ('".$request_no."','".$so_no."','".$plant_id."','".$product_code."','".$s_date."',' ',' ',' ', ' ',' ','".$cust_code."','".$cust_name."','".$proj_code."','".$proj_name."','".$proj_address."','".$proj_tel."','".$qty."','".$remain_vol."','".$so_vol."','I',' ',NOW(),'".$seal_no."')";
                                            //echo $insert;exit();
                        if(!mysqli_query($conns,$insert)){ throw new Exception('Gagal Menambahkan Batch Request, Error : '.mysqli_error()); }
                        
                        //insert login & versi
                        $insert_requst = "INSERT INTO `request` "
                                . "(request_no,seal_no,code_bom,name_bom,id_user) "
                                . "VALUES ('".$request_no."','".$seal_no."','".$chart."','".$name_bom."','".$Lg."')";                        
                        if(!mysqli_query($conns,$insert_requst)){ throw new Exception('Gagal Menambahkan Batch Request, Error : '.mysqli_error()); }
                        else{
                                //UPDATE 1-24hr field on delivery_schedule_temp table
                                $temp_up = $qty+$volume_out;
                                $q_update = "UPDATE `delivery_schedule` SET `out_volume` = '$temp_up' WHERE `so_no` = '$so_no' AND `schedule_date` = '$s_date'";
                                if(!mysqli_query($conns,$q_update)){
                                    throw new Exception("ERROR:Cannot Update Temp Volume on delivery schedule, MySql : ".mysqli_error());
                                }
                                else{
                                    $kueri = "SELECT * FROM `delivery_schedule` WHERE `so_no` = '$so_no' AND `schedule_date`= '$s_date'";
                                    $result = mysqli_query($conns,$kueri);
                                    $data = mysqli_fetch_array($result);

                                    $output = array(
                                        'status'            =>  1,
                                        'msg'               =>  'Berhasil Menambahkan Batch Request, No SO : '.$so_no.', tanggal :'.$s_date.', Qty :'.$qty,
                                        'out_volume'        =>  $data['out_volume'],
                                        'today_b_request'   =>  $data['1-24hr']);
                                    exit(json_encode($output));
                                }
                        }
                    }
                    else{
                        $max    =   $so_vol + ($so_vol * $toleransi);
                        throw new Exception("Qty melebihi Order Volume, MAX Order adalah ".$max);
                    }
                }
                else{ throw new Exception("SO : ".$so_no." tidak ditemukan"); }
            }
            else{ throw new Exception("Empty Field"); }
        }
        else{ exit("Method is denied"); }
    } catch (Exception $exc) {
        $output = array(
            "status"    => 0,
            "msg"       =>  $exc->getMessage()
        );
        exit(json_encode($output));
    }
}
else{ exit("Access Denied"); }

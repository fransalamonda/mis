<?php
include '../inc/constant.php';
include '../db.php';
include '../lib/active_record.php';

if(IS_AJAX){
    try {
        $plant_id = PLANT_ID;
	$mysqli = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
        $active_record = new active_record(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if(!$mysqli){ throw new Exception("<b>Error001</b> : ".  mysqli_error($conns)); }
        if(!mysqli_select_db($conns,DB_NAME)){throw new Exception("<b>Error002</b> : ".  mysqli_error($conns));}
        if(empty($_POST['delvdate']) ){ throw new Exception("Kolom <b>Tanggal </b> Harus diisi!"); }
        extract($_POST);
        $trunc = mysqli_query($conns,"TRUNCATE TABLE `tmp_batch_transaction_detail`");

        //CEK DATA YANG STATUS FLAG_CODE nya 'S' atau 'M'   not adjustment  
        $d_string = "SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                FROM `batch_transaction_detail` AS A 
                INNER JOIN `batch_transaction` AS B ON  A.`docket_no` = B.`docket_no` 
                WHERE B.`delv_date`='".$delvdate."'
                    AND (UPPER(B.`product_code`)!='Adjustment' OR UPPER(B.`product_code`)!='SELFUSAGE')
                AND B.`mch_code`='".$plant_id."'";
//        print_r($d_string);exit();
        $active_record->trans_begin();
        $result = $active_record->query($d_string);
        //$result = $active_record->num_row();
        if(!$result){
            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
            $active_record->trans_rollback();
            exit();
        }
        if($result->num_rows == 0 ){ throw new Exception("<b>Bacth Transaction Delv Date :</b> : <b>".$_POST['delvdate']."</b>  tidak ditemukan"); }
        $count        = 0;
        $count_docket = 0;
        foreach ($result as $bt){
            $request_no         =  $bt['request_no'];
            $so_no              =  $bt['so_no'];
            $docket_no          =  $bt['docket_no'];
            $material_code      =  $bt['material_code'];
            $design_qty         =  $bt['design_qty'];
            $target_qty         =  $bt['target_qty'];
            $actual_qty         =  $bt['actual_qty'];
            $moisture           =  $bt['moisture'];
            $tambah = array(
                    "request_no"     =>  $request_no,
                    "so_no"          =>  $so_no,
                    "docket_no"      =>  $docket_no,
                    "material_code"  =>  $material_code,
                    "design_qty"     =>  $design_qty,
                    "target_qty"     =>  $target_qty,
                    "actual_qty"     =>  $actual_qty,
                    "moisture"       =>  $moisture,
            );
            //print_r($tambah);
            $save_status = $active_record->insert("tmp_batch_transaction_detail", $tambah);
            if(!$save_status){
                $output = array(
                    "status"    => 0,
                    "msg"       =>  "#error00 : Gagal Membaca Database Tanggal, ".$delvdate." "
                        );
            exit(json_encode($output));
            }
            //$count++;
        }
        $active_record->trans_commit();
        
        //select docket adjustment
        $docket_adj = "SELECT A.`request_no`,A.`so_no`,B.`docket_no`,A.`material_code`,A.`design_qty`,A.`target_qty`,A.`actual_qty`,A.`moisture` 
                FROM `batch_transaction_detail` AS A 
                INNER JOIN `batch_transaction` AS B ON  A.`so_no` = B.`docket_no` 
                WHERE B.`delv_date`='".$delvdate."'
                      AND B.`mch_code`='".$plant_id."'";
        $active_record->trans_begin();
        $result_docket = $active_record->query($docket_adj);
        //$result = $active_record->num_row();
        if(!$result_docket){
            $output = array(
                                    "status"    => 0,
                                    "msg"       =>  'error 002'
                            );
            exit(json_encode($output));
        }
        if($result_docket->num_rows > 0 ){            
            foreach ($result_docket as $rda){
                $requestno         =  $rda['request_no'];
                $sono              =  $rda['so_no'];
                $docketno          =  $rda['docket_no'];
                $materialcode      =  $rda['material_code'];
                $designqty         =  $rda['design_qty'];
                $targetqty         =  $rda['target_qty'];
                $actualqty         =  $rda['actual_qty'];
                $moisturee         =  $rda['moisture'];
                
                /*
                 * cek docket = so dan code material yang sama di batch transaction detail
                 * kalau tidak ada SO insert ke SO ADJ dan SO ADJ insert ke Docket Asli
                 */
                
                //cek di bacth transaction detail docketnya sama dengan so adjustment
                $cek ="SELECT * FROM `batch_transaction_detail` where docket_no = '$sono' AND material_code= '$materialcode'";
                    $active_record->trans_begin();
                    $result_cek = $active_record->query($cek);
                    if($result_cek->num_rows == 0){
                        $so ="SELECT * FROM `batch_transaction` where docket_no = '$sono' ";
                        $active_record->trans_begin();
                        $cari_so = $active_record->query($so);
                            foreach ($cari_so as $c_s){
                                $sono_ad              =  $c_s['so_no'];
                                    $tambah_adj = array(
                                                    "request_no"     =>  $requestno,
                                                    "so_no"          =>  $sono_ad,
                                                    "docket_no"      =>  $sono,
                                                    "material_code"  =>  $materialcode,
                                                    "design_qty"     =>  $designqty,
                                                    "target_qty"     =>  $targetqty,
                                                    "actual_qty"     =>  $actualqty,
                                                    "moisture"       =>  $moisturee,
                                    );
                                    $save_status_adj = $active_record->insert("tmp_batch_transaction_detail", $tambah_adj);
                                    if(!$save_status_adj){
                                        $msg = "MySql :".mysqli_error($active_record->koneksi);
                                        exit();
                                    }
                            }
//                        $count_docket++;
                    }else{
                        $jml_cek ="SELECT SUM(`actual_qty`) AS jml FROM `batch_transaction_detail` WHERE `so_no` = '$sono' AND `material_code`='$materialcode'";
                        $active_record->trans_begin();
                        $jml_m = $active_record->query($jml_cek);
                            foreach ($jml_m as $jm){
                               $jml_act = $jm['jml'];
                            }
//                        $actualqty_as = 0;
                        foreach ($result_cek as $rda_e){
                            $requestno         =  $rda_e['request_no'];
                            $sono              =  $rda_e['so_no'];
                            $docketno          =  $rda_e['docket_no'];
                            $materialcode      =  $rda_e['material_code'];
                            $designqty         =  $rda_e['design_qty'];
                            $targetqty         =  $rda_e['target_qty'];
                            $actualqty_adj     =  $rda_e['actual_qty']; //asli
                            $actualqty_as      =  $rda['actual_qty'];
                            //$akumulasi         =  $actualqty_adj + $jml_act; 
                            $akum         =  $rda_e['actual_qty'] + $jm['jml']; 
                            $moisturee         =  $rda_e['moisture'];
                            
                            $p_k = array(
                                "docket_no"        =>  $docketno,
                                "material_code"    =>  $materialcode,
//                                "adj"              => $jml_act,
//                                "actual_qty_asli"  => $actualqty_adj,
                                "actual_qty"       =>  $akum,
                            );
//                            print_r($p_k);
//                            print_r($edit_adj);
                            $q_update = "UPDATE `tmp_batch_transaction_detail` SET `actual_qty` = '$akum' WHERE `docket_no` = '$docketno' AND `material_code` = '$materialcode' ";
//                            print_r($q_update);
                            $rt = mysqli_query($conns,$q_update);
                        }
                        //$count_docket++;
                    }
            }
            $count_docket++;
        }

        $active_record->trans_commit();        
        $output = array(
                                    'status'            =>  1,
                                    'msg'               =>  "Please Download Batch Transaction Date ".$_POST['delvdate'],
                                    'out_volume'        =>  $plant_id);
                                exit(json_encode($output));        
    } catch (Exception $exc) {
        $output = array(
            "status"    => 0,
            "msg"       =>  $exc->getMessage()
        );
        exit(json_encode($output));
    }
}else{
    exit("Access Denied");
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


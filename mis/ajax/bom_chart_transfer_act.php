<?php
session_start();
include '../inc/constant.php';
include '../lib/active_record.php';

if(IS_AJAX){
    try{
        $plan_id = PLANT_ID;
        $active_record = new active_record(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
        $mysqli = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
        if(!$mysqli){
            throw new Exception("<b>Error001</b> : ".  mysql_error($conn));
        }
        if(!mysql_select_db(DB_NAME)){throw new Exception("<b>Error002</b> : ".  mysql_error($conn));}
        
        if(empty($_POST['chart_no']) ){
            throw new Exception("Kolom <b>chart_no </b> Harus diisi!");
        }
        extract($_POST);
        
        $trunc = mysql_query("TRUNCATE TABLE `mix_design`");
        $trunc = mysql_query("TRUNCATE TABLE `mix_design_composition`");
        
        $d_string = "SELECT B.`mch_code`,B.`product_code`,B.`slump_code`,B.`discharge`,B.`description`,B.`specification`,B.`qlt_group`,B.`flag_code`,B.`max_size`,B.`cre_by`,B.`cre_date`,B.`upd_by`,B.`upd_date`
                    FROM `mix_package_composition` AS A
                    INNER JOIN `mix_package` AS B ON A.`product_code` = B.`product_code`
                    WHERE A.`chart_no` = '".$_POST['chart_no']."'
                    AND A.`mch_code`= '".PLANT_ID."'";
        
    $active_record->trans_begin();
    $result = $active_record->query($d_string);
        //$result = $active_record->num_row();
    if(!$result){
                            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                            $active_record->trans_rollback();
                            exit();
    }
        if($result->num_rows == 0 ){
                throw new Exception("<b>Mix Design :</b> Version <b>".$_POST['chart_no']."</b>  tidak ditemukan");
        }
        $count        = 0;
        foreach ($result as $md){
            $mch_code       = $md['mch_code'];
            $product_code   = $md['product_code']; 
            $slump_code     = $md['slump_code'];
            $discharge      = $md['discharge'];
            $description    = $md['description'];
            $specification  = $md['specification'];
            $qlt_group      = $md['qlt_group']; 
            $flag_code      = $md['flag_code'];
            $max_size       = $md['max_size'];
            $cre_by         = $md['cre_by'];
            $cre_date       = $md['cre_date'];
            $upd_by         = $md['upd_by'];
            $upd_date       = $md['upd_date'];
            
            $tambah = array(
                    "mch_code"      => $mch_code,
                    "product_code"  => $product_code,
                    "slump_code"    => $slump_code,
                    "discharge"     => $discharge,
                    "description"   => $description,
                    "specification" => $description,
                    "qlt_group"     => $qlt_group,
                    "flag_code"     => $flag_code,
                    "max_size"      => $max_size,
                    "cre_by"        => $cre_by,
                    "cre_date"      => $cre_date,
                    "upd_by"        => $upd_by,
                    "upd_date"      => $upd_date,             
            );
            //print_r($tambah);
            $save_status = $active_record->insert('mix_design', $tambah);
            if(!$save_status){
                $output = array(
                                                    "status"    => 0,
                                                    "msg"       =>  'error201'
                               );
            }
            $count++;
        }
        $active_record->trans_commit();    
        $mpc ="SELECT *
                      FROM `mix_package_composition`
                      WHERE `chart_no`='".$_POST['chart_no']."'
                      AND `mch_code`='".PLANT_ID."'";
        $active_record->trans_begin();
        $result_mpc = $active_record->query($mpc);
        if(!$result_mpc){
                $output = array(
                                                    "status"    => 0,
                                                    "msg"       =>  'error0003'
                               );
        }
        if($result_mpc->num_rows == 0 ){
                throw new Exception("<b>Mix Design :</b> Version <b>".$_POST['chart_no']."</b>  tidak ditemukan");
        }
        $countmpc        = 0;
        foreach ($result_mpc as $mdc){
                //product_code        material_name               
            $mchcode       = $mdc['mch_code'];
            $productcode   = $mdc['product_code']; 
            $materialgroup = $mdc['material_group'];
            $seqno         = $mdc['seq_no'];
            $materialcode  = $mdc['material_code'];
            $materialname  = $mdc['material_name'];
            $mixqty        = $mdc['mix_qty']; 
            $mixqtyadj    = $mdc['mix_qty_adj'];
            $unit           = $mdc['unit'];
            $flagcode      = $mdc['flag_code'];
            $codetrans     = $mdc['code_trans'];
            $chartno       = $mdc['chart_no'];
            
            $tambah_mdc = array(
                    "mch_code"      => $mchcode,
                    "product_code"  => $productcode,
                    "material_group"    => $materialgroup,
                    "seq_no"        => $seqno,
                    "material_code" => $materialcode,
                    "material_name" => $materialname,
                    "mix_qty"       => $mixqty,
                    "mix_qty_adj"   => $mixqtyadj,
                    "unit"          => $unit,
                    "flag_code"     => $flagcode,
            );
            //print_r($tambah);
            $save_status_mdc = $active_record->insert('mix_design_composition', $tambah_mdc);
            if(!$save_status_mdc){
                $output = array(
                                                    "status"    => 0,
                                                    "msg"       =>  'error00'
                               );

            }
            $countmpc++;
        }
        $active_record->trans_commit();
        
        $update_y = "UPDATE `bash`.`mix_package_composition`
                 SET `code_trans` = 'Y'
                 WHERE `chart_no` = '".$_POST['chart_no']."'";
        $y_update = mysql_query($update_y);
    if(!$y_update){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error: 6".  mysqli_error($mysqli));
    } 
    
    $update_n = "UPDATE `bash`.`mix_package_composition`
                 SET `code_trans` = 'N'
                 WHERE `chart_no` <> '".$_POST['chart_no']."'";
    $n_update = mysql_query($update_n);
    if(!$y_update){
        $mysqli->rollback();$mysqli->close();
        throw new Exception("Error: 6".  mysqli_error($mysqli));
    }
    exit(json_encode($output));
   // $mysql->commit();
    $output = array(
        "status"    =>  1,
        "msg"       => 'Successfully <b>Transfers</b> Version '
            );        
    }catch (Exception $exc){
        $output = array();
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


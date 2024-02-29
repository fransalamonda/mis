<?php
session_start();
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
include './inc/constant.php';
include './lib/active_record.php';
include './lib/mis.php';
if (isset($_POST['jns_proses']) && !empty($_POST['jns_proses']) && isset($_POST['condition']) && !empty($_POST['condition']) && isset($_POST['proses'])){
    $active_record = new active_record(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $mis = new mis();
    ?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Return Truck Data Review Action</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
    </head>
    <body>
        <?php
        include "inc/menu.php";
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12"><h1 class="page-header">Proses Data Review</h1></div>
                <div class="col-sm-9 colsm-offset-3">
                    <?php
                    
                    if(strtolower($_POST['jns_proses']) == '1'){
                       /*
                        * URUTAN LOGIKA
                        * 1.get data based on $_POST['condition']
                        * 2.insert into tabel batch_transaction2
                        * 3.update flag to P if flag before is S, and T if falg before is M
                        */
                        $query_string = "SELECT * FROM `batch_transaction` WHERE ".$_POST['condition'];
                        $active_record->trans_begin();
                        $result = $active_record->query($query_string);
                        if(!$result){
                            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                            $active_record->trans_rollback();
                            exit();
                        }                      
                        /*
                         * INSERT INTO tbl_batch_transaction 2
                         */
                        $count = 0;
                        while ($obj = $result->fetch_object()) {
                            if($obj->flag_code == 'P' || $obj->flag_code == 'T'){
                                $msg = "Data Request No:".$obj->request_no.", SO No:".$obj->so_no." Sudah pernah diproses!";
                                $mis->print_msg("Error !", "error", $msg);
                                $active_record->trans_rollback();
                                exit();
                            }
                            $data_bt_2 = array(
                                "request_no"     =>  $obj->request_no,
                                "so_no"          =>  $obj->so_no,
                                "docket_no"      =>  $obj->docket_no,
                                "id_acceptance"  =>  $_POST["jns_proses"],
                                "process_code"   =>  $_POST["kode_proses"],
                                "remarks"        =>  $_POST["remark"],
                                "received_vol"   =>  $_POST["proses"],
                            );
//                            print_r($data_bt_2);exit();
                            $save_status = $active_record->insert("batch_transaction2", $data_bt_2);
                            if(!$save_status){
                                $msg = "MySql :".mysqli_error($active_record->koneksi);
                                $msg .= "<br/><br/><a class='btn btn-danger btn-sm' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                                $mis->print_msg("Error !", "error", $msg);
                                $active_record->trans_rollback();
                                exit();
                            }
                            $count++;
                        }
                        $active_record->trans_commit();
                        $msg = "Sukses memroses ".$count." data";
                        $msg .= "<br/><a class='btn btn-primary btn-xs' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                        $mis->print_msg("Success !", "success", $msg);
                    }
                    
                    /*
                     * JENIS PROSES : Kembali
                     */
                    elseif(strtolower($_POST["jns_proses"]) == '2'){
                        if(isset($_POST['kode_proses']) && !empty($_POST['kode_proses']) && isset($_POST["remark"]) && !empty($_POST["remark"])){
                           /*
                            * URUTAN LOGIKA
                            * 1.get data based on $_POST['condition']
                            * 2.insert into tabel batch_transaction2
                            * 3.update flag to P if flag before is S, and T if falg before is M
                            */
                            $query_string = "SELECT * FROM `batch_transaction` WHERE ".$_POST['condition'];
                            $active_record->trans_begin();
                            $result = $active_record->query($query_string);
                            if(!$result){
                                $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                $active_record->trans_rollback();
                                exit();
                            }

                            /*
                             * INSERT INTO tbl_batch_transaction 2
                             */
                            $count = 0;
                            while ($obj = $result->fetch_object()) {
                                if($obj->flag_code == 'P' || $obj->flag_code == 'T'){
                                    $msg = "Data Request No:".$obj->request_no.", SO No:".$obj->so_no." Sudah pernah diproses!";
                                    $mis->print_msg("Error !", "error", $msg);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                $data_bt_2 = array(
                                    "request_no"    =>  $obj->request_no,
                                    "so_no"         =>  $obj->so_no,
                                    "docket_no"     =>  $obj->docket_no,
                                    "id_acceptance"  =>  $_POST["jns_proses"],
                                    "process_code"  =>  $_POST["kode_proses"],
                                    "remarks"       =>  $_POST["remark"],
                                    "received_vol"  =>  $_POST["proses"],
                                );
                                $save_status = $active_record->insert("batch_transaction2", $data_bt_2);
                                if(!$save_status){
                                    $msg = "MySql :".mysqli_error($active_record->koneksi);
                                    $msg .= "<br/><br/><a class='btn btn-danger btn-sm' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                                    $mis->print_msg("Error !", "error", $msg);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                $count++;
                            }
                            
                            $active_record->trans_commit();
                            $msg = "Sukses memroses ".$count." data";
                            $msg .= "<br/><a class='btn btn-primary btn-xs' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                            $mis->print_msg("Success !", "success", $msg);
                            
                        }
                        else{
                            $msg = "Missing Kode Proses atau Remarks";
                            $msg .= "<br/><a class='btn btn-primary btn-xs' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                            $mis->print_msg("ERROR !", "error", $msg);
                            exit();
                        }
                    }
                    elseif(strtolower($_POST["jns_proses"]) == '3'){
                        if(isset($_POST['kode_proses']) && !empty($_POST['kode_proses']) && isset($_POST['dev']) && !empty($_POST['dev']) && isset($_POST['remark']) && !empty($_POST['remark'])){
                            /*
                            * URUTAN LOGIKA
                            * 1.get data based on $_POST['condition']
                            * 2.insert into tabel batch_transaction2
                            */
                            $query_string = "SELECT * FROM `batch_transaction` WHERE ".$_POST['condition'];
                            $active_record->trans_begin();
                            $result = $active_record->query($query_string);
                            if(!$result){
                                $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                $active_record->trans_rollback();
                                exit();
                            }
                            /*
                             * INSERT INTO tbl_batch_transaction 2
                             */
                            $count = 0;
                            while ($obj = $result->fetch_object()) {
                                if($obj->flag_code == 'P' || $obj->flag_code == 'T'){
                                    $msg = "Data Request No:".$obj->request_no.", SO No:".$obj->so_no." Sudah pernah diproses!";
                                    $mis->print_msg("Error !", "error", $msg);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                $data_bt_2 = array(
                                    "request_no"    =>  $obj->request_no,
                                    "so_no"         =>  $obj->so_no,
                                    "docket_no"     =>  $obj->docket_no,
                                    "id_acceptance"  =>  $_POST["jns_proses"],
                                    "process_code"  =>  $_POST["kode_proses"],
                                    "remarks"       =>  $_POST["remark"],
                                    "received_vol"  =>  $_POST["proses"],
                                );
                                $save_status = $active_record->insert("batch_transaction2", $data_bt_2);
                                if(!$save_status){
                                    $msg = "MySql :".mysqli_error($active_record->koneksi);
                                    $msg .= "<br/><br/><a class='btn btn-danger btn-sm' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                                    $mis->print_msg("Error !", "error", $msg);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                $count++;
                            }
                            
                            $active_record->trans_commit();
                            $msg = "Sukses memroses ".$count." data";
                            $msg .= "<br/><br/><a class='btn btn-success btn-sm' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                            $mis->print_msg("Success !", "success", $msg);
                            
                        }
                        else{
                            $msg = "Missing Kode Proses atau Remarks";
                            $msg .= "<br/><br/><a class='btn btn-danger btn-sm' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                            $mis->print_msg("ERROR !", "error", $msg);exit();
                        }
                    }
                    
                    elseif(strtolower($_POST["jns_proses"]) == '4'){
                        if(isset($_POST['kode_proses']) && !empty($_POST['kode_proses']) && isset($_POST['remark']) && !empty($_POST['remark']) && isset($_POST['proses'])){
                            /*
                            * URUTAN LOGIKA
                            * 1.get data based on $_POST['condition']
                            * 2.insert into tabel batch_transaction2
                            */
                            $query_string = "SELECT * FROM `batch_transaction` WHERE ".$_POST['condition'];
                            $active_record->trans_begin();
                            $result = $active_record->query($query_string);
                            if(!$result){
                                $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                $active_record->trans_rollback();
                                exit();
                            }
                            /*
                             * INSERT INTO tbl_batch_transaction 2
                             */
                            $count = 0;
                            while ($obj = $result->fetch_object()) {
                                if($obj->flag_code == 'P' || $obj->flag_code == 'T'){
                                    $msg = "Data Request No:".$obj->request_no.", SO No:".$obj->so_no." Sudah pernah diproses!";
                                    $mis->print_msg("Error !", "error", $msg);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                $data_bt_2 = array(
                                    "request_no"    =>  $obj->request_no,
                                    "so_no"         =>  $obj->so_no,
                                    "docket_no"     =>  $obj->docket_no,
                                    "id_acceptance"  =>  $_POST["jns_proses"],
                                    "process_code"  =>  $_POST["kode_proses"],
                                    "remarks"       =>  $_POST["remark"],
                                   "received_vol"   =>  $_POST["proses"],
                                );
                                $save_status = $active_record->insert("batch_transaction2", $data_bt_2);
                                if(!$save_status){
                                    $mis->print_msg("Error !", "error", "MySql :".mysqli_error($active_record->koneksi));
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                $count++;
                            }
                            
                            $active_record->trans_commit();
                            $msg = "Sukses proses ".$count." data";
                            $msg .= "<br/><br/><a class='btn btn-success btn-sm' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>";
                            $mis->print_msg("Success !", "success", $msg);
                        }
                        else{
                            $mis->print_msg("ERROR !", "error", "Missing Kode Proses atau Remarks<br/><a class='btn btn-primary btn-xs' href=\"return_truck.php\"><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>");exit();
                        }
                    }
                    ?>
                </div>
                <div class="col-sm-3 colsm-offset-3">
                    
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-datetimepicker.js"></script>
        <script src="js/userdefined.js"></script>
    </body>
</html>
        <?php
    
}
else{
    header("Location:return_truck.php");
}
?>


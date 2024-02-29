<?php
session_start();
//kode menu
$_SESSION['menu'] = "truck_list";
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    header("Location:index.php");
}

$object = (object)$_SESSION['login'];
if($object->group_id != 4){ // administrator only
    header("Location:index.php");
}


include './inc/constant.php';
include './lib/active_record.php';
include './lib/mis.php';
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
        <title>List Driver Truck Mixer</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
        <link rel="stylesheet" href="css/jquery.dataTables_themeroller.css"/>
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>

    <?php
        include "inc/menu.php";
        ?>
        <div class="loading"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Truck Mixer List</h1>
                </div>
                <div class="col-lg-12">
                    <div class="alert alert-danger" id="messages"><i class="fa fa-ban"></i> Alert</div>
                </div>
                
                <div class="col-md-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">List Truck</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php

                                $query_string = "SELECT * FROM `t_truckmixer`";

                                $result = $active_record->query($query_string);

                                if(!$result){
                                    $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                    $active_record->trans_rollback();
                                    exit();
                                }

                                ?>
                                <table class="table table-bordered table-hover table-striped tbl_driver" id="example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Driver</th>
                                            <th>Nomor Polisi</th>
                                            <th>Nomor Telepon</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        while ($obj = $result->fetch_object()) {
                                        ?>
                                        <tr>

                                            <td><?php echo $count++;?></td>
                                            <td><?php echo $obj->driver_name;?></td>
                                            <td><?php echo $obj->no_pol;?></td>
                                            <td><?php echo $obj->no_telp;?></td>
                                           
                                            <td class="text-left">
                                               
                                                <a href="" class="btn btn-xs btn-info edit_driver" data-driverid="<?php echo $obj->driver_id;?>" data-drivername="<?php echo $obj->driver_name;?>" data-nopol="<?php echo $obj->no_pol;?>" data-notelp="<?php echo $obj->no_telp;?>" title="Edit data"><i class="fa fa-edit"></i>
                                                </a>

                                                <a href="" class="btn btn-xs btn-danger delete_driver" data-id="<?php echo $obj->driver_id;?>" title="Hapus data"><i class="fa fa-remove"></i>
                                                </a>
                                               
                                            </td>

                                        </tr>
                                        <?php
                                        }
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 edit_code_wrapper" style="display: none;">
                    <div class="panel panel-success">
                        <div class="panel-heading">Form Edit Data</div>
                        <div class="panel-body">
                            <form method="post" id="frm_edit_driver" class="form-horizontal" role="form">
                                <fieldset class="col-lg-12">
                                    <div class="component">
                                        <?php

                                        $query_c = "SELECT A.no_pol,A.driver_name,A.no_telp "
                                                . "FROM `t_truckmixer` A "
                                                . "WHERE A.`driver_id`!=0";


                                        $result_c = $active_record->query($query_c);


                                        if(!$result_c){
                                            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                            exit();
                                        }
                                        ?> 
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Plat Nomor: </label>  
                                            <div class="col-md-5">

                                                <input type="hidden" id="driverid_edit" readonly="" value="" type="text" placeholder="Driver Id" class="form-control input-md">

                                                <input id="nopol_edit" readonly="" value="" type="text" placeholder="Plat Nomor" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Nama Driver :</label>  
                                            <div class="col-md-5">
                                                <input id="namaDriver_edit"  value="" type="text" placeholder="Nama Driver" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Nomor Telepon :</label>  
                                            <div class="col-md-8">
                                                <input id="noTelp_edit" value="" type="text" placeholder="Nomor Telepon" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput"></label>  
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-sm btn-info">Perbaharui&nbsp;!</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 add_code_wrapper">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Form Tambah Data</div>
                        <div class="panel-body">
                            <form method="post" id="frm_add_new_driver" class="form-horizontal" role="form">
                                <fieldset class="col-lg-12">
                                    <div class="component">
                                       
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Plat Nomor: </label>  
                                            <div class="col-md-5">
                                                <input id="flatNo" Name="flatNo"  type="text" placeholder="Plat Kendaraan" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Nama Driver :</label>  
                                            <div class="col-md-5">
                                                <input id="namaDriver" Name="namaDriver"  type="text" placeholder="Nama Driver" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">No Telepon :</label>  
                                            <div class="col-md-8">
                                                <input id="noTelp" Name="noTelp"  type="text" placeholder="Nomor Telepon" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput"></label>  
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-sm btn-primary">Tambah&nbsp;!</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include "inc/version.php";
         ?>

        <script type="text/javascript" src="js/jquery.1.11.1.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <!--<script type="text/javascript" src="js/bootbox.min.js"></script>-->
        <script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
        <!--<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
        <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery-ui.custom.js"></script>
        <script type="text/javascript" src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.effect-shake.js"></script>
        <script type="text/javascript" src="js/user_tmixer.js"></script>
    </body>
</html>      


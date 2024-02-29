<?php
session_start();
//kode menu
$_SESSION['menu'] = "list_acceptance";
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
        <title>List Acceptance Code</title>
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
                    <h1 class="page-header">Acceptance Code</h1>
                </div>
                <div class="col-lg-12">
                    <div class="alert alert-danger" id="messages"><i class="fa fa-ban"></i> Alert</div>
                </div>
                <div class="col-md-4 edit_code_wrapper" style="display: none;">
                    <div class="panel panel-info">
                        <div class="panel-heading">Form Edit Data</div>
                        <div class="panel-body">
                            <form method="post" id="frm_edit_code_acceptance" class="form-horizontal" role="form">
                                <fieldset class="col-lg-12">
                                    <div class="component">
                                        <?php
                                        $query_c = "SELECT A.id,A.desc "
                                                . "FROM `tbl_acceptance_category` A "
                                                . "WHERE A.`id`!=1";
                                        $result_c = $active_record->query($query_c);
                                        if(!$result_c){
                                            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                            exit();
                                        }
                                        ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Kategori: </label>  
                                            <div class="col-md-5">
                                                <select class="form-control" name="cate" id="cate_edit">
                                                <?php
                                                while ($obj_c = $result_c->fetch_object()) {
                                                    ?>
                                                    <option value="<?php echo $obj_c->id;?>"><?php echo $obj_c->desc;?></option>                                                    
                                                        <?php
                                                }
                                                ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Kode baru :</label>  
                                            <div class="col-md-5">
                                                <input id="code_edit" readonly="" value="" type="text" placeholder="Kode" class="form-control input-md angka">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Keterangan :</label>  
                                            <div class="col-md-8">
                                                <input id="desc_edit" value="" type="text" placeholder="Keterangan" class="form-control input-md">
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
                            <form method="post" id="frm_add_code_acceptance" class="form-horizontal" role="form">
                                <fieldset class="col-lg-12">
                                    <div class="component">
                                        <?php
                                        $query_c = "SELECT A.id,A.desc "
                                                . "FROM `tbl_acceptance_category` A "
                                                . "WHERE A.`id`!=1";
                                        $result_c = $active_record->query($query_c);
                                        if(!$result_c){
                                            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                            exit();
                                        }
                                        ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Kategori: </label>  
                                            <div class="col-md-5">
                                                <select class="form-control" name="cate" id="cate" >
                                                    <option value=""> - pilih - </option>
                                                <?php
                                                while ($obj_c = $result_c->fetch_object()) {
                                                    ?>
                                                    <option value="<?php echo $obj_c->id;?>"><?php echo $obj_c->desc;?></option>                                                    
                                                        <?php
                                                }
                                                ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Kode baru :</label>  
                                            <div class="col-md-5">
                                                <input id="newcode" value="" type="text" placeholder="Kode Baru" class="form-control input-md angka">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Keterangan :</label>  
                                            <div class="col-md-8">
                                                <input id="desc" value="" type="text" placeholder="Keterangan" class="form-control input-md">
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
                <div class="col-md-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Data Table</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php
                                $query_string = "SELECT A.code,A.desc,B.desc cate_name,B.`id` "
                                        . "FROM `tbl_code_acceptance` A "
                                        . "INNER JOIN `tbl_acceptance_category` B "
                                        . "ON A.`id_acceptance`=B.`id`";
                                $result = $active_record->query($query_string);
                                if(!$result){
                                    $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                ?>
                                <table class="table table-bordered table-hover table-striped tbl_acceptance" id="example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Category</th>
                                            <th>Description</th>
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
                                            <td><?php echo $obj->code;?></td>
                                            <td>
                                                <?php 
                                                if($obj->id == 1){
                                                    ?>
                                                <span class="label label-success">
                                                <?php
                                                }
                                                elseif($obj->id == 2){
                                                    ?>
                                                <span class="label label-info">
                                                <?php
                                                }
                                                elseif($obj->id == 3){
                                                    ?>
                                                <span class="label label-warning">
                                                <?php
                                                }
                                                elseif($obj->id == 4){
                                                ?>
                                                <span class="label label-danger">
                                                <?php
                                                }
                                                ?>
                                                <?php echo $obj->cate_name;?></span>
                                            </td>
                                            <td class="text-capitalize"><?php echo $obj->desc;?></td>
                                            <td class="text-left">
                                                <?php
                                                if($obj->id != 1){
                                                ?>
                                                <a href="" class="btn btn-xs btn-info edit_code_acceptance" data-id="<?php echo $obj->code;?>" data-cate="<?php echo $obj->id;?>"  data-desc="<?php echo $obj->desc;?>" title="Edit data"><i class="fa fa-edit"></i></a>
                                                <a href="" class="btn btn-xs btn-danger delete_code_acceptance" data-id="<?php echo $obj->code;?>" title="Hapus data"><i class="fa fa-remove"></i></a>
                                                <?php }?>
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
        <script type="text/javascript" src="js/userdefined.js"></script>
    </body>
</html>

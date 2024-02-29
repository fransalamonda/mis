<?php
session_start();
//kode menu
$_SESSION['menu'] = "list_bom";
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    header("Location:index.php");
}

$object = (object)$_SESSION['login'];
if($object->group_id != 4 && $object->group_id != 1){ // administrator only
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
        <title>List BOM Code</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
        <link rel="stylesheet" href="css/jquery.dataTables_themeroller.css"/>
<!--        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/> -->
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
                    <h1 class="page-header">BOM Code</h1>
                </div>
                <div class="col-lg-12">
                    <div class="alert alert-danger" id="messages"><i class="fa fa-ban"></i> Alert</div>
                </div>
                <div class="col-md-4 edit_code_var_bom" style="display: none;">
                    <div class="panel panel-info">
                        <div class="panel-heading">Form Edit Data</div>
                        <div class="panel-body">
                            <form method="post" id="frm_edit_code_bom" class="form-horizontal" role="form">
                                <fieldset class="col-lg-12">
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Code :</label>  
                                            <div class="col-md-5">
                                                <input id="code" value="" readonly="" type="text" placeholder="Code" class="form-control input-md angka">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">New Name :</label>  
                                            <div class="col-md-5">
<!--                                                <input id="name_edit_bom" readonly="" value="" type="text" placeholder="New Name" class="form-control input-md"> -->
                                                <input id="name_edit_bom" value="" type="text" placeholder="New Name" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">New Desc :</label>  
                                            <div class="col-md-8">
                                                <input id="desc_edit" value="" type="text" placeholder="Description" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput"></label>  
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-sm btn-info">Edit&nbsp;!</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-4 add_code_var_bom">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Form Add Data</div>
                        <div class="panel-body">
                            <form method="post" id="frm_add_code_bom" class="form-horizontal" role="form">
                                <fieldset class="col-lg-12">
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Code Version: </label>  
                                            <div class="col-md-5">
                                                <input id="codeversion" value="" type="text" placeholder="New Code" class="form-control input-md angka">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Name Version:</label>  
                                            <div class="col-md-5">
                                                <input id="nameversion" value="" type="" placeholder="Kode Baru" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">Description :</label>  
                                            <div class="col-md-8">
                                                <input id="desc" value="" type="text" placeholder="Description" class="form-control input-md">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="component">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput"></label>  
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-sm btn-primary">Add&nbsp;!</button>
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
                                $query_string = "SELECT * FROM `tbl_code_bom`";
                                $result = $active_record->query($query_string);
                                if(!$result){
                                    $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                                    $active_record->trans_rollback();
                                    exit();
                                }
                                ?>
                                <table class="table table-bordered table-hover table-striped tbl_bom" id="example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Name</th>
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
                                            <td>                        <?php echo $obj->code_bom;?></td>
                                            <td>                        <?php echo $obj->name_bom;?></td>
                                            <td class="text-capitalize"><?php echo $obj->desc;?></td>
                                            <td class="text-left">
                                                <?php
                                                if($obj->code_bom != 1){
                                                ?>
                                                <a href="" class="btn btn-xs btn-info edit_code_bom" data-id="<?php echo $obj->code_bom;?>" data-cate="<?php echo $obj->name_bom;?>"  data-desc="<?php echo $obj->desc;?>" title="Edit data"><i class="fa fa-edit"></i></a>
                                                <a href="" class="btn btn-xs btn-danger delete_code_bom" data-id="<?php echo $obj->code_bom;?>" title="Hapus data"><i class="fa fa-remove"></i></a>
                                               <?php
                                                }
                                               ?>
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

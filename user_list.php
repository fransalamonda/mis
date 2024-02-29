 <?php
session_start();
//kode menu
$_SESSION['menu'] = "user_list";
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
	$date = date("d/m/Y");
	
$active_record = new active_record(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$mis = new mis();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>List User</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
        <style type="text/css">
            th,td{
                font-size: 12px;
            }
        </style>
        <link rel="stylesheet" href="css/style.css" />
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                    $("div.alert").fadeOut(3000);
            });
    	</script>
	</head>
	<body>
            <div id="loading" style="">
            <img style="" src="images/loading_1.gif" />
        </div>
            <div id="bg-loading"></div>
        <?php
        include"inc/menu.php";
        ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 colsm-offset-3">
                        <h1 class="page-header">List User</h1>
					
                        <?php
                        $q = "SELECT A.`id_user`,A.`username`,B.`group_name` "
                                . "FROM `tbl_user` A "
                                . "INNER JOIN `tbl_group_user` B ON A.`group_id` = B.`id_group` "
                                . "WHERE B.`id_group` != 4";
                        $result = $active_record->query($q);
                        if(!$result){
                            $mis->print_msg("Error !", "error", "MySql :".$active_record->koneksi->error);
                            $active_record->trans_rollback();
                            exit();
                        }
                        ?>
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-lg-12 pull-left">
                                    <div id="messages" class="alert alert-danger">asasd</div>
    <!--                                <form role="form" method="GET">
                                      <div class="form-group input-group">
                                        <input type="text" class="form-control" id="filter" name="filter" placeholder="Machine Code"/>
                                            <span class="input-group-btn">
                                              <button class="btn btn-default" style="padding-top: 9px;padding-bottom: 9px;" type="submit"><i class="fa fa-search"></i></button>
                                            </span>
                                      </div>
                                    </form>-->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">

                                    <h4><small>Data List</small></h4>   
                                    <table class="table table-bordered table-striped table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="width:25px;">No</th>
                                                <th>ID User</th>
                                                <th>Nama</th>
                                                <th>Group</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                              <?php
                                            $i=1;
                                    while ($obj = $result->fetch_object()){
                                        $link = "";
                                        ?>
                                            <tr class="user-<?php echo $obj->id_user;?>">
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $obj->id_user;?></td>
                                                <td><?php echo $obj->username;?></td>
                                                <td><?php echo $obj->group_name;?></td>
                                                <td>
                                                    <a href="#" class="delete" data-id="<?php echo $obj->id_user;?>">Hapus</a> | 
                                                    <a href="#" class="reset" data-id="<?php echo $obj->id_user;?>">Reset</a>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?> 
                                        </tbody>
                                    </table>
                                    <a class="btn btn-primary" href="user_add.php">User Baru&nbsp;<i class="fa fa-plus"></i></a>
                                    <br/>
                                    <br/>
                                </div>
                            </div>
                        </div>
                </div>
                </div>
            <?php
    include "inc/version.php";
    ?>
            </div>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/user.js"></script>
    </body>
</html>
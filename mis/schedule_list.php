 <?php
 session_start();
include "db.php";
$date = date("d/m/Y");
	
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
                    $status_list = false;
                    
                        $q = "SELECT * FROM `tbl_schedule`";
                        
                        $result = mysql_query($q);
                        if(!$result) die(mysql_error());
                        $status_list = true;
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
                                <?php
                                if($status_list == true){
                                    ?>
                                <h4><small>Data List</small></h4>   
                                    <?php
                                }
                                ?>
                                
                                <form method="post" action="" onSubmit="return validate();">
                                    <table class="table table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="width:25px;">No</th>
                                                <th>Jam</th>
                                                <th>Created By</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
            			              <?php
                                if($status_list == true){
                                            $i=1;
                                    while($row = mysql_fetch_array($result)){
                                        $link = "";
                                        ?>
                                            <tr>
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $row['jam'];?></td>
                                                <td><?php echo $row['createdby'];?></td>
                                                <td>
                                                    <a href="#" class="delete" data-id="<?php echo $row['id'];?>">Hapus</a> | 
                                                    <a href="#" class="reset" data-id="<?php echo $row['id'];?>">Reset</a>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                }
                                ?>
            			                
                                        </tbody>
                                    </table>
                                </form>
                                <a class="btn btn-primary" href="schedule_add.php">Tambah Data</a>
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
            <script>
//            $(document).ready(function() {
//                
//            });
            </script>
    </body>
</html>
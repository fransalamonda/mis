<?php
session_start();
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
include './inc/constant.php';
if (isset($_POST['mark']) && !empty($_POST['mark'])){
    
    $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
    // Check DB connection
    if(mysqli_connect_errno()){ die("Failed to connect to MySQL: " . mysqli_connect_error());}
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
        <title>Return Truck Data Review</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
    </head>
    <body>
        <?php
        include "inc/menu.php";
        ?>
        <div class="loading"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12"><h1 class="page-header">Batch Transaction Acceptance - Review</h1></div>
                <div class="col-sm-9 colsm-offset-3">
                    <?php
                    $mark = $_POST['mark'];
                    $condition = " `docket_no` IN (";
                    for($i=0;$i<count($mark);$i++)
                        {
                            $condition.="'".$mark[$i]."'";
                            if($i < count($mark) -1)
                                {
                                    $condition.=",";
                                }
                        }
                        
                    $condition.=")";
                    $query_string = "SELECT * FROM `batch_transaction` WHERE ".$condition;
                    $result = mysqli_query($con,$query_string);
                    if(!$result) die("Error :".mysql_error());
                    // put your code here
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">Review Data Batch transaction</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover small">
                                    <thead>
                                        <tr>
                                            <th>Req No</th>
                                            <th>Seal No</th>
                                            <th>Docket No</th>
                                            <th>Prod Code</th>
                                            <th>Delv Vol</th>
                                            <th>So No</th>
                                            <th>Delv time</th>
                                            <th>Unit No</th>
                                            <th>Cust Name</th>
                                            <th>Proj Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                            ?>
                                        <tr>
                                            <td><?php echo $row['request_no'];?></td>
                                            <td><?php echo $row['seal_no'];?></td>
                                            <td><?php echo $row['docket_no'];?></td>
                                            <td><?php echo $row['product_code'];?></td>
                                            <td class="text-center"><?php echo $row['delv_vol'];?></td>
                                            <td><?php echo $row['so_no'];?></td>
                                            <td><?php echo $row['delv_time'];?></td>
                                            <td><?php echo $row['unit_no'];?></td>
                                            <td><?php echo $row['cust_name'];?></td>
                                            <td><?php echo $row['proj_name'];?></td>
                                        </tr>
                                                <?php
//                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 colsm-offset-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Form Action</div>
                        <?php
                        $query_string = "SELECT `id`,`desc` FROM `tbl_acceptance_category`";
                        $result_tbl = mysqli_query($con,$query_string);
                        if(!$result_tbl) die("Error :".mysqli_error());
                        ?>
                        <div class="panel-body">
                            <p>Silahkan pilih tindakan selanjutnya terhadap data di samping</p>
                            <form role="form" id="truck_return" method="POST" action="return_truck_review_act.php">
                                <input type="hidden" name="condition" value="<?php echo $condition;?>" />
                                <div class="form-group">
                                    <label for="jns_proses">Jenis Proses:</label>
                                    <select class="form-control" name="jns_proses" id="jns_proses">
                                        <option value=""> - Pilih jenis proses - </option>
                                        <?php
                                            while($row_tbl = mysqli_fetch_array($result_tbl,MYSQLI_ASSOC)){
                                        ?>
                                            <option value="<?php echo $row_tbl['id']?>"> <?php echo $row_tbl['desc']?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group kode_proses">          
                                </div>
                                <div class="form-group remark" >
                                    <input type="hidden" class="form-control" name="dev" id="dev" value="<?php echo $row['delv_vol'];?>">
                                </div>
                                <div class="form-group remark" style="display: none;">
                                    <label for="remark">Acceptance Volume:</label>
                                    <input id="proses" value="" name="proses" class="form-control">
                                </div>                                    
                                <div class="form-group remark" style="display: none;">
                                    <label for="remark">Remark:</label>
                                    <textarea class="form-control" rows="5" name="remark" id="remark"></textarea>
                                </div>                                
                                <button type="submit" class="btn btn-warning btn-sm red">Proses&nbsp;<i class="fa fa-arrow-right"></i></button>
                            </form>
                            <?php
                                        }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            include './inc/version.php';
        ?>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-dialog.min.js"></script>
        <script src="js/bootstrap-datetimepicker.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/userdefined.js"></script> 
    </body>
</html>
    <?php
    mysqli_close($con);
}
else{ header("Location:return_truck.php"); }
?>


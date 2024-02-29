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
                <div class="col-sm-12"><h1 class="page-header">Silahkan preview No Docket Di I Matrix</h1></div>
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
                    $query_string = "DELETE FROM `batch_transaction_detail` WHERE ".$condition;
//                    print_r($query_string);exit();
                    $result = mysqli_query($con,$query_string);
                    if(!$result) die("Error :".mysql_error());
                    // put your code here
                    ?>
                </div>
                <div class="col-sm-9 colsm-offset-3">
                <a class='btn btn-success btn-sm' href=\DEC/batch_error.php\><i class='fa fa-arrow-left'></i>&nbsp;Kembali</a>
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


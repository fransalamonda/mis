<?php
session_start();
include_once './inc/constant.php';
include "db.php";
//include 'db.php';
//kode menu
$_SESSION['menu'] = "index";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; 
//header("Location:login.php");
}else{
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIS Application</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
    <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style type="text/css">
        body, html {
            height: 100%;
        }
         
        .bg {
            background-image: url("images/bg.jpg");
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;

        }
        .obs-example{margin: 20px;}

    </style>
</head>
<body>

    
    <?php include "inc/menu.php"; ?>

    <div class="bg" >
        <br>
    <?php 
            $status_list = false;
            $conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
            mysqli_select_db($conn,DB_NAME);
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($conns,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){ while($row = mysqli_fetch_array($result)){ $chartno = $row['chart_no'];
            if($chartno == '1' ){ ?> 
            <div align="right" style="color:#000099"> <h4 style="color:white";><?php echo $row['name_bom']; ?></h4> </div>
                <?php } elseif($chartno == '2' ) {?>
            <div align="right" style="color:#FF0000"> <h4 style="color:white";><?php echo $row['name_bom']; ?></h4> </div>
                <?php }elseif($chartno == '3' ) {  ?>
            <div align="right" style="color:#077"> <h4 style="color:white";style="color:white";><?php echo $row['name_bom']; ?></h4></div>
                <?php }elseif($chartno == '4' ) {  ?>
            <div align="right" style="color:#0f1"><h4 style="color:white";><?php echo $row['name_bom']; ?></h4></div>
            <?php }elseif($chartno == '5' ) {  ?>
            <div align="right" style="color:#f90"><h4 style="color:white";><?php echo $row['name_bom']; ?></h4></div>
            <?php }elseif($chartno == '6' ) {  ?>
            <div align="right" style="color:#f9f"><h4 style="color:white";><?php echo $row['name_bom']; ?></h4></div>
            <?php }elseif($chartno == '7' ) {  ?>
            <div align="right" style="color:#f4f"> <h4 style="color:white";><?php echo $row['name_bom']; ?></h4></div>
            <?php }else{ ?>
            <div align="right" style="color:#0a9"><h4 style="color:white";><?php echo $row['name_bom']; ?></h4></div>
            <?php } } } ?>
    
        <div class="container" >
            <div class="row" style="text-align:center;margin:100px auto;" >
                <div align="center"> </div>
            </div>
            <?php include "inc/version.php"; ?>
        </div>

    </div>
    
</body>
</html>                                		
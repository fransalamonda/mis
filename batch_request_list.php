<?php
session_start();
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
$date = date("d/m/Y");
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
        <title>Batch Request List</title>
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
            include "inc/constant.php";
            $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
            if(mysqli_connect_errno()){ die("Failed to connect to MySQL: " . mysqli_connect_error());}
        ?>
        <div class="container-fluid">
            <div class="row"></div>
                
        </div>
    </body>
</html>

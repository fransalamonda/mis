<?php
session_start();
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; 
//header("Location:login.php");
}
//kode menu
$_SESSION['menu'] = "login";
include_once './inc/constant.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MIS Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/login.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <?php
    include './inc/menu.php';
    ?>
    <div class="loading">Loading&#8230;</div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
<!--    <div class="logo">
        <img  src="http://10.10.10.36/do_monitoring/assets/dist/img/cg.png" style="height: 100px;" />
        <img style="max-height: 80px" src="<?php echo ASSETS_PATH_ADMIN;?>layout/img/587542_728943_378088_1.jpg" alt=""/>
        <h1 style="color: brown">
            <center><b style="font-size: 30px">PT MOTIVE MULIA - MERAH PUTIH BETON</b></center>
            <br>
            <center>            <span style="color: red;">DEC Application</span> </center>
        </h1>
    </div>-->
                <br/>
                <br/>
                <br/>
                <div id="msg"class="alert alert-danger">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="login">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User ID" name="userid" id="userid" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" id="pass" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include './inc/version.php';
    ?>
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.10.2.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/login.js"></script>

</body>

</html>

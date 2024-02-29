<?php
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    exit($mysqli->connect_error);
}
$string_cari = "SELECT `jam` FROM `tbl_schedule`";
$result_cari = $mysqli->query($string_cari);
if(!$result_cari){
    exit($mysqli->error);
}

//exit("a");
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
        <title>Automation Retrieve Batch Transaction</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-theme.css" />
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
        <style type="text/css">
            #overlay {
                position: absolute;left: 0;
                top: 0;bottom: 0;right: 0;
                background: #fff;opacity: 0.8;
                filter: alpha(opacity=80);
                z-index: 9999;display: none;
            }
            #loading {
                position: absolute;
                top: 50%;
                left: 50%;
                margin: -28px 0 0 -25px;
                z-index: 10000;text-align: center;font-weight: bold;
            }
            #loading img{
                height: 100px;
            }
        </style>
    </head>
    <body>
        <div id="overlay">
            <div id="loading">
                <img src="images/cat.gif"><br> Processing
            </div>
        </div>
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.pho">MIS Application / Tiptop</a>
            </div>
<!--             /.navbar-header 

            
             /.navbar-top-links -->

            
        </nav>
        <div class="container">
            <div class="row">
                <?php if($result_cari->num_rows == 0){ ?>
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications Panel
                        </div>
                        <div class="panel-body">
                            <?php // exit("Data Jadwal Kosong"); ?>
                        </div>
                    </div>
                        <?php } 
                        $condition = "";
                        if($result_cari->num_rows > 0){
                            $num_rows = $result_cari->num_rows;
                            $i=0;
                            while($row = $result_cari->fetch_array(MYSQLI_ASSOC)){
                                $condition.="(";
                                $pieces = explode(":", $row['jam']);
                                if(strlen($pieces[0]) == 2 && $pieces[0][0] == '0'){
                                    $pieces[0]=$pieces[0][1];
                                }
                                $condition.="now.getHours() === ".$pieces[0]." && now.getMinutes() === ".$pieces[1]." && now.getSeconds() === 0";
                                $condition.=")";
                                if($num_rows > 1 && $i < $num_rows-1){
                                    $condition.=" || ";
                                }
                                $i++;
                            }
                        }
                        ?>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications Panel
                        </div>
                         <!--/.panel-heading--> 
                        <div class="panel-body">
                            <div class="list-group" style="max-height: 400px;overflow-y: scroll">
                                
                            </div>
                             <!--/.list-group--> 
                        </div>
                         <!--/.panel-body--> 
                    </div>
                     <!--/.panel .chat-panel--> 
                </div>
            </div>
        </div>
        
        <?php
        // put your code here
        ?>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/userdefined.js"></script>
        
        <script type="text/javascript">
            var loading         =   $("#overlay");
            var list_group      =   $("#list-group");
            $(document).ready(function(){
                process();
            
//                setInterval(function(){
//                    var now = new Date();
//                    console.log("Sekarang Pukul : "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds()+"\n");
//                //first, check time, if it is 9 AM, reload the page
//
////                 if ( <?php echo $condition;?>) { // If it is between 9 and ten AND the last refresh was longer ago than 6 hours refresh the page.
//                    console.log("sudah jam \n"+now.getHours()+":"+now.getMinutes());
//                    $(".list-group").append("<p>Jalan</p>");
//////                    ajaxProses();
////                 }
//
//                //do other stuff
//
//              },1000);
            });

            function process(){
            //    $(".list-group").append(item);
                var x = window.location.origin;
                process_ajax(x,"/mis/auto_ftp.php","");
            }
            function process_ajax(base_url,url,data){
                window.loading.show();
                jQuery.ajax({
                    type: "POST", // HTTP method POST or GET
                    url: base_url+url, //Where to make Ajax calls
                    dataType:"text", // Data type, HTML, json etc.
                    data:data, //Form variables
                    success:function(response){
                        var obj = jQuery.parseJSON(response);
                        if(obj.status === 1){
                            var item = "<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-shopping-cart fa-fw\"></i>"+obj.msg+"</a>";
                            $(".list-group").append(item);
                            window.loading.hide();
                        }
                        else if(obj.status === 0){
                            var item = "<a href=\"#\" class=\"list-group-item\"><i class=\"fa fa-shopping-cart fa-fw\"></i>"+obj.msg+"</a>";
                            $(".list-group").append(item);
                            window.loading.hide();
                        }
                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        window.loading.hide();
                        alert(thrownError);
                    }
                });
            }

        </script>
    </body>
</html>

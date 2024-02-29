<?php
session_start();
//kode menu
$_SESSION['menu'] = "full_export_akumulasi";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
include_once './inc/constant.php';
include "db.php";
?>

<!--
privileges : batcher (no need to login)
-->
<!doctype html>
<head>
    <title>Export Full Batch Transaction Akumulasi</title>
    <style>
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 400px;}*/
        .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #batch-add-adjustmen{ display:none; }
	#plant_id{ margin:0px auto; width:400px; }
	form{ margin:20px auto;width:50%; }
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/> 
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/style.css" />
	
</head>
<body class="corporate">
    <?php 
    include "inc/menu.php";
    $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
            if(mysqli_connect_errno()){ die("Failed to connect to MySQL: " . mysqli_connect_error());}
    ?>
    <div class="loading"></div>
    <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($con,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){
                while($row = mysqli_fetch_array($result)){$chartno = $row['chart_no'];
                    if($chartno == '1' ){ ?>
                <div align="right" style="color:#000099"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php } elseif($chartno == '2' ) { ?>
        <div align="right" style="color:#FF0000">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '3' ) {                
                            ?>
        <div align="right" style="color:#077">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '4' ) {                
                            ?>
        <div align="right" style="color:#0f1">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '5' ) {                
                            ?>
        <div align="right" style="color:#f90">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '6' ) {                
                            ?>
        <div align="right" style="color:#f9f">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '7' ) {                
                            ?>
        <div align="right" style="color:#f4f">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }else{
                              ?>
        <div align="right" style="color:#0a9">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }
                    }
            }
        ?>
        
<div class="container-fluid">
        
        <form action="ajax/tmp_exsport_akumulasi.php" method="post" enctype="multipart/form-data" id="query" class="form-horizontal " role="form"> 
                    <div class="form-group">
                    <h1>Export Full Batch Transaction Akumulasi</h1>
                    </div>
	      	<div id="messages" class="">asasd</div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Delivery Date :</label>  
                            <div class="col-md-4">
                                 <input id="delvdate" name="delvdate" type="text" placeholder="Delivery Date" class="form-control input-md" value="<?php echo date('Ymd'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="component input">
                            <div class="form-group">
                                <label class="col-md-4 control-label input" for="textinput"></label>  
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Retrieve&nbsp;<i class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="component text-center download">
                                <a class="btn btn-sm btn-success Full_n">Download Export Full&nbsp;<i class="fa fa-download"></i></a>
                            </div>
                        </div>
                </form>

     <?php
     include "inc/version.php";
     include "ajax/pop_up_query_adjustman.php";
    ?>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        (function() {
            $( "#delvdate" ).datepicker({ dateFormat: 'yymmdd' });
            var base_url    = location.origin+"/mis/";
            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
            var loading = $(".loading");
            var base_url    = location.origin;
                base_url+="/mis";
 //           var delv_date = $('#delv_date').val();
//            loading.show();
            $('form#query').ajaxForm({
                beforeSend: function() {
                    loading.show();
                },
                complete: function(xhr) {    
                    $('div.loading').hide();
                    console.log(xhr.responseText);    
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if(obj.status == '1'){
                        $('div#messages').html("<p>"+obj.msg+"</p>");
                        $('div#messages').removeClass("n_error").addClass("n_ok");
                        $('div#messages').hide();
                        $('div#batch-add-adjustmen').hide();
                        $('div#messages').fadeIn();
                        $('div#batch-add-adjustmen').slideDown();
                        $(".input").slideUp();
                        $(".download").fadeIn();
                        $('div.loading').hide();
                    }
                    else if(obj.status == '0'){
                        $('div#messages').hide();$('div#batch-add-adjustmen').slideUp();
                        $('div#messages').removeClass("n_ok").addClass("n_error");
                        $('div#messages').html("<p>"+obj.msg+"!</p>");
                        $('div#messages').fadeIn();$('div#detail').hide();
                        $('div.loading').hide();
                        //$(".download").fadeIn();
                    }
                }
            });
            
            $(".Full_n").click(function(e){
                e.preventDefault();
                var filter = $("#delvdate");
                window.open(base_url+"/ajax/batch_transaction_export_full_akumulasi.php?filter="+filter.val());  
                $(".Full_n").hide();
//                $(".mat_is_n").hide();
                //check_all_button();
            });
            $(".docket_n").click(function(e){
                e.preventDefault();
                var filter = $("#delvdate");
                window.open(base_url+"/ajax/export_to_full.php?code=1&filter="+filter.val());
                $(".docket_n").hide();
                $(".mat_is_n").show();
                //check_all_button();
            });
            $(".mat_is_n").click(function(e){
                e.preventDefault();
                var filter = $("#delvdate");
                window.open(base_url+"/ajax/export_to_full.php?code=2&filter="+filter.val());
                $(".mat_is_n").hide();
                //check_all_button();
            });
            $(".docket_m").click(function(e){
                e.preventDefault();
                var filter = $("#delvdate");
                window.open(base_url+"/ajax/export_to_full.php?code=3&filter="+filter.val());
                $(".docket_m").hide();
                $(".mat_is_m").show();
            });
            $(".mat_is_m").click(function(e){
                e.preventDefault();
                var filter = $("#delvdate");
                window.open(base_url+"/ajax/export_to_full.php?code=4&filter="+filter.val());
                $(".mat_is_m").hide();
            });

            function goToMessage(){
                $("body, html").animate({ 
                    scrollTop: $("#detail").offset().top 
                }, 60);
            }
        })();
    </script>
</body>
</html>
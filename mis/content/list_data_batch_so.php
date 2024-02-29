<?php
session_start();
//kode menu
$_SESSION['menu'] = "list_data_query";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER,DB_USER,DB_PASS);
if(!$mysqli){die("MySQL Error:".$mysqli->connect_error);}
$db_con = $mysqli->select_db(DB_NAME);
if(!$db_con){ exit("Database ".DB_NAME."Cannot be found"); }
$date = date("d/m/Y");
include 'db.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>List Data Batch Transaction</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
<!--        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>		-->
            <style>        
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 400px;}*/
        .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #export{display:none;}
        form{margin:20px auto;width:80%;}
    </style>
    </head>
    <body>
        <?php
        include "inc/menu.php";
        ?>

    <div id="loading" align="center"><img style="" src="images/loading_1.gif" /></div>
<!--        <div id="bg-loading"></div>-->
<div class="container-fluid" id="sala">
        <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
            $result = mysql_query($query_war);
            if(!$result) die(mysql_error());
            $status_list = true;
            if($status_list == true){
                while($row = mysql_fetch_array($result)){
                    $chartno = $row['chart_no'];
                    if($chartno == '1' ){
        ?>
                <div align="right" style="color:#000099"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php } elseif($chartno == '2' ) { ?>
        <div align="right" style="color:#FF0000"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '3' ) {  ?>
        <div align="right" style="color:#077"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '4' ) {  ?>
        <div align="right" style="color:#0f1"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '5' ) {  ?>
        <div align="right" style="color:#f90"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '6' ) {  ?>
        <div align="right" style="color:#f9f"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '7' ) {  ?>
        <div align="right" style="color:#f4f"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }else{ ?>
        <div align="right" style="color:#0a9"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php } } } ?>
        <div class="row">
            <div class="col-lg-12 panel-body">
                <div class="page-header"><div class="form-group"> <h1>List Data Batch Transaction</h1> </div></div>
                <form action="ajax/batch_add.php" method="POST" id="queryform" class="form-horizontal" role="form">
                    <div id="msg" class="alert alert-danger"></div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">From Date :</label>
                            <div class="input-group date form_date col-md-2" data-date="" data-date-format="yyyymmdd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" id="fromdate" name="fromdate" size="16" type="text" value="" placeholder="Pilih Tanggal" readonly="" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">To Date :</label>
                            <div class="input-group date form_date col-md-2" data-date="" data-date-format="yyyymmdd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" id="todate" name="todate" size="16" type="text" value="" placeholder="Pilih Tanggal" readonly="" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">So No :</label>
                            <div class="col-md-2">
                                <input id="so_no" value="" name="so_no" type="text" placeholder="SO Number" class="form-control input-md">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="padding-bottom: 5px;margin-bottom: 0px;">
                    <label class="col-md-4 control-label" for="textinput"></label>
                    <div class="col-md-4">
                        <button type="reset" class="btn  btn-default">Clear&nbsp;<i class="fa fa-remove"></i></button>
                        <button type="submit" class="btn  btn-primary">Submit&nbsp;<i class="fa fa-weibo"></i></button>
                    </div>
                    </div>
            </form>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"></div>
                            <div class="panel-body"><p></p></div>
                            <div class="table-responsive monda" id="monda">
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
</div>
<?php 
    include "inc/version.php";
    include "content/popup_detail.php";
?>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript">
                $('div#loading').hide();
//            $(document).ready(function() {
                $("div.alert").fadeOut();
//            });
    	</script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
        <script>
            function show_alert_ms(obj_msg,type,msg_text){
                obj_msg.hide();
                obj_msg.addClass('alert');
                if(type ===3){
                    obj_msg.removeClass('alert-danger').removeClass('alert-info').removeClass('alert-warning');
                    obj_msg.addClass('alert-success');
                    obj_msg.empty().append("<strong>SUCCESS!</strong><br/>").append(msg_text);
                    $('div#loading').hide();
                    $('div#sala').show();
                }
                else if(type === 2){
                    obj_msg.removeClass('alert-danger').removeClass('alert-info').removeClass('alert-success');
                    obj_msg.addClass('alert-warning');
                    obj_msg.empty().append("<strong>WARNING!</strong><br/>").append(msg_text);
                    $('div#loading').hide();
                    $('div#sala').show();
                }
                else if(type === 1){
                    obj_msg.removeClass('alert-danger').removeClass('alert-success').removeClass('alert-warning');
                    obj_msg.addClass('alert-info');
                    obj_msg.empty().append("<strong></strong><br/>").append(msg_text);
                    $('div#loading').hide();
                    $('div#sala').show();
                }
                else{
                    obj_msg.removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning');
                    obj_msg.addClass('alert-danger');
                    obj_msg.empty().append("<strong>Error</strong><br/>").append(msg_text);
                    $('div#loading').hide();
                    $('div#sala').show();
                }
                obj_msg.fadeIn();
                $('div#loading').hide();
                $('div#sala').show();
            }
            $(function() {
                var base_url    = location.origin+"/mis/";
                var loading_object = $(".loading");
                $("#queryform").submit(function(e){
                    e.preventDefault();
                    var fromdate = $("#fromdate");
                    var todate = $("#todate");
                    var so_no  = $("#so_no");
                    
                    if(fromdate.val() === '' && todate.val() === '' && so_no.val() === ''){show_alert_ms($("#msg"),99,"Data kosong");return false;}
                    var data = "fromdate="+fromdate.val()+"&todate="+todate.val()+"&so_no="+so_no.val();
                    $('div#loading').show();
                    $('div#sala').hide();
                    //loading_object.show();
                    jQuery.ajax({
                        type: "POST", // HTTP method POST or GET
                        url: base_url+"ajax/batch_transaction_query.php", //Where to make Ajax calls
                        dataType:"text", // Data type, HTML, json etc.
                        data:data, //Form variables
                        success:function(response){
                            var obj = jQuery.parseJSON(response);
                            if(obj.status === 1){
                                show_alert_ms($("#msg"),1,obj.msg);
//                                $("#msg").html(obj.msg);
                                $("#monda").html(obj.tblso);
                            }
                            else if(obj.status === 0){
                                show_alert_ms($("#msg"),0,obj.msg);
                            //                goToMessage();
                            }
                            $('div#loading').hide();
                            $('div#sala').show();
                            (base_url,data,"refresh",msg);
                        }
                    });
                });  
            });
        </script>
        <script>
            var base_url    = location.origin+"/mis/";
            var loading_object = $(".loading");
            $("#example").on('click', 'a.view', function(e){
                    e.preventDefault(e);
                    $("#msg").hide();
                    $("#msg_popup").hide();
                    var prod            = $(this).data("prod");
                    var docket    = $(this).data("docket");
                    var data = "prod="+prod+"&docket="+docket;
                    loading_object.show();
                    jQuery.ajax({
                        type: "POST", // HTTP method POST or GET
                        url: base_url+"ajax/Batch_detail_retrieve.php", //Where to make Ajax calls
                        dataType:"text", // Data type, HTML, json etc.
                        data:data, //Form variables
                        success:function(response){
                            var obj = jQuery.parseJSON(response);
                            if(obj.status === 1){
                                $('#test').modal('show');
                                $("#popup_table").html(obj.msg);
                                $("#mix_wrapper").html(obj.mix_str);
                            }
                            else if(obj.status === 0){
                                show_alert_ms($("#msg"),0,obj.msg);
                //                goToMessage();
                            }
                            loading_object.hide();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            loading_object.hide(); 
                            alert(thrownError);
                        }
                    });
                });
        </script>
        <script type="text/javascript">
            $('.form_datetime').datetimepicker({
                //language:  'fr',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                        forceParse: 0,
                showMeridian: 1
            });
                $('.form_date').datetimepicker({
                weekStart: 1,
                todayBtn:  1,
                        autoclose: 1,
                        todayHighlight: 1,
                        startView: 2,
                        minView: 2,
                        forceParse: 0
            });
                $('.form_time').datetimepicker({
                language:  'fr',
                weekStart: 1,
                todayBtn:  1,
                        autoclose: 1,
                        todayHighlight: 1,
                        startView: 1,
                        minView: 0,
                        maxView: 1,
                        forceParse: 0
            });
        </script>
    </body>
</html>

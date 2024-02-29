<?php
session_start();

//kode menu
$_SESSION['menu'] = "list_data";

if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER,DB_USER,DB_PASS);
if(!$mysqli){die("MySQL Error:".$mysqli->connect_error);}
$db_con = $mysqli->select_db(DB_NAME);
if(!$db_con){
    exit("Database ".DB_NAME."Cannot be found");
}
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
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>		
    </head>
    <body>
        <?php
        include "inc/menu.php";
        ?>
        <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom
                            FROM `mix_package_composition` AS A
                            INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no`
                            WHERE A.`code_trans` = 'Y'";   
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 colsm-offset-3">
                    <h1 class="page-header">List Data Batch Transaction</h1>
                    <?php
                    
         
                        
                        
//                        print_r($q);exit();
                        $hasil = $mysqli->query($q);
                        if(!$hasil){
                            exit(mysqli_error($mysqli));
                        }
                        $status_list = true;                        
                    
?>
                    
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-lg-2 pull-left">
                                <form role="form" method="GET" class="form-inline">
                                    <div class="form-group" style="padding-bottom: 5px;margin-bottom: 0px;">
                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyymmdd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" name="filter" size="16" type="text" value="" placeholder="From Date" readonly="" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input2" value="" /><br/>
                                    </div>
                                    <div class="form-group" style="padding-bottom: 5px;margin-bottom: 0px;">
                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyymmdd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" name="todate" size="16" type="text" value="" placeholder="To Date" readonly="" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input2" value="" /><br/>
                                    </div>
                                    <div class="component">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <input id="so_no" value="" name="so_no" type="text" placeholder="SO Number" class="form-control input-md">
                                        </div>
                                    </div>
                                    </div>                                    
                                    <button type="submit" class="btn btn-primary">Search&nbsp;<i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Data Table</div>
                                    <div class="panel-body">
                                        <p></p>
                                    </div>
                                    <div class="table-responsive">
                                            <table class="table small table-striped" id="example">
                                                <thead>
                                                    <tr class="bold">
<!--                                                        <th>
                                                            <input type="checkbox" id="selectall">
                                                        </th>-->
                                                        <th>No</th>
                                                        <th>Seal No</th>
                                                        <th>Docket No</th>
                                                        <th>Prod Code</th>
                                                        <th>Vol</th>
                                                        <th>So No</th>
                                                        <th>Time</th>
                                                        <th>Date</th>
                                                        <th>Unit No</th>
                                                        <th>Driver Name</th>
                                                        <th>Cust Name</th>
                                                        <th>Proj Name</th>
                                                        <th>Flag</th>
                                                        <th>View</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      <?php
                                              if($status_list == true){
                                                    $i=1;
                                                    $delv_vol_sum = 0;
                                                    while($row = $hasil->fetch_array(MYSQLI_ASSOC) ){
                                                        $link = "";
                                                        $delv_vol_sum+=$row['delv_vol'];
                                                        ?>
                                                    <tr class="">
                                                        <!--<td><input type="checkbox" class="checkbox1" name="mark[]" value="<?php echo $row['docket_no'];?>" /></td>-->
                                                        <td><?php echo $i++;?></td>
                                                        <td><?php echo $row['seal_no'];?></td>
                                                        <td><?php echo $row['docket_no'];?></td>
                                                        <td><?php echo $row['product_code'];?></td>
                                                        <td class="text-center"><?php echo $row['delv_vol'];?></td>
                                                        <td><?php echo $row['so_no'];?></td>
                                                        <td><?php echo $row['delv_time'];?></td>
                                                        <td><?php echo $row['delv_date'];?></td>
                                                        <td><?php echo $row['unit_no'];?></td>
                                                        <td><?php echo $row['driver_name'];?></td>
                                                        <td><?php echo $row['cust_name'];?></td>
                                                        <td><?php echo $row['proj_name'];?></td>
                                                        <td><?php echo $row['flag_code'];?></td>
                                                        <td><a href="" data-docket="<?php echo $row['docket_no'];?>" data-prod="<?php echo $row['product_code'];?>" class="view btn btn-xs btn-warning">View</a></td> 
                                                    </tr>
                                                        <?php
                                                        }
                                                ?>
                                                    <tr>
                                                        <td colspan="4" class="text-right">Total Delv Vol :</td>
                                                        <td colspan="8"><?php echo $delv_vol_sum;?></td>
                                                    </tr>
                                                <?php
                                              }
                                                      ?>

                                            </tbody>
                                        </table>
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
		</div>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                    $("div.alert").fadeOut(5000);
            });
    	</script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>

    <script>
        var base_url    = location.origin+"/mis/";
        var loading_object = $(".loading");
        $("table#example").on('click', 'a.view', function(e){
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
    <script>
	$(function() {
		$( "#filter" ).datepicker({ dateFormat: 'yymmdd' });
//                var cur_url         = window.location;
//                setTimeout(function(){
//                                            window.location = cur_url;
//                                        }, 3000);
                
	});
        $(document).ready(function() {
            $('#selectall').click(function(event) {  //on click 
                if(this.checked) { // check select status
                    $('.checkbox1').each(function() { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "checkbox1"               
                    });
                }else{
                    $('.checkbox1').each(function() { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                    });         
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

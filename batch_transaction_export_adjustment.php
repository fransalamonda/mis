<?php
session_start();

//kode menu
$_SESSION['menu'] = "full_export_adjustment";

if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
if($object->group_id != 3 && $object->group_id !=4 && $object->group_id !=6){
    header("Location:index.php");
}
$date = date("d/m/Y");
include "inc/constant.php";
include 'db.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
            <title>Batch Transaction Export Full Report Adjustment</title>
            <link rel="stylesheet" href="css/normalize.css" />
            <link rel="stylesheet" href="css/bootstrap.min.css"/>
            <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
            <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
            <link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
            <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
            <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>	
	</head>
	<body>
        <?php
            include "inc/menu.php";
            $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
            if(mysqli_connect_errno()){ die("Failed to connect to MySQL: " . mysqli_connect_error());}
        ?>
        <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom
                            FROM `mix_package_composition` AS A
                            INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no`
                            WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($conns,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){
                    while($row = mysqli_fetch_array($result)){
                        $chartno = $row['chart_no'];
                        if($chartno == '1' ){
        ?>
                <div align="right" style="color:#000099">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
        <?php
                        } elseif($chartno == '2' ) {                
                            ?>
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
                <div class="row">
                    <div class="col-sm-12 colsm-offset-3">
                        <h1 class="page-header">
                            Export Full Batch Transaction Adjustment
                            <?php
                             if(isset($_GET['filter']) && !empty($_GET['filter'])){
                                 $ymd = DateTime::createFromFormat('Ymd', $_GET['filter'])->format('Y M d');
                                 echo"<span class='small'>".$ymd."</span>";
                             }
                            ?>
                        </h1>
					
                    <?php
                    $status_list = false;
                    if(isset($_GET['filter']) && !empty($_GET['filter'])){
                        if(isset($_GET['time']) && !empty($_GET['time'])){
                            extract($_GET);
                            $q = "SELECT * FROM `batch_transaction` "
                                    . "WHERE (UPPER(`product_code`)='Adjustment' OR UPPER(`product_code`)='SELFUSAGE')"
                                    . "AND `delv_date` = '$filter' "
                                    . "AND `delv_time` BETWEEN '$time' AND '23:59'
                                    ORDER BY `docket_no` ASC";
                        }
                        else{
                            extract($_GET);
                            $q = "SELECT A.* FROM `batch_transaction` A "
                                    . "LEFT JOIN `batch_transaction2` B ON A.`docket_no` = B.`docket_no` "
                                    . "WHERE (UPPER(`product_code`)='Adjustment' OR UPPER(`product_code`)='SELFUSAGE')"
                                    . "AND `delv_date` = '".$filter."' AND `mch_code`='".PLANT_ID."' "
                                    . "ORDER BY `docket_no` ASC";
                        }
                        
                        $result = mysqli_query($con,$q);
                        $count = mysqli_num_rows($result);
                        if(!$result) die(mysqli_error($con));
                        $status_list = true;
                    }
                    ?>
                    
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-lg-2 pull-left">
                                <form role="form" method="GET">
                                    <div class="form-group" style="padding-bottom: 0px;margin-bottom: 0px;">
                                        <div class="input-group col-md-12" >
                                            <input class="form-control" id="filter" name="filter" size="16" type="text" value="<?php echo date('Ymd'); ?>" placeholder="Pilih Tanggal" readonly="" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input2" value="" /><br/>
                                    </div>
<!--                                    <div class="form-group" style="padding-bottom: 0px;margin-bottom: 0px;">
                                      <div class="input-group date form_time col-md-12" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                                          <input class="form-control" size="16" type="text" name="time" value="" readonly="" placeholder="Pilih Jam" />
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                      </div>
                                      <input type="hidden" id="dtp_input3" value="" /><br/>
                                    </div>-->
<button type="submit" class="btn btn-info btn-sm">Search&nbsp;<i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">List Data</div>
                                    <div class="panel-body">
                                        <form method="post" action="act/batch_transaction_export_act_adjustment.php" >
                                            <div class="table-responsive">
                                                <table class="display compact" id="example">
                                                    <thead style="font-size: small">
                                                        <tr>
            <!--                                                <th>
                                                                <input type="checkbox" onclick="checkAll(this)">
                                                            &nbsp;</th>-->
                                                            <th>No&nbsp;</th>
                                                            <th>SO No&nbsp;</th>
                                                            <th>Docket No&nbsp;&nbsp;</th>
                                                            <th>Prod Code&nbsp;</th>
                                                            <th>Vol&nbsp;</th>
                                                            <th>Time&nbsp;</th>
                                                            <th>Unit No&nbsp;</th>
                                                            <th>Driver Name&nbsp;</th>
                                                            <th>Cust Name&nbsp;</th>
                                                            <th>Proj Name&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: medium">
                                                          <?php
                                                  if($status_list == true){
                                                        $i=1;
                                                        $delv_vol_sum = 0;
                                                        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                            $link = "";
                                                            $delv_vol_sum+=$row['delv_vol'];
                                                            ?>
                                                        <tr class="small">
            <!--                                                <td>
                                                                <input type="hidden" name="mark[]" value="<?php echo $row['so_no'];?>" />
                                                                <input type="hidden" value="<?php echo $row['mch_code'];?>" name="mach_code" />
                                                                <input type="hidden" value="<?php echo $row['delv_date'];?>" name="delv_date" />
                                                            </td>-->
                                                            <td><?php echo $i++;?></td>
                                                            <td><?php echo $row['so_no'];?></td>
                                                            <td><?php echo $row['docket_no'];?></td>
                                                            <td><?php echo $row['product_code'];?></td>            			                  
                                                            <td><?php echo $row['delv_vol'];?></td>
                                                            <td><?php echo $row['delv_time'];?></td>
                                                            <td><?php echo $row['unit_no'];?></td>
                                                            <td><?php echo $row['driver_name'];?></td>
                                                            <td><?php echo $row['cust_name'];?></td>
                                                            <td><?php echo $row['proj_name'];?></td>
                                                        </tr>
                                                    <?php
                                                        }
                                                    ?>
<!--                                                    <tr>
                                                        <td colspan="5" class="text-right">Total Delv Vol :</td>
                                                        <td colspan="8"><?php echo $delv_vol_sum;?></td>
                                                    </tr>-->
                                                    <?php
                                                  }
                                                    ?>

                                                          </tbody>
                                                </table>
                                            </div>
                                <?php
                                if(isset($count) && $count > 0){
                                ?>
                                            <input type="hidden" name="table" value="batch_transaction" />
                                            <input type="hidden" name="filter" value="<?php echo $_GET['filter']; ?>" />
                                    <button class="btn  btn-success" type="submit" name="submit" value="aa"> <i class="glyphicon glyphicon-download-alt"></i>&nbsp;Export</button>
                                <?php
                                }
                                ?>
                                        </form>
                                    </div>
                                </div>
                                <iframe style="display: none;" src="" name="download"></iframe>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
            <?php
    include "inc/version.php";
    ?>
		</div>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-datetimepicker.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
        <script language="javascript">
            function validate(){
                var chks = document.getElementsByName('mark[]');
                var hasChecked = false;
                for (var i = 0; i < chks.length; i++)
                {
                    if(chks[i].checked){
                        hasChecked = true;
                        break;
                    }
                    
                }
                if (hasChecked == false)
                {
                    alert("Please select at least one.");
                    return false;
                }
                if(hasChecked == true){
                    return confirm("Anda yakin reset data ini?");
                }
                //return true;
            }
            function checkAll(bx) {
                var cbs = document.getElementsByTagName('input');
                for(var i=0; i < cbs.length; i++) {
                  if(cbs[i].type == 'checkbox') {
                  cbs[i].checked = bx.checked;
                 }
               }
            }
    </script>
    <script>
	$(function() {
            $( "#filter" ).datepicker({ dateFormat: 'yymmdd' });
            $('#example').DataTable();
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
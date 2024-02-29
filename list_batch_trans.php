<?php
session_start();

//kode menu
$_SESSION['menu'] = "reset";

if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
if($object->group_id != 3 && $object->group_id !=4 && $object->group_id !=6){
    header("Location:index.php");
}
include './inc/constant.php';
$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
$mysqli = new mysqli(DB_SERVER,DB_USER,DB_PASS);
if(!$mysqli){die("MySQL Error:".$mysqli->connect_error);}
$db_con = $mysqli->select_db(DB_NAME);
if(!$db_con){
    exit("Database ".DB_NAME."Cannot be found");
}
mysqli_select_db($conn,DB_NAME);
$date = date("d/m/Y");
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>Reset - Batch Transaction</title>
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
            $result = mysqli_query($conn,$query_war);
            if(!$result) die(mysql_error());
            $status_list = true;
            if($status_list == true){
                    while($row = mysqli_fetch_array($result)){
                        $chartno = $row['chart_no'];
                        if($chartno == '1' ){
        ?> <div align="right" style="color:#000099"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php } elseif($chartno == '2' ) { 
            ?> <div align="right" style="color:#FF0000"><h4><?php echo $row['name_bom']; ?></h4></div>
                <?php }elseif($chartno == '3' ) { 
                    ?> <div align="right" style="color:#077"><h4><?php echo $row['name_bom']; ?></h4></div>
                        <?php }elseif($chartno == '4' ) {
                            ?><div align="right" style="color:#0f1"><h4><?php echo $row['name_bom']; ?></h4></div>
                                <?php }elseif($chartno == '5' ) { 
                                    ?> <div align="right" style="color:#f90"><h4><?php echo $row['name_bom']; ?></h4> </div>
                                        <?php }elseif($chartno == '6' ) {
                                            ?> <div align="right" style="color:#f9f"><h4><?php echo $row['name_bom']; ?></h4></div>
                                                <?php }elseif($chartno == '7' ) {
                                                    ?> <div align="right" style="color:#f4f"> <h4><?php echo $row['name_bom']; ?></h4></div>
                                                        <?php }else{
                                                            ?> <div align="right" style="color:#0a9"><h4><?php echo $row['name_bom']; ?></h4></div>
                                                                <?php } } }
                                                                ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 colsm-offset-3">
                    <h1 class="page-header">Reset - Batch Transaction</h1>
                    <?php
                    if(isset($_POST['submit']) && !empty($_POST['submit'])){
                            extract($_POST);
                            if(isset($mark)){
                                $mysqli->autocommit(FALSE);
                                for($i=0;$i<count($mark);$i++){
                                    $id=$mark[$i];
        //                            $sql = "UPDATE `batch_transaction` SET `flag_code` = 'S' WHERE `request_no`='$id'";
                                    //if flag_code = T -> M, IF flag_code = P -> S
                                    $sql = "UPDATE `batch_transaction` SET `flag_code`=CASE UPPER(`flag_code`) WHEN 'P' THEN 'S' WHEN 'T' THEN 'M' ELSE `flag_code` END WHERE `docket_no`='".$id."'";
//                                    $hasil = mysql_query($sql);
                                    $hasil = $mysqli->query($sql);
                                    if(!$hasil){
                                        $mysqli->rollback();
                                        ?>
                                        <div class="alert alert-danger">
                                            <strong>Gagal!!</strong> Data gagal di-reset, Error : <?php echo mysqli_error();exit();?>
                                        </div>  
                                        <?php
                                        exit();
                                    }
                                }
                                
                                //SUCCESS
                                $mysqli->commit();
                                ?>
                                <div class="alert alert-success">
                                  <strong>Berhasil!</strong> Data telah berhasil di-reset.
                                </div>
                        <?php 
                            }
                        }
                    $status_list = false;
                    if(isset($_GET['filter']) && !empty($_GET['filter'])){
                        if(isset($_GET['time']) && !empty($_GET['time'])){
                            extract($_GET);
                            $q = "SELECT A.* FROM `batch_transaction` A "
                                    . "INNER JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                                    . "WHERE `delv_date` = '".$filter."' AND `delv_time` BETWEEN '".rawurldecode($time)."' AND '23:59' "
                                    . "AND (UPPER(A.`flag_code`) !='S' AND UPPER(A.`flag_code`)!='M')"
                                    . "ORDER BY `seal_no` ASC";
                        }
                        else{
                            extract($_GET);
                            $q = "SELECT A.* FROM `batch_transaction` A "
                                    . "INNER JOIN `batch_transaction2` B ON A.`docket_no`=B.`docket_no` "
                                    . "WHERE `delv_date` = '".$filter."' AND (UPPER(A.`flag_code`) !='S' AND UPPER(A.`flag_code`)!='M')"
                                    . "ORDER BY `seal_no` ASC";
                        }
                        $hasil = $mysqli->query($q);
                        if(!$hasil){
                            exit(mysqli_error($mysqli));
                        }
                        $status_list = true;                        
                    }
?>
                    
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-lg-2 pull-left">
                                <form role="form" method="GET" class="form-inline">
                                    <div class="form-group" style="padding-bottom: 5px;margin-bottom: 0px;">
                                        <div class="input-group  col-md-12" >
                                           <input class="form-control" id="filter" name="filter" size="16" type="text" value="<?php echo date('Ymd'); ?>" placeholder="Pilih Tanggal" readonly="" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input2" value="" /><br/>
                                    </div>
                                    <div class="form-group" style="padding-bottom: 5px;margin-bottom: 0px;">
                                        <div class="input-group date form_time col-md-12" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                                            <input class="form-control" size="16" type="text" name="time" value="" readonly="" placeholder="Pilih Jam" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input3" value="" /><br/>
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
                                        <div class="table-responsive">
                                            <form method="post" action="" onSubmit="return validate();">
                                                <table class="table table-bordered table-hover small">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="selectall"></th>
                                                            <th>No</th>
                                                            <th>Seal No</th>
                                                            <th>Docket No</th>
                                                            <th>Prod Code</th>
                                                            <th>Delv Vol</th>
                                                            <th>So No</th>
                                                            <th>Delv time</th>
                                                            <th>Unit No</th>
                                                            <th>Driver Name</th>
                                                            <th>Cust Name</th>
                                                            <th>Proj Name</th>
                                                            <th>Flag</th>
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
                                                            <tr>
                                                                <td><input type="checkbox" class="checkbox1" name="mark[]" value="<?php echo $row['docket_no'];?>" /></td>
                                                                <td><?php echo $i++;?></td>
                                                                <td><?php echo $row['seal_no'];?></td>
                                                                <td><?php echo $row['docket_no'];?></td>
                                                                <td><?php echo $row['product_code'];?></td>
                                                                <td class="text-center"><?php echo $row['delv_vol'];?></td>
                                                                <td><?php echo $row['so_no'];?></td>
                                                                <td><?php echo $row['delv_time'];?></td>
                                                                <td><?php echo $row['unit_no'];?></td>
                                                                <td><?php echo $row['driver_name'];?></td>
                                                                <td><?php echo $row['cust_name'];?></td>
                                                                <td><?php echo $row['proj_name'];?></td>
                                                                <td><?php echo $row['flag_code'];?></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                    ?>
                                                            <tr>
                                                                <td colspan="5" class="text-right">Total Delv Vol :</td>
                                                                <td colspan="8"><?php echo $delv_vol_sum;?></td>
                                                            </tr>
                                                    <?php
                                                  }
                                                          ?>

                                                        </tbody>
                                                    </table>
                                                <?php
                                                if(isset($object) && ($object->group_id == 3 || $object->group_id == 4 || $object->group_id == 6)){ // plant admin
                                                ?>
                                                <button class="btn btn-xs btn-danger" type="submit" name="submit" value="aa">Reset</button>
                                                <?php
                                                }
                                                ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
        <script type="text/javascript">
	    	$(document).ready(function() {
	    		$("div.alert").fadeOut(5000);
	    	});
    	</script>
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
    </script>
    <script>
	$(function() {
		$( "#filter" ).datepicker({ dateFormat: 'yymmdd' });
                
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
<?php
mysqli_close($conn);

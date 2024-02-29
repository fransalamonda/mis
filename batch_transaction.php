<?php
session_start();

//kode menu
$_SESSION['menu'] = "download";

if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
include './inc/constant.php';
include 'db.php';
?>


<!--
privileges : batcher (no need to login)
-->
<html>
<head>
    <title>Download Batch transaction</title>
    <style>
        body { padding: 0px;font-family: Tahoma, Arial, Helvetica, sans-serif; }
        #status{margin-top: 30px;} 
        form#query{
            border-bottom: 1px solid #ddd;
        }
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
<!--    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/> -->
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="css/style.css" />
	
</head>
<body>
    <?php
    include "inc/menu.php";
    ?>
    <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT `chart_no`
                            FROM `mix_package_composition`
                            WHERE `code_trans` = 'Y'";   
            $result = mysql_query($query_war);
            if(!$result) die(mysql_error());
            $status_list = true;
            if($status_list == true){
                    while($row = mysql_fetch_array($result)){
                        $chartno = $row['chart_no'];
                        if($chartno == '1' ){
        ?>
            <div align="right" style="color:#000099" ><h4>1 / (Norm)</h4></div>
        <?php
                        } else {                
        ?>
            <div align="right" style="color:#FF0000"><h4>2 / (NFA)</h4></div>
        <?php
                          }
                    }
            }
    ?>
    <div class="loading">Loading&#8230;</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="page-header">
                    <h2>Download Batch Transaction Data</h2>
                </div>
                <form action="" method="post" enctype="multipart/form-data" id="retrieve_bt" class="form-horizontal" role="form">
                    <div id="messages" class="alert alert-danger">asasd</div>
                    <fieldset>
                        <div class="component input">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="textinput">Delivery Date :</label>  
                                <div class="col-md-4">
                                    <input id="delvdate" value="" name="delvdate" type="text" placeholder="Delivery Date" class="form-control input-md">
                                </div>
                            </div>
                        </div>
                        <div class="component input">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="textinput"></label>  
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Retrieve&nbsp;<i class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="component text-center download">
                            <div class="form-group">
                                <a class="btn btn-sm btn-success docket_n">Docket Normal&nbsp;<i class="fa fa-download"></i></a>
                                <a class="btn btn-sm btn-danger mat_is_n">Mat Is Normal&nbsp;<i class="fa fa-download"></i></a>&nbsp;|&nbsp;
                                <a class="btn btn-sm btn-default docket_m">Docket Manual&nbsp;<i class="fa fa-download"></i></a>
                                <a class="btn btn-sm btn-info mat_is_m">Mat Is Manual&nbsp;<i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
        <?php
            $code = date("Ymdhms");
        ?>
<!--        <form action="tocsv_u_b_t.php" target="download" method="POST" id="export">
	    	<input type="hidden" name="table" id="table" value="docket"/>
                    <input type="hidden" name="code" class="code" id="code" value="<?php echo $code;?>"/>
	    	<button type="submit" class="btn btn-primary" id="btn-docket">Download Plant Docket</button>
	    </form>
        <form action="tocsv_u_b_t.php" target="download" method="POST" id="export2" style="display: none;">
	    	<input type="hidden" name="table" id="table" value="material"/>
			<input type="hidden" name="code" class="code" id="code" value="<?php echo $code;?>"/>
	    	<button type="submit" class="btn btn-info" id="btn-material">Download Material Issue </button>
	    </form>
        <iframe style="display:none" src="" name="download">
        	
        </iframe>-->
    <?php
        include "inc/version.php";
    ?>
	<script src="js/jquery-1.10.2.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
	<script src="js/jquery.form.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/userdefined.js"></script>
	<script type="text/javascript">
	$(function() {
            $( "#delvdate" ).datepicker({ dateFormat: 'yymmdd' });
	});
	</script>
</body>
</html>


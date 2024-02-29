<?php
session_start();
//kode menu
$_SESSION['menu'] = "upload_delivery_schedule";
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
$Lg = $object->id_user;
if($object->group_id != 2 && $object->group_id !=3 && $object->group_id !=4 && $object->group_id !=5 && $object->group_id !=6){
    header("Location:index.php");
}
include_once './inc/constant.php';
include 'db.php';
?>
<!--
privileges : batcher (no need to login)
-->
<!doctype html>
<head>
    <title><?php echo $Lg; ?> Delivery Schedule Upload Data</title>
    <style>        
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 400px;}*/
        .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #export{display:none;}
        form{margin:20px auto;width:50%;}
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
<!--    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>-->
    <link rel="stylesheet" href="css/style.css" />	
</head>
<body>
    <?php
    include "inc/menu.php";
    ?>
    <?php
    $status_list = false;
    $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
    $result = mysqli_query($conns,$query_war);
    if(!$result) die(mysqli_error());
    $status_list = true;
    if($status_list == true){
        while($row = mysqli_fetch_array($result)){
            $chartno = $row['chart_no'];
            if($chartno == '1' ){ ?> <div align="right" style="color:#000099"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php } 
            elseif($chartno == '2' ) { ?> <div align="right" style="color:#FF0000"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
            elseif($chartno == '3' ) { ?> <div align="right" style="color:#077"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
            elseif($chartno == '4' ) { ?> <div align="right" style="color:#0f1"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
            elseif($chartno == '5' ) { ?> <div align="right" style="color:#f90"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
            elseif($chartno == '6' ) { ?> <div align="right" style="color:#f9f"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
            elseif($chartno == '7' ) { ?> <div align="right" style="color:#f4f"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
            else{ ?> <div align="right" style="color:#0a9"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
        }
    }
    ?>
    <div class="container-fluid">
        <div id="loading" style=""><img style="" src="images/loading_1.gif" /></div>
        <div id="bg-loading"></div>
        <div class="row" >
            <div class="col-lg-12">
                <form action="delivery_schedule_act.php" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" id="upload">
                        <div class="page-header">
                            <h1> Delivery Schedule Upload </h1>
                            <div class="alert alert-warning" role="alert">
                                    <strong>Perhatian!</strong> Format tanggal <i>'delivery date' </i>adalah <strong>dd/mm/yyyy </strong>  (01/12/2001).
                                </div>
                            <div id="messages" class="alert alert-dismissable">asasd</div>
                        </div>
                        <fieldset>
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Input file</label>  
                                        <div class="col-md-4">
                                            <input type="file" name="myfile" style="margin-top: 6px;" />
                                        </div>
                                    </div>
                                </div>
                            <div class="component">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput"></label>  
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">Upload&nbsp;<i class="fa fa-upload"></i></button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                </form>
            </div>
        </div>
    </div>
    <?php
    include "inc/version.php";
    ?>
    <script src="js/jquery-1.10.2.js"></script><script src="js/bootstrap.min.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.file-input.js.pagespeed.jm.IdmWcpRIii.js"></script>
    <script>
	$(function() {
		$( "#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
	});
	</script>
    <script>
        (function() {
            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
            $('form').ajaxForm({
                beforeSend: function() {
                    $('div#export').hide();
                    $('div#message').hide();
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                    $('div#loading').show();
                    $('div#bg-loading').show();
                },
                success: function() {
                    var percentVal = '100%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                complete: function(xhr) {
                    var obj = jQuery.parseJSON(xhr.responseText);
//                    console.log(obj.row);
                    if(obj.status == 1){
                    	//console.log("hanya");
                    	$('div#messages').hide();
						$('div#messages').removeClass("alert-danger").removeClass("alert-info").addClass("alert-success");
						$('div#messages').fadeIn();
						$('div#messages').html("<p>"+obj.msg+"!</p>");
						$('div#loading').hide();
                    	$('div#bg-loading').hide();
					}
                    if(obj.status == 2){
                    	//console.log("hanya");
                    	$('div#messages').hide();
						$('div#messages').removeClass("alert-danger").removeClass("alert-success").addClass("alert-info");
						$('div#messages').fadeIn();
						$('div#messages').html("<p>"+obj.msg+"!</p>");
						$('div#loading').hide();
                    	$('div#bg-loading').hide();
					}                                        
					else if(obj.status == 0){
						$('div#messages').hide();
						$('div#messages').removeClass("alert-success").removeClass("alert-info").addClass("alert-danger");
						$('div#messages').fadeIn();
						$('div#messages').html("<p>"+obj.msg+"!</p>");
						$('div#loading').hide();
                    	$('div#bg-loading').hide();
					}
                }
            });
        })();
    </script>
</body>
</html>


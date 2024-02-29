<?php
session_start();
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
if($object->group_id != 3 && $object->group_id !=4){
    header("Location:index.php");
}
include "./inc/constant.php";
$conn= mysql_connect(DB_SERVER,DB_USER,DB_PASS);
mysql_select_db(DB_NAME);
$query_select = "SELECT * FROM `machine`";
$result_select = mysql_query($query_select);
if(!$result_select){
    die("Error : ".  mysql_error());
}
?>
<!--
privileges : administrator & production admin
-->
<!doctype html>
<head>
    <title>Split Batch Transaction</title>
    <style type="text/css">
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 500px;}*/
/*        .progress { position:relative; width:500px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}*/
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #export,#export2{
            display:none;
        }
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
    <link rel="stylesheet" href="css/style.css" />
	
</head>
<body>
    <?php
    include "inc/menu.php";
    ?>
    <div id="loading" style="">
        <img style="" src="images/loading_1.gif" />
    </div>
    <div id="bg-loading"></div>

<div class="container-fluid">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
    <div class="row" >
        <form action="upload_batch_transaction_ac.php" method="post" enctype="multipart/form-data" id="upload" class="form-horizontal" role="form">
            <div class="page-header">
                <h1>Split Batch Transaction</h1>
            </div>
            <div id="messages" class="alert alert-danger">asasd</div>
                <fieldset id="upload_file">
                        <div class="component">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="textinput">Plant ID</label>  
                                <div class="col-md-4">
                                    <select name="mach_code" class="form-control">
                                        <option value=""> - pilih - </option>
                                        <?php
                                        while($data = mysql_fetch_array($result_select)):
                                                ?>
                                        <option title="<?php echo $data['desc'];?>" value="<?php echo $data['mach_code'];?>"><?php echo $data['mach_code'];?></option>
                                                <?php
                                        endwhile;
                                        ?>
                                    </select>
                                        <!--<input id="textinput" readonly="" value="<?php echo $machine;?>" name="mach_code" type="text" placeholder="Machine Code" class="form-control input-md">-->
                                </div>
                            </div>
                        </div>

                        <div class="component">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="textinput">File</label>  
                                <div class="col-md-4">
                                    <input type="file" name="myfile" style="margin-top: 5px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="component">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="textinput"></label>  
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Split&nbsp;<i class="fa fa-spin"></i></button>
                                </div>
                            </div>
                        </div>
                        <!--<div class="progress-bar progress-bar-info progress" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="">
                                <span class="sr-only percent">20% Complete</span>
                                <!--<div class="bar"></div >
                                <div class="percent">0%</div >     
                                <div id="status"></div>
                        </div>--> 
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style=""><span class="sr-only">60% Complete</span></div>
                        </div>

                </fieldset>
                <a href="" class="btn btn-success" id="btn-refresh" style="display:none;">Input New Transaction File</a>
        </form>
                            <?php
                            $code = date("Ymdhms");
                            ?>
        <form action="tocsv_u_b_t.php" target="download" method="POST" id="export">
            <input type="hidden" name="table" id="table" value="docket_BTU"/>
            <input type="hidden" name="code" class="code" id="code" value="<?php echo $code;?>"/>
            <button type="submit" class="btn btn-primary" id="btn-docket">Download Plant Docket</button>
        </form>
        <form action="tocsv_u_b_t.php" target="download" method="POST" id="export2">
            <input type="hidden" name="table" id="table" value="material_BTU"/>
            <input type="hidden" name="code" class="code" id="code" value="<?php echo $code;?>"/>
            <button type="submit" class="btn btn-info" id="btn-material">Download Material Issue </button>
        </form>
        <iframe style="display:none" src="" name="download">

        </iframe>
    </div>
    </div>
    <div class="col-sm-3"></div>
</div>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.file-input.js.pagespeed.jm.IdmWcpRIii.js"></script>
    <script src="js/bootstrap.min.js"></script>
   <!-- <script src="http://google-code-prettify.googlecode.com/svn/trunk/src/prettify.js"></script>-->
    
    <script>
        (function() {

            var bar = $('.progress-bar');
            var percent = $('.percent');
            var status = $('#status');

            $('form#upload').ajaxForm({
                beforeSend: function() {
                	$('.progress-bar').hide();
                    status.empty();
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                	$('.progress-bar').show();
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
                	$('div#loading').hide();
                    $('div#bg-loading').hide();
                    var obj = jQuery.parseJSON(xhr.responseText);
//                    console.log(obj.row);
                    if(obj.status === 1){
                    	//console.log("hanya");
                    	$('div#messages').hide();
                        $('div#messages').removeClass("alert-danger").addClass("alert-success");
                        $('div#messages').show();
                        $('#export').show();
                        $('#upload_file').hide();
                        $('div#messages').html("<p>"+obj.msg+"!</p>");
                        $("form#export #code").attr({value:obj.tgl_docket})
                        $("form#export2 #code").attr({value:obj.tgl_docket})
                    }
                    else if(obj.status === 0){
                        $('div#messages').hide();
                        $('div#messages').removeClass("alert-success").addClass("alert-danger");
                        $('div#messages').show();
                        $('div#messages').html("<p>"+obj.msg+"!</p>");
                    }
                }
            });
        })();
    </script>
    <script type="text/javascript">
    	$(document).ready(function() {
    		$("#btn-docket").click(function (e) {
    			$('#export2').show();
    			$('#export').hide();
    		});
    		$("#btn-material").click(function (e) {
    			$('#export2').hide();
    			$('#export').hide();
				$('#btn-refresh').show();
    		});
    			
    	});
    </script>
</body>
</html>
<?php
    mysql_close($conn);
?>

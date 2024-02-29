<!doctype html>
<head>
    <title>Convert Delivery Schedule</title>
    <style>
        body { padding: 30px;font-family: Tahoma, Arial, Helvetica, sans-serif; }
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 500px;}*/
/*        .progress { position:relative; width:500px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}*/
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #export{
			display:none;
		}
		form{
			margin:20px auto;width:50%;
		}
		.progress{
			visibility:visible;
		}
    </style>
    <link rel="stylesheet" href="css/normalize.css" >
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/bootstrap-theme.css" >
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" >
    <link rel="stylesheet" href="css/style.css" >
	
</head>
<body>

	<div id="loading" style="">
            <img style="" src="images/loading_1.gif" />
    </div>
    <div id="bg-loading"></div>

<!--<div class="alert alert-danger">
<strong>Oh snap!</strong> Change a few things up and try submitting again.
</div>-->
<div class="row" >

	     <form action="upload_bom_ac_2.php" method="post" enctype="multipart/form-data" id="upload" class="form-horizontal" role="form">
		        <div class="page-header">
        <h1>BOM Upload Form</h1>
      </div>
		        <div id="messages" class="alert alert-danger">asasd</div>
		        <fieldset>
		        	<div class="component">
		        		<div class="form-group">
						  <label class="col-md-4 control-label" for="textinput">Machine Code</label>  
						  <div class="col-md-4">
							  <input id="textinput" readonly="" value="<?php echo $machine;?>" name="mach_code" type="text" placeholder="Machine Code" class="form-control input-md">
						  </div>
						</div>
		        	</div>
		        	<div class="component">
		        		<div class="form-group">
						  <label class="col-md-4 control-label" for="textinput">Product Code</label>  
						  <div class="col-md-4">
							  <input id="textinput" name="product_code" type="text" placeholder="Product Code" class="form-control input-md">
						  </div>
						</div>
		        	</div>
		        	<div class="component">
		        		<div class="form-group">
						  <label class="col-md-4 control-label" for="textinput">Product Name</label>  
						  <div class="col-md-4">
							  <input id="textinput" name="product_name" type="text" placeholder="Product Name" class="form-control input-md">
						  </div>
						</div>
		        	</div>
		        	<div class="component">
		        		<div class="form-group">
						  <label class="col-md-4 control-label" for="textinput">Quality Group</label>  
						  <div class="col-md-4">
							  <input id="textinput" name="qlty_grp" type="text" placeholder="Quality Group" class="form-control input-md">
						  </div>
						</div>
		        	</div>
		        	<div class="component">
		        		<div class="form-group">
						  <label class="col-md-4 control-label" for="textinput">Material File</label>  
						  <div class="col-md-4">
							  <a class="file-input-wrapper btn btn-default "><span>Browse</span><input type="file" name="bmb" style="left: -206.5px; top: 7px;"></a>
						  </div>
						</div>
		        	</div>
		        	<div class="component">
		        		<div class="form-group">
						  <label class="col-md-4 control-label" for="textinput"></label>  
						  <div class="col-md-4">
							  <button type="submit" class="btn btn-lg btn-primary">Upload</button>
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
		        
		         
		    </form>
</div>

  
	<div style="margin: 10px auto;width:100px;"><a href="index.php">Back to Home</a></div>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.file-input.js.pagespeed.jm.IdmWcpRIii.js"></script>
   <!-- <script src="http://google-code-prettify.googlecode.com/svn/trunk/src/prettify.js"></script>-->
    <script>
	$(function() {
		$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#format" ).change(function() {
			$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		});
	});
	</script>
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
                    if(obj.status == 1){
                    	//console.log("hanya");
                    	$('div#messages').hide();
						$('div#messages').removeClass("alert-danger").addClass("alert-success");
						$('div#messages').show();
						$('div#messages').html("<p>"+obj.msg+"!</p>");
					}
					else if(obj.status == 0){
						$('div#messages').hide();
						$('div#messages').removeClass("alert-success").addClass("alert-danger");
						$('div#messages').show();
						$('div#messages').html("<p>"+obj.msg+"!</p>");
					}
                }
            });
        })();
    </script>
</body>
</html>


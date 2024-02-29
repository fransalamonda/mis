<!doctype html>
<head>
    <title>Batch Request</title>
    <style>
        body { padding: 30px;font-family: Tahoma, Arial, Helvetica, sans-serif; }
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 400px;}*/
        .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #batch-add{
			display:none;
		}
		#plant_id{
			margin:0px auto;
			width:400px;
		}
		form{
			margin:20px auto;width:50%;
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
    <div class="row" >
		<form action="detail_add_act.php" method="post" enctype="multipart/form-data" id="query" class="form-horizontal" role="form">
    		<div class="page-header">
	        	<h1>Add New Conversion Data</h1>
	      	</div>
	      	<div id="messages" class="alert alert-danger">asasd</div>
	      	<fieldset>
				<input  type="hidden" name="id" value=""/>
				<div class="component">
	        		<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput">Old :</label>  
					  <div class="col-md-4">
						  <input id="old" value="" name="old" type="text" placeholder="Old Code" class="form-control input-md">
					  </div>
					</div>
	        	</div>
	        	<div class="component">
	        		<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput">New :</label>  
					  <div class="col-md-4">
						  <input id="new" value="" name="new" type="text" placeholder="New Code" class="form-control input-md">
					  </div>
					</div>
	        	</div>
	        	<div class="component">
	        		<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput"></label>  
					  <div class="col-md-4">
						  <button type="submit" class="btn btn-lg btn-primary">Add</button>
					  </div>
					</div>
	        	</div>	      		
	      	</fieldset>
    	</form>
    </div>
	
     
	<div style="margin: 10px auto;width:100px;"><a href="index.php">Home</a> | <a href="list_konversi.php">List</a></div>
	<script src="js/jquery-1.10.2.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
	<script src="js/jquery.form.js"></script>
    <script>
        (function() {

            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
			var delv_date = $('#delv_date').val();
            $('form#query').ajaxForm({
                beforeSend: function() {
//                    $('div#detail').hide();
                    $('div#message').fadeOut();
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                success: function() {
                    var percentVal = '100%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                complete: function(xhr) {
                	console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
//                    console.log(obj.row);
                    if(obj.status == '1'){
						//console.log("ok");
						$('div#messages').html("<p>"+obj.msg+"</p>");
						$('div#messages').removeClass("alert-danger").addClass("alert-success");
//						$('div#detail').show();
						$('input#old').val(obj.old);
						$('input#new').val(obj.new);
						
						$('div#messages').fadeIn();
					}
					else if(obj.status == '0'){
						$('div#messages').hide();
						$('div#messages').removeClass("alert-success").addClass("alert-danger");
						$('div#messages').html("<p>"+obj.msg+"!</p>");
						$('div#messages').fadeIn();$('div#detail').hide();
					}
                }
            });
            
            
        })();
    </script>
    <script type="text/javascript">
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
    </script>
</body>
</html>


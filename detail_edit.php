<?php
	include "db.php";
	if(isset($_GET['id']) && !empty($_GET['id'])){
		extract($_GET);
		$status = 0;
		$q = "SELECT * FROM `conversion` WHERE `id` = '$id'";
		$result = mysql_query($q);
		if(!$result){
			die("Cannot select table data");exit();
		}
		$count = mysql_num_rows($result);
		if($count > 0){
			$status = 1;
		}
		else{
			header("Location:list_konversi.php");
		}
	}
	else{
		header("Location:list_konversi.php");
	}
		//echo $_GET['id'];
	
?>

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
    <?php
    if($status == 1){
		?>
		<form action="detail_edit_act.php" method="post" enctype="multipart/form-data" id="query" class="form-horizontal" role="form">
    		<div class="page-header">
	        	<h1>Edit Conversion Data</h1>
	      	</div>
	      	<div id="messages" class="alert alert-danger">asasd</div>
	      	<fieldset>
	      	<?php
	      	while($row = mysql_fetch_array($result)){
				?>
				<input  type="hidden" name="id" value="<?php echo $row['id'];?>"/>
				<div class="component">
	        		<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput">Old :</label>  
					  <div class="col-md-4">
						  <input id="old" value="<?php echo $row['old'];?>" name="old" type="text" placeholder="Old Code" class="form-control input-md">
					  </div>
					</div>
	        	</div>
	        	<div class="component">
	        		<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput">New :</label>  
					  <div class="col-md-4">
						  <input id="new" value="<?php echo $row['new'];?>" name="new" type="text" placeholder="New Code" class="form-control input-md">
					  </div>
					</div>
	        	</div>
	        	<div class="component">
	        		<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput"></label>  
					  <div class="col-md-4">
						  <button type="submit" class="btn btn-lg btn-primary">Edit</button>
					  </div>
					</div>
	        	</div>
				<?php
			}
	      	?>
	      		
	      	</fieldset>
    	</form>
		<?php
	}
    ?>
    	
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
						$('div#messages').removeClass("n_error").addClass("n_ok");
//						$('div#detail').show();
						$('input#old').val(obj.old);
						$('input#new').val(obj.new);
						
						$('div#messages').fadeIn();
					}
					else if(obj.status == '0'){
						$('div#messages').hide();
						$('div#messages').removeClass("n_ok").addClass("n_error");
						$('div#messages').html("<p>"+obj.msg+"!</p>");
						$('div#messages').fadeIn();$('div#detail').hide();
					}
                }
            });
            
            
            //ADD QTY BATCH
            $('form#detail').ajaxForm({
                beforeSend: function() {
                    $('div#message_batch').fadeOut();
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                    //$('form#export').hide();
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
                	//console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
//                    console.log(obj.row);
                    if(obj.status == '1'){
						//console.log("ok");
						$('div#message_batch').html("<p>"+obj.msg+"</p>");
						$('div#message_batch').removeClass("n_error").addClass("n_ok");
						$('div#message_batch').fadeIn();
						$('input#qty').val('');
						$('input#qty').val('');
						$('#delv_temp_volume').html(obj.temp_vol);
						$('div#detail').hide();
						$('#kueri').hide();
					}
					else if(obj.status == '0'){
						$('div#message_batch').hide();
						$('div#message_batch').removeClass("n_ok").addClass("n_error");
						$('div#message_batch').html("<p>"+obj.msg+"!</p>");
						$('div#message_batch').fadeIn();
						//$('div#detail').hide();
					}
                }
            });
        })();
    </script>
    <script type="text/javascript">
    	$(document).ready(function() {
    		$("#btn-download-detail").click(function (e) {
    			//alert("a");
	            var countError=0;
	            e.preventDefault();
	            var so_no   = $("#so_no").val();
	            var docket_no   = $("#docket_no").val();
	            if(so_no === '')
	            {
	                $("div#messages").hide().removeClass("n_ok").addClass("n_error");
	                $("div#loading").show();$("div#bg-loading").show();
	                $("div#messages").html("<p>Field Cannot Be Blank!</p>");
	                $("div#messages").fadeIn();
	                $("div#loading").hide();$("div#bg-loading").hide();
	                $("#so_no").focus();
	                countError++;
	                return false;
	            }
	            

	            $("div#loading").show(); //show loading image
	            $("div#bg-loading").show(); //show loading image

	            var myData = "&opass="+opass+"&npass="+npass+"&cnpass="+cnpass; //build a post data structure
	            jQuery.ajax({
	                type: "POST", // HTTP method POST or GET
	                url: "<?php echo site_url()?>member/cpass_act", //Where to make Ajax calls
	                dataType:"text", // Data type, HTML, json etc.
	                data:myData, //Form variables
	                success:function(response){
	        //                            alert(response);
	                    if(response == '1'){
	                        $("div#messages").removeClass("n_error").addClass("n_ok");
	                        $("div#messages").html("<p>Password berhasil diganti!</p>");
	                        $("div#messages").fadeIn();
	                        $("div#loading").hide(); //hide loading image
	                        $("div#bg-loading").hide();
	                    }
	                    else if(response=='0'){
	                        $("div#messages").removeClass("n_ok").addClass("n_error");
	                        $("div#messages").html("<p>Password Lama salah!</p>");
	                        $("div#messages").fadeIn();
	                        $("div#loading").hide(); 
	                        $("div#bg-loading").hide();
	                    }
	                    else{
	                        $("div#messages").removeClass("n_ok").addClass("n_error");
	                        $("div#messages").html("<p>Internal Error!</p>");
	                        $("div#messages").fadeIn();
	                        $("div#loading").hide(); 
	                        $("div#bg-loading").hide();
	                    }
	                },
	                error:function (xhr, ajaxOptions, thrownError){
	                    $("div#loading").hide(); //hide loading image
	                    $("div#bg-loading").hide();
	                    alert(thrownError);
	                }
	            });
	        });
    		$( "#batch-main" ).click(function() {
			  $( "#export" ).submit();
			  $("input#table").val("bt");
			  $( "#batch-main" ).hide();
			  $( "#batch-detail" ).fadeIn();
			});
			$( "#batch-detail" ).click(function() {
				  $("input#table").val("btd");
				  $( "#export" ).submit();
				  $( "#batch-main" ).hide();$( "#delvdate" ).val('');$( "#machcode" ).val('');
				  $( "#export" ).fadeOut();
				  $('#kueri').show();
				  $("div#messages").hide();
			});
    	});
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


<?php
session_start();
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
?>
<!doctype html>
<head>
    <title>Tambah Data Plant</title>
    <style>
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
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
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
    <div >
    	<form action="act/list_bp_add_act.php" method="post" id="query" class="form-horizontal" role="form">
            <div class="page-header">
                <h1>Tambah Data Batching Plant</h1>
            </div>
            <div id="messages" class="alert alert-danger">asasd</div>
            <fieldset>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Machine Code : </label>  
                        <div class="col-md-4">
                            <input id="mach_code" value="" name="mach_code" type="text" placeholder="Machine Code" class="form-control input-md">
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Keterangan :</label>  
                            <div class="col-md-4">
                                <input id="desc" value="" name="desc" type="text" placeholder="Keterangan" class="form-control input-md">
                            </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput"></label>  
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-lg btn-primary">Submit&nbsp;!</button>
                        </div>
                    </div>
                </div>
            </fieldset>
    	</form>
    </div>
    
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
    <script>
        (function() {

            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
            var delv_date = $('#delv_date').val();
            var loading = $("div#loading");
            $('form#query').ajaxForm({
                beforeSubmit: function() {
                    cek_form();
                },
                beforeSend:function(){
                    $('div#loading').show();
                    $('div#bg-loading').show();
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);$('div#loading').show();
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
                    console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if(obj.status === 1){
                        $('div#messages').hide();
                        $('div#messages').html("<p>"+obj.msg+"</p>");
                        $('div#messages').removeClass("n_error").addClass("n_ok");
                        $('form#query')[0].reset();
                        $('div#messages').fadeIn();
                    }
                    else if(obj.status === 0){
                        $('div#messages').hide();
                        $('div#messages').removeClass("n_ok").addClass("n_error");
                        $('div#messages').html("<p>"+obj.msg+"!</p>");
                        $('div#messages').fadeIn();
                    }
                }
            });
            function goToMessage(){
                $("body, html").animate({ 
                    scrollTop: $("#detail").offset().top 
                }, 600);
            }
        })();
        function cek_form(){
            var mach = $("#mach_code");
            var desc = $("#desc");
            
            if(mach.val() === ''){
                $('div#messages').hide();
                $('div#messages').removeClass("n_ok").addClass("n_error");
                $('div#messages').html("<p>Kolom <b>Machine Code</b> harus diisi!</p>");
                $('div#messages').fadeIn();
                mach.focus();
                return FALSE;
            }
            if(desc.val() === ''){
                $('div#messages').hide();
                $('div#messages').removeClass("n_ok").addClass("n_error");
                $('div#messages').html("<p>Kolom <b>Keterangan</b> harus diisi!</p>");
                $('div#messages').fadeIn();
                desc.focus();
                return FALSE;
            }
        }
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


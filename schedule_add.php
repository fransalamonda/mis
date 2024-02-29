<?php
session_start();
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    header("Location:index.php");
}

$object = (object)$_SESSION['login'];
if($object->group_id != 4){ // administrator only
    header("Location:index.php");
}
include './inc/constant.php';
?>
<!doctype html>
<head>
    <title>Schedule Add</title>
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
    <div class="container">
        <form class="form-horizontal" action="act/schedule_add_act.php" id="query" role="form" method="POST">
            <div class="page-header">
                <h1>Tambah Data Schedule</h1>
            </div>
            <div id="messages" class="alert alert-danger">asasd</div>
            <div class="form-group">
                <label for="inputCity" class="col-lg-1 control-label">Jam</label>
                <div class="col-xs-2">
                    <select class="form-control" name="jam">
                        <?php
                        for($i=1;$i<=12;$i++){
                            ?>
                      <option value="<?php echo $i;?>"><?php if(strlen($i) <2){echo "0".$i;}else{echo $i;}?></option>
                                <?php
                        }
                        ?>
                    </select>
                </div>
                <label for="inputType" class="col-lg-1 control-label">Menit</label>
                <div class="col-lg-2">
                    <select class="form-control" name="menit">
                        <?php
                        for($i=0;$i<=59;$i+=5){
                            ?>
                      <option value="<?php if(strlen($i) <2){echo "0".$i;}else{echo $i;}?>">
                          <?php echo ":";if(strlen($i) <2){echo "0".$i;}else{echo $i;}?>
                      </option>
                                <?php
                        }
                        ?>
                    </select>
                </div>
                <label for="inputType" class="col-lg-1 control-label">am/pm</label>
                <div class="col-lg-2">
                    <select class="form-control" name="ap">
                        <option value="am">am</option>
                        <option value="pm">pm</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Submit !</button>
            </div>
        </form>
    </div>
    
     <?php
    include "inc/version.php";
    ?>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
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
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if(obj.status === 1){
                        $('div#messages').hide();
                        $('div#messages').html("<p>"+obj.msg+"</p>");
                        $('div#messages').removeClass("n_error").addClass("n_ok");
                        $('form#query')[0].reset();
                        $('div#messages').fadeIn();
                        setTimeout(function(){$('div#messages').fadeOut();}, 3000);
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
            var id = $("#iduser");
            var name = $("#name");
            var group = $("#group");
            
            if(id.val() === ''){
                $('div#messages').hide();
                $('div#messages').removeClass("n_ok").addClass("n_error");
                $('div#messages').html("<p>Kolom <b>ID User</b> harus diisi!</p>");
                $('div#messages').fadeIn();
                id.focus();
                return FALSE;
            }
            if (/\s/.test(id.val())) {
                $('div#messages').hide();
                $('div#messages').removeClass("n_ok").addClass("n_error");
                $('div#messages').html("<p>User tidak boleh menggunakan spasi!</p>");
                $('div#messages').fadeIn();
                id.focus();
                return FALSE;
            }
            if(name.val() === ''){
                $('div#messages').hide();
                $('div#messages').removeClass("n_ok").addClass("n_error");
                $('div#messages').html("<p>Kolom <b>Name</b> harus diisi!</p>");
                $('div#messages').fadeIn();
                name.focus();
                return FALSE;
            }
            if(group.val() === ''){
                $('div#messages').hide();
                $('div#messages').removeClass("n_ok").addClass("n_error");
                $('div#messages').html("<p>Kolom <b>Group</b> harus diisi!</p>");
                $('div#messages').fadeIn();
                group.focus();
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


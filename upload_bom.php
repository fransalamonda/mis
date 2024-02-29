<?php
/*
 * 20150416
 * Upload BOM dihapus, digantikan oleh upload_bom_chart 
 * ilham
 */
die("Page is not available");
session_start();
//kode menu
$_SESSION['menu'] = "upload_bom";
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
if($object->group_id != 1 && $object->group_id !=4){
    header("Location:index.php");
}
?>
<!--
privileges : administrator & techincal
-->
<!doctype html>
<head>
    <title>Upload BOM File (bma_file)</title>
    <style>
        .bar{background-color:#B4F5B4;width:0%;height:2px;border-radius:3px;}
        .percent{position:absolute;display:inline-block;top:3px;left:48%;}
        #status{margin-top:30px;}
        #export{display:none;}
	form{margin:20px auto;width:40%;}
	.progress{visibility:visible;}
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="css/style.css" />
	
</head>
<body>
    <?php
    include "inc/menu.php";
    ?>
    <div class="loading">Loading&#8230;</div>

    <div class="container">
        <div class="row" >
            <div class="col-md-12">
                <form action="act/upload_bom_ac.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form" id="upload">
                    <div class="page-header">
                        <h1>Upload BOM Data</h1>
                    </div>
                    <div id="messages" class="alert alert-dismissable">asasd</div>
                    <fieldset>
                        <div class="component">
                            <div class="form-group">
                                <label class="col-md-6 control-label" for="textinput">Mix Design (bma_file) </label>  
                                <div class="col-md-4">
                                    <input type="file" name="myfile" style="margin-top: 5px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="component">
                            <div class="form-group">
                                <label class="col-md-6 control-label" for="textinput">Mix Design Composition (bmb_file) </label>  
                                <div class="col-md-4">
                                    <input type="file" name="myfile2" style="margin-top:5px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="component">
                            <div class="form-group"> 
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary" style="width: 100%;">Upload</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style=""><span class="sr-only">60% Complete</span></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    include "inc/version.php";
    ?>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.file-input.js.pagespeed.jm.IdmWcpRIii.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        (function() {
            var bar     = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
            var loading =   $(".loading");
            var msg     = $("div#messages");
            
            $('form#upload').ajaxForm({
                beforeSend: function() {
                    status.empty();
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);loading.show();msg.hide();
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
                    loading.hide();
                    var obj = jQuery.parseJSON(xhr.responseText);
//                    console.log(obj.row);
                    if(obj.status === 1){
                    	//console.log("hanya");
                    	msg.hide();
                        msg.removeClass("alert-danger").removeClass("alert-warning").addClass("alert-success");
                        msg.html("<p>"+obj.msg+" !</p>");msg.fadeIn();
                    }
                    else if(obj.status === 0){
                        msg.hide();
                        msg.removeClass("alert-warning").removeClass("alert-success").addClass("alert-danger");
                        msg.html("<p>"+obj.msg+" !</p>");msg.fadeIn();
                    }
                    else if(obj.status === 2){
                        msg.hide();
                        msg.removeClass("alert-danger").removeClass("alert-success").addClass("alert-warning");
                        msg.html("<p>"+obj.msg+" !</p>");msg.fadeIn();
                    }
                }
            });
        })();
    </script>
</body>
</html>


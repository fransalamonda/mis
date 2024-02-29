<?php
session_start();
//kode menu
$_SESSION['menu'] = "list_bp";
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
    header("Location:index.php");
}

$object = (object)$_SESSION['login'];
if($object->group_id != 4){ // administrator only
    header("Location:index.php");
}
include "db.php";
$date = date("d/m/Y");
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>List Batching Plant</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
        <style type="text/css">
            th,td{
                font-size: 12px;
            }
        </style>
        <link rel="stylesheet" href="css/style.css" />
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("div.alert").fadeOut(3000);
            });
    	</script>
	</head>
	<body>
            <div id="loading" style="">
            <img style="" src="images/loading_1.gif" />
        </div>
            <div id="bg-loading"></div>
        <?php
        include"inc/menu.php";
        ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 colsm-offset-3">
                        <h1 class="page-header">List Batching Plant</h1>
					
                        <?php
                    $status_list = false;
                    if(isset($_GET['filter']) && !empty($_GET['filter'])){
                        extract($_GET);
                        $q = "SELECT * FROM `machine` WHERE `mach_code` LIKE '$filter%'";
                        
                        $result = mysqli_query($conns,$q);
                        if(!$result) die(mysqli_error());
                        $status_list = true;
                    }
                    else{
                        $q = "SELECT * FROM `machine`";
                        
                        $result = mysqli_query($conns,$q);
                        if(!$result) die(mysqli_error());
                        $status_list = true;
                    }
					?>
                    
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-lg-2 pull-left">
                                <div id="messages" class="alert alert-danger">asasd</div>
                                <form role="form" method="GET">
                                  <div class="form-group input-group">
                                    <input type="text" class="form-control" id="filter" name="filter" placeholder="Machine Code"/>
                                        <span class="input-group-btn">
                                          <button class="btn btn-default" style="padding-top: 9px;padding-bottom: 9px;" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                  </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                if($status_list == true){
                                    ?>
                                <h4><small>Data List</small></h4>   
                                    <?php
                                }
                                ?>
                                
                                <form method="post" action="" onSubmit="return validate();">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width:25px;">No</th>
                                                <th>Machine Code</th>
                                                <th>Keterangan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
            			              <?php
                                if($status_list == true){
                                            $i=1;
                                    while($row = mysqli_fetch_array($result)){
                                        $link = "";
                                        ?>
                                            <tr>
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $row['mach_code'];?></td>
                                                <td><?php echo $row['desc'];?></td>
                                                <td>
                                                    <a href="#" class="delete_bp" data-id="<?php echo $row['mach_code'];?>">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                }
                                ?>
            			                
                                        </tbody>
                                    </table>
                                </form>
                                <a class="btn btn-primary" href="list_bp_add.php">Tambah Data</a>
                                <br/>
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <?php
    include "inc/version.php";
    ?>
            </div>
            <script src="js/jquery-1.10.2.js"></script>
            <script type="text/javascript">
                var base_url = "http://localhost/mis/";
                
                $(document).ready(function(){
                    var response1='response from server';
                    
                    
                    var msg = $("#messages");
                    var msg_content = "";
                    var cur_url         = window.location;
                    $(".delete_bp").click(function(e){
                        e.preventDefault();
                        var IS_JSON = true;
                        var r = confirm("Are you sure?")
                        if(r === true){
                            var id_bp = $(this).data("id");
                            var myData = "mach_code="+id_bp;
                            
                            $("div#loading").show(); //hide loading image
                            $("div#bg-loading").show();
                            jQuery.ajax({
                                type: "POST", // HTTP method POST or GET
                                url: window.base_url+"act/list_bp_delete.php", //Where to make Ajax calls
                                dataType:"text", // Data type, HTML, json etc.
                                data:myData, //Form variables
                                success:function(response){
                                    var obj = jQuery.parseJSON(response);
                                    if(obj.status === 1){
                                        msg.hide();
                                        msg.empty();
                                        msg_content   =   "<b>Success!</b> "+obj.msg;
                                        msg.append(msg_content);


                                        msg.removeClass("alert-warning alert-info alert-danger").addClass("alert-success");
                                        msg.fadeIn();
                                        $("div#loading").hide(); //hide loading image
                                        $("div#bg-loading").hide();
                                        setTimeout(function(){
                                            window.location = cur_url;
                                        }, 3000); 
                                        
                                    }
                                    else if(obj.status === 0){
//                                        console.log("ok");
                                        msg.hide();
                                        msg.empty();
                                        msg_content   =   "<b>Alert!</b> "+obj.msg;
                                        msg.append(msg_content);


                                        msg.removeClass("alert-warning alert-info alert-success").addClass("alert-danger");
                                        msg.fadeIn();
                                        $("div#loading").hide(); //hide loading image
                                        $("div#bg-loading").hide();
                                    }
                                    else{
                                        alert("a");
                                        console.log("sini");
                                    }
                                },
                                error:function (xhr, ajaxOptions, thrownError){
                                    $("div#loading").hide(); //hide loading image
                                    $("div#bg-loading").hide();
                                    alert(thrownError);
                                }
                            });
                        }
                    });
                });
            </script>
    </body>
</html>
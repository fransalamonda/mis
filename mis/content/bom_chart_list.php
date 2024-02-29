<?php
session_start();
include "./inc/constant.php";
//kode menu
$_SESSION['menu'] = "upload_bom";

if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
$date = date("d/m/Y");
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
            <title>BOM Datacenter</title>
            <link rel="stylesheet" href="css/normalize.css" />
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="css/bootstrap.min.css"/>
            <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
            <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
            <link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
            <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
            <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>	
            <style>
                #msg_popup{display: none;}
            </style>
        </head>
	<body>
        <?php
            include "inc/menu.php";
            
            $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
            if(mysqli_connect_errno()){ die("Failed to connect to MySQL: " . mysqli_connect_error());}
        ?>
            <div class="loading"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 colsm-offset-3">
                        <h1 class="page-header">
                            BOM Datacenter
                        </h1>
                        <div id="msg" class="alert"></div>
                    <?php
                    $status_list = false;
                    $q = "SELECT A.* FROM `mix_package` A "
                        . "WHERE `mch_code`='".PLANT_ID."' "
                        . "ORDER BY `product_code` ASC";
                    $result = mysqli_query($con,$q);
                    if(!$result) die(mysqli_error($con));
                    $status_list = true;
                    ?>
                    
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Data table</div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="display compact" id="example">
                                                    <thead style="font-size: small">
                                                        <tr>
                                                            <th>No&nbsp;</th>
                                                            <th>Machine&nbsp;</th>
                                                            <th>Product&nbsp;&nbsp;</th>
                                                            <th>Desc&nbsp;&nbsp;</th>
                                                            <th>Slump&nbsp;</th>
                                                            <th>Action&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: medium">
                                                          <?php
                                                    if($status_list == true){
                                                        $i=1;
                                                        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                            $link = "";
                                                            ?>
                                                        <tr class="small">
                                                            <td><?php echo $i++;?></td>
                                                            <td><?php echo $row['mch_code'];?></td>
                                                            <td><?php echo $row['product_code'];?></td>            			                  
                                                            <td><?php echo $row['description'];?></td>
                                                            <td><?php echo $row['slump_code'];?></td>
                                                            <td>
                                                                <a href="" data-mach="<?php echo $row['mch_code'];?>" data-product="<?php echo $row['product_code'];?>" class="view btn btn-xs btn-warning">View</a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>

                                                          </tbody>
                                                </table>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
    include "inc/version.php";
    ?>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="test" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detail BOM</h4>
                  </div>
                  <div class="modal-body">
                      <div id="msg_popup" class="alert"></div>
                      
                      <form class="form-horizontal">
                        <fieldset id="mix_wrapper">
                        </fieldset>
                      </form>
                      <table class="table table-striped table-hover table-bordered" id="popup_table">

                      </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                  </div>
                </div>
              </div>
            </div>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-datetimepicker.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/bootstrap-dialog.min.js"></script>
        <script>
            function show_alert_ms(obj_msg,type,msg_text){
                obj_msg.hide();
                obj_msg.addClass('alert');
                if(type ===3){
                    obj_msg.removeClass('alert-danger').removeClass('alert-info').removeClass('alert-warning');
                    obj_msg.addClass('alert-success');
                    obj_msg.empty().append("<strong>SUCCESS!</strong><br/>").append(msg_text);
                }
                else if(type === 2){
                    obj_msg.removeClass('alert-danger').removeClass('alert-info').removeClass('alert-success');
                    obj_msg.addClass('alert-warning');
                    obj_msg.empty().append("<strong>WARNING!</strong><br/>").append(msg_text);
                }
                else if(type === 1){
                    obj_msg.removeClass('alert-danger').removeClass('alert-success').removeClass('alert-warning');
                    obj_msg.addClass('alert-info');
                    obj_msg.empty().append("<strong>INFO!</strong><br/>").append(msg_text);
                }
                else{
                    obj_msg.removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning');
                    obj_msg.addClass('alert-danger');
                    obj_msg.empty().append("<strong>Error!</strong><br/>").append(msg_text);
                }
                obj_msg.fadeIn();
            }
            $(function() {
                var base_url    = location.origin+"/mis/";
                var loading_object = $(".loading");
                
                //tampilan datatable
                $('#example').DataTable();
                
                
                //event on click tombol view
                $("table#example").on('click', 'a.view', function(e){
                    e.preventDefault(e);
                    $("#msg").hide();$("#msg_popup").hide();
                    var mach            = $(this).data("mach");
                    var product_code    = $(this).data("product");
                    var data = "mach_code="+mach+"&product_code="+product_code;
                    loading_object.show();
                    jQuery.ajax({
                        type: "POST", // HTTP method POST or GET
                        url: base_url+"ajax/bom_chart_retrieve.php", //Where to make Ajax calls
                        dataType:"text", // Data type, HTML, json etc.
                        data:data, //Form variables
                        success:function(response){
                            var obj = jQuery.parseJSON(response);
                            if(obj.status === 1){
                                $('#test').modal('show');
                                $("#popup_table").html(obj.msg);
                                $("#mix_wrapper").html(obj.mix_str);
                            }
                            else if(obj.status === 0){
                                show_alert_ms($("#msg"),0,obj.msg);
                //                goToMessage();
                            }
                            loading_object.hide();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            loading_object.hide(); 
                            alert(thrownError);
                        }
                    });
                });
                
                
                //proses hapus chart
                $("table#popup_table").on('click', 'a.delete', function(e){
                    var mch_code    = $(this).data("mch_code");
                    var prod_code   = $(this).data("product_code");
                    var chart_no    = $(this).data("chart_no");
                    var no_urut    = $(this).data("no_urut");
                    console.log(mch_code);
                    BootstrapDialog.confirm('Delete chart no <b>'+no_urut+'</b>?', function(result){
                        if(result) {
                            //ajax hapus komposisi material dengan no urutan terpilih
                            $("#msg_popup").hide();
                            var data = "mach_code="+mch_code+"&product_code="+prod_code+"&chart_no="+chart_no;

//                            loading_object.css("z-index","2000");
//                            loading_object.show();
                            jQuery.ajax({
                                type: "POST", // HTTP method POST or GET
                                url: base_url+"ajax/bom_chart_delete.php", //Where to make Ajax calls
                                dataType:"text", // Data type, HTML, json etc.
                                data:data, //Form variables
                                success:function(response){
                                    var obj = jQuery.parseJSON(response);
                                    if(obj.status === 1){
                                        alert(obj.msg);
                                        $(".col"+no_urut).remove();
                                    }
                                    else if(obj.status === 0){
                                        alert(obj.msg);
                                    }
//                                    loading_object.hide();
                                },
                                error:function (xhr, ajaxOptions, thrownError){
                                    loading_object.hide(); 
                                    alert(thrownError);
                                }
                            });
                        }
                        else{return false;}
                    });
                });
                
                //proses transfer chart ke mix_design_composition
                $("table#popup_table").on('click', 'a.transfer', function(e){
                    var mch_code    = $(this).data("mch_code");
                    var prod_code   = $(this).data("product_code");
                    var chart_no    = $(this).data("chart_no");
                    var no_urut    = $(this).data("no_urut");
                    console.log(mch_code);
                    BootstrapDialog.confirm('Transfer chart no <b>'+no_urut+'</b>?', function(result){
                        if(result) {
                            //ajax hapus komposisi material dengan no urutan terpilih
                            $("#msg_popup").hide();
                            var data = "mach_code="+mch_code+"&product_code="+prod_code+"&chart_no="+chart_no;

//                            loading_object.css("z-index","2000");
//                            loading_object.show();
                            jQuery.ajax({
                                type: "POST", // HTTP method POST or GET
                                url: base_url+"ajax/bom_chart_transfer.php", //Where to make Ajax calls
                                dataType:"text", // Data type, HTML, json etc.
                                data:data, //Form variables
                                success:function(response){
                                    var obj = jQuery.parseJSON(response);
                                    if(obj.status === 1){
                                        alert(obj.msg);
                                    }
                                    else if(obj.status === 0){
                                        alert(obj.msg);
                                    }
//                                    loading_object.hide();
                                },
                                error:function (xhr, ajaxOptions, thrownError){
                                    loading_object.hide(); 
                                    alert(thrownError);
                                }
                            });
                        }
                        else{return false;}
                    });
                });
            });
	</script>
    </body>
</html>
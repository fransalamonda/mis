<?php
session_start();
include "./inc/constant.php";
include "db.php";
//kode menu
$_SESSION['menu'] = "upload_bom";
if(isset($_SESSION['login'])){$object = (object)$_SESSION['login'];}
$date = date("d/m/Y");
include "inc/version.php";
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
             <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
            <!--<link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>-->	
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
        <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($con,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){
                while($row = mysqli_fetch_array($result)){
                    $chartno = $row['chart_no'];
                    if($chartno == '1' ){
        ?>
        <div align="right" style="color:#000099"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php } elseif($chartno == '2' ) { ?>
        <div align="right" style="color:#FF0000"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '3' ) { ?>
        <div align="right" style="color:#077"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '4' ) { ?>
        <div align="right" style="color:#0f1"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '5' ) { ?>
        <div align="right" style="color:#f90"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '6' ) { ?>
        <div align="right" style="color:#f9f"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }elseif($chartno == '7' ) { ?>
        <div align="right" style="color:#f4f"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php }else{ ?>
        <div align="right" style="color:#0a9"><h4><?php echo $row['name_bom']; ?></h4></div>
        <?php } } } ?>
        <div class="col-sm-12 colsm-offset-3">
            <h1 class="page-header">BOM Datacenter</h1>
            <div id="msg" class="alert-danger"></div>
            <form class="form-horizontal form-bordered" action="#"  id="transfer"  x-data="{foo :''}">
                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Version :</label>
                    <div class="col-md-3">
                        <?php
                            $conn= mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
                            mysqli_select_db($conn,DB_NAME);
                            $query_select = "SELECT DISTINCT a.`chart_no`,b.`name_bom`
                                                                  FROM `mix_package_composition` a
                                                                  LEFT JOIN `tbl_code_bom` b ON a.`chart_no` = b.`code_bom`
                                                                  WHERE `mch_code` = '".PLANT_ID."'";
                            $result_select = mysqli_query($conn,$query_select);
                            if(!$result_select){
                                die("Error : ".  mysqli_error());
                            }
                        ?>
                        <select id="chart_no" class="form-control" @change="foo = $event.target.value">
                            <option > - pilih - </option>
                            <?php
                                        while($data = mysqli_fetch_array($result_select)):
                            ?>
                            <option title="<?php echo $data['chart_no'];?>" x-bind:value="<?php echo $data['chart_no'];?>"> <?php echo $data['name_bom'];?></option>
                            <?php
                                endwhile;
                            ?>
                        </select>
                    </div>
                </div>                                        
                <div class="form-group" style="padding-bottom: 5px;margin-bottom: 0px;">
                    <label class="col-md-4 control-label" for="textinput">Transfers :</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn  btn-primary">Submit&nbsp;<i class="fa fa-weibo"></i></button>
                         <?php if(isset($object) && ($object->group_id==1|| $object->group_id==4)){ ?>
                        <a  x-bind:href = "'deletemixpackage.php?id='+foo"  class="btn btn-danger"  onclick="return confirm('CEK Dulu Bos Sebelom Dihapus?')">Delete Versi&nbsp;<i class="fa fa-trash"></i></a> 
                         
                    <?php }else {     ?>

                                                <?php } ?>
                    </div>      
                </div>
                
            </form>
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
                                                  
                                                                <?php if(isset($object) && ($object->group_id==1|| $object->group_id==4)){ ?>
                                                        <td> 
                                                                <a href="deletemixdesign.php?id=<?php echo $row['product_code'];?>" onclick="return confirm('CEK Dulu Bos Sebelom Dihapus?')" class="btn btn-danger btn-xs" title="edit data">Delete Mix Design</a>
                                                                  <a href="" data-mach="<?php echo $row['mch_code'];?>" data-product="<?php echo $row['product_code'];?>" class="view btn btn-xs btn-warning">View</a> </td>
                                                <?php }else {     ?>
                                                    <td>
                                                        <a href="" data-mach="<?php echo $row['mch_code'];?>" data-product="<?php echo $row['product_code'];?>" class="view btn btn-xs btn-warning">View</a>
                                                <?php } ?>
                                                                
                                                                
                                                           
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
                        <div class="form-group"><b/></div>
                    <!--    <div id="msg" class="alert"></div> -->
                </div>
            <?php
    
    ?>
            </div>
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
            <!-- Modal -->
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
                    obj_msg.empty().append("<strong>Error</strong><br/>").append(msg_text);
                }
                obj_msg.fadeIn();
            }
             
            $(function() {
                var base_url    = location.origin+"/mis/";
                var loading_object = $(".loading");
                
                //transfer 
                $("#transfer").submit(function(e){
                    e.preventDefault();
                    var chart_no = $("#chart_no");
                    if(chart_no.val() === ''){show_alert_ms($("#msg"),99,"Chart kosong");return false;}
                    var data = "chart_no="+chart_no.val();
                    loading_object.show();
                    jQuery.ajax({
                        type: "POST", // HTTP method POST or GET
                                url: base_url+"ajax/bom_chart_transfer.php", //Where to make Ajax calls
                                dataType:"text", // Data type, HTML, json etc.
                                data:data, //Form variables
                                success:function(response){
                                    var obj = jQuery.parseJSON(response);
                                        if(obj.status === 1){
                                            show_alert_ms($("#msg"),1,obj.msg);
                                            $("#msg").html(obj.msg);
                                        }
                                        else if(obj.status === 0){
                                            show_alert_ms($("#msg"),0,obj.msg);
                            //                goToMessage();
                                        }
                                        loading_object.hide();
                                        (base_url,data,"refresh",loading_object,msg);
                                }
                    });
                });
                
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
                $( "select" ) .change(function () {    
                    document.getElementById("loc").innerHTML="You selected: "+document.getElementById("chart_no").value;  
                    });  
                
            });
	</script>
    </body>
</html>


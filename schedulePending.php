<?php
session_start();
//kode menu
$_SESSION['menu'] = "schedulemanual";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
include"db.php";
include './inc/constant.php';
$mysqli = new mysqli(DB_SERVER,DB_USER,DB_PASS);
if(!$mysqli){die("MySQL Error:".$mysqli->connect_error);}
$db_con = $mysqli->select_db(DB_NAME);
if(!$db_con){
    exit("Database ".DB_NAME."Cannot be found");
}
$date = date("d/m/Y");  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>List Delivery Schedule</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/> 
        <style type="text/css">
            th,td{ font-size: 12px; }
        </style>
    <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
            $(document).ready(function(){
                $("div.alert").fadeOut(3000);
                load_data();
        function load_data(so_no)
        {
            $.ajax({
                method:"POST",
                url:"data.php",
                data: { so_no:so_no},
                success:function(hasil)
                {
                    $('.data').html(hasil);
                }
            });
        }
        $('#so_no').keyup(function(){
            var so_no = $("#so_no").val();
         
            load_data(so_no);
        });
       
        });
        </script>
    
    </head>
    <body>
        <?php
        include"inc/menu.php";
        ?>
        <!-- 20150626-->
        <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($conns,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){
                    while($row = mysqli_fetch_array($result)){
                    $chartno = $row['chart_no'];
                    if($chartno == '1' ){ ?> <div align="right" style="color:#000099"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php } 
                    elseif($chartno == '2' ) { ?> <div align="right" style="color:#FF0000"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                    elseif($chartno == '3' ) { ?> <div align="right" style="color:#077"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                    elseif($chartno == '4' ) { ?> <div align="right" style="color:#0f1"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                    elseif($chartno == '5' ) { ?> <div align="right" style="color:#f90"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                    elseif($chartno == '6' ) { ?> <div align="right" style="color:#f9f"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                    elseif($chartno == '7' ) { ?> <div align="right" style="color:#f4f"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                    else{ ?> <div align="right" style="color:#0a9"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php }
                }
            }
        ?>
        <div class="container-fluid">
            <div class="row">
                <div id="messages" class="alert alert-danger">Merah Putih Beton</div> 
                <div class="col-sm-12 colsm-offset-3">
                    <h1 class="page-header">Pending Schedule</h1>
                    <center>
           <?php 
    if(isset($_GET['pesan'])){
        $pesan = $_GET['pesan'];
        if($pesan == "input"){
            echo  "Schedule Berhasil Ditambah !!";
        }else if($pesan == "update"){
            echo "Data berhasil di update.";
        }else if($pesan == "hapus"){
            echo "Data berhasil di hapus.";
        }
    }
    ?> </span> 
    
</center>
     <div class="row">
                          
                        </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">View Schedule</div>
                        <div id="msg" class="alert-danger"></div>

                        <div class="panel-body">

    <?php 
           $id=@$_GET['id'];
            $sql = "SELECT*FROM delivery_schedule WHERE id='$id'";
           
            $result = mysqli_query($conns,$sql);
            $row=mysqli_fetch_array($result);
              # code...
            
           ?>
  <form action="prosespending.php" method="post"  role="form"> 
<div class="col-md-12" >
                       <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">SO NO :</label>  
                            <div class="col-md-3">
                                <input type="text" id="search"  name="so_no" class="form-control" required value="<?=$row['so_no'];?>" readonly>
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Schedule Date :</label>  
                            <div class="col-md-3">
                               <input type="text" class="form-control" name="schedule_date"
                                    placeholder="Pilih Tanggal"  value="<?=$row['schedule_date'];?>" readonly>
                            </div>
                        </div>
                    </div>
                      <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Sales Name :</label>  
                            <div class="col-md-3">
                                <input type="" name="sales_name" class="form-control" value="<?=$row['sales_name'];?>" readonly> 
                            </div>
                        </div>
                    </div>
                   
                </div>
                     <div class="col-md-12 ">
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Customer Name :</label>  
                            <div class="col-md-3">
                                <input type="" name="customer_name" class="form-control" value="<?=$row['customer_name'];?>" readonly>
                                <input type="hidden" name="id" class="form-control" value="<?=$row['id'];?>" readonly>
                            </div>
                        </div>
                    </div>
      
                
                      <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Project Location :</label>  
                            <div class="col-md-3">
                                <input type="" name="project_location" class="form-control" value="<?=$row['project_location'];?>" readonly>
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Project Address :</label>  
                            <div class="col-md-3">
                                <input type="" name="project_address" class="form-control" value="<?=$row['project_address'];?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Customer ID:</label>  
                            <div class="col-md-3">
                                <input type="" name="customer_name" class="form-control" value="<?=$row['customer_id'];?>" readonly>
                                <input type="hidden" name="id" class="form-control" value="<?=$row['id'];?>" readonly>
                            </div>
                        </div>
                    </div>
      
                
                      <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Project ID :</label>  
                            <div class="col-md-3">
                                <input type="" name="project_location" class="form-control" value="<?=$row['project_id'];?>" readonly>
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Mix Design:</label>  
                            <div class="col-md-3">
                                <input type="" name="product_code" class="form-control" value="<?=$row['product_code'];?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                     <div class="col-md-12 ">
                     <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">D/O Vol :</label>  
                            <div class="col-md-3">
                                <input type="" name="deliv_order_vol" class="form-control" value="<?=$row['deliv_order_vol'];?>" readonly>
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Vol Schedule :</label>  
                            <div class="col-md-3">
                                <input type="" name="vol" class="form-control" value="<?=$row['1-24hr'];?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Uploud Date:</label>  
                            <div class="col-md-3">
                                <input type="" name="sales_name" class="form-control" value="<?=$row['date_upload'];?>" readonly> 
                            </div>
                        </div>
                    </div>
                  
                </div>



                 <div class="col-md-12">
                    <hr>
                     <div class="component">
                        <div class="form-group">
                         <div class="panel panel-default">
                        <div class="panel-heading">Keterangan Schedule Pending</div>
                        <div class="panel-body">
                            <div class="col-md-12">

                                <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="LOKASI PROYEK HUJAN">LOKASI PROYEK HUJAN</label>
                                </div>
                                <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="PROYEK TIDAK ADA SCHEDULE">PROYEK TIDAK ADA SCHEDULE</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="LOKASI BELUM SIAP">LOKASI BELUM SIAP</label>
                                 </div>
                                <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="SCHEDULE MUNDUR KE HARI BERIKUTNYA">SCHEDULE MUNDUR KE HARI BERIKUTNYA</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="PROYEK BATAL COR">PROYEK BATAL COR</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="IT TROUBLE">IT TROUBLE</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="PLANT TROUBLE">PLANT TROUBLE</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="LOADER TROUBLE">LOADER TROUBLE</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="LISTRIK GENSET ATAU PLN TROUBLE">LISTRIK GENSET ATAU PLN TROUBLE</label>
                                 </div>
								 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="MATRIAL KOSONG">MATRIAL KOSONG</label>
                                 </div>
                                 <div class="col-md-3 ">
                                    <label><input type="checkbox" name="remark[]" value="GANTI SO BARU">GANTI SO BARU</label>
                                 </div>
                                 <div class="col-md-3 ">   
                                 <button type="submit" class="btn btn-primary">Simpan <i class="fa fa-save "></i></button>
                                 </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-md-12 ">
                     <div class="component">
                        <div class="form-group">
                        
                        </div>
                    </div>
                </div>  
                </form>
                    </div>
                    </div>
                </div>
                         
  </html> 

                            
                </div>
            </div>
            <?php
    include "inc/version.php";
    ?>
        </div>
        <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
        <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
        <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
        <script>
    $(function() {
        $( "#filter" ).datepicker({ dateFormat: 'dd/mm/yy' });
                $("div#messages").hide()
    });
    </script>
        <script type="text/javascript">
                var base_url = location.origin+"/mis/";
                
                $(document).ready(function(){
//                    var response1='response from server';
                    var msg             = $("#messages");
                    var msg_content     = "";
                    var cur_url         = window.location;
                    $(".delete_ds").click(function(e){
                        e.preventDefault();
                        var IS_JSON = true;
                        var r = confirm("Are you sure?")
                        if(r === true){
                            var id_ds    = $(this).data("id");
                          //  var tanggal  = $("#h_date");
//                            alert (id_ds);
//                            alert (tanggal);
                            var myData = "so_no="+id_ds;
                           // alert (myData);
                            $("div#loading").show(); //hide loading image
                            $("div#bg-loading").show();
                            jQuery.ajax({
                                type: "POST", // HTTP method POST or GET
                                url: window.base_url+"act/list_ds_delete.php", //Where to make Ajax calls
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
                      $('#search').on('keyup', function() {
               
            });
                });
        </script>
    </body>
</html>




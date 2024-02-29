<?php
session_start();
//kode menu
$_SESSION['menu'] = "list_delivery_schedule";
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
        <meta http-equiv=refresh content=1000;url=list_delivery_schedule.php>
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
                    <h1 class="page-header">List Delivery Schedule</h1>
                    <?php
                    $status_list = false;
                    if(isset($_GET['filter']) && !empty($_GET['filter'])){
                        extract($_GET);
                        $q = "SELECT DS . * , TB.`username`, sum(bt.batch_vol) as total_load
                            FROM `delivery_schedule` DS
                            LEFT JOIN `tbl_user` TB ON DS.`id_user` = TB.`id_user`
                            LEFT JOIN batch_transaction bt ON bt.so_no = DS.so_no
                            WHERE DS.`schedule_date` = '$filter' group by so_no";
                        $hasil = $mysqli->query($q);
                        if(!$hasil){ exit(mysqli_error($mysqli)); }
                    $status_list = true;
                    }
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">Data Table</div>
                        <div id="msg" class="alert-danger"></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-2 pull-left">                                    
                                    <form role="form" method="GET">
                                      <div class="form-group input-group">
                                          <input type="text" class="form-control" id="filter" name="filter"
                                    placeholder="Pilih Tanggal" readonly="" value="<?php echo date('d/m/Y'); ?>">
                                          <span class="input-group-btn">
                                              <button class="btn btn-default" style="padding-top: 9px;padding-bottom: 9px;" type="submit"><i class="fa fa-search"></i></button>
                                          </span>
                                      </div>
                                    </form>
                                        <?php
                                        if($status_list == true){
                                        ?>
                                        <h4><small>Delivery Date </small><?php echo date("d M Y",strtotime(str_replace("/","-",$filter)));?></h4>   
                                        <?php
                                        }
                                        ?>
                                </div>
                               <div class="col-lg-4 pull-left"> 
								  <p style="Font-Size:20px;"colspan="3" class><b>PERHATIAN......!!!</b></p>
                                  <p style="color:red;"colspan="3" class><b>Jika Total Vol SO Terkirim Lebih Besar dari Sisa Vol SO Maka Segera Info ke bagian Sales atau ke bagian Sechedule</b></p>
                                  <p style="color:blue;"colspan="3" class><b>Pastikan Prod Code dan alamat sesuai permintaan Customer</b></p>
								  
								  </div>
								  
								 </head>
<body>
	<h1>
   <p style="Font-Size:20px;"colspan="3" class="text-center"><b>PUTAR VIDEO</b></p>	
	<video width="570px" height="130px" controls>
		<source src="TUTORIAL DELIVERY-1.mp4" type="video/mp4">
		<source src="TUTORIAL DELIVERY-1" type="video/ogg">
	</video>
 
                        </div>
                        <div class="table-scrollable">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" action="" onSubmit="return validate();">
                                            <div class="portlet box blue">
                                                <table class="table table-bordered table-hover small">
                                                    <thead>
                                                       
                                                        <tr>
                                                            <?php 
                                                            date_default_timezone_set("Asia/Jakarta");
                                                            $jam = date('G');
                                                             $jamplus1 =  $jam+1;
                                                            $jamplus2 = $jam+2;
                                                            $jamplus3 =  $jam+3;
                                                            $jamplus4 = $jam+4;
                                                            if ($jamplus1 == 25 ) {
                                                                $jamplus1 = 1 ;
                                                            }
                                                            if ($jamplus2 == 25 ) {
                                                                $jamplus2 = 1 ;
                                                            }elseif ($jamplus2 == 26) {
                                                                    $jamplus2 = 2 ;
                                                                }
                                                            if ($jamplus3 == 25 ) {
                                                                $jamplus3 = 1 ;
                                                            }elseif ($jamplus3 == 26) {
                                                                    $jamplus3 = 2 ;
                                                            }elseif ($jamplus3 == 27) {
                                                                    $jamplus3 = 3 ;
                                                                }
                                                            if ($jamplus4 == 25 ) {
                                                                $jamplus4 = 1 ;
                                                            }elseif ($jamplus4 == 26) {
                                                                    $jamplus4 = 2 ;
                                                            }elseif ($jamplus4 == 27) {
                                                                    $jamplus4 = 3 ;
                                                            }elseif ($jamplus4 == 28) {
                                                                    $jamplus4 = 4 ;
                                                                }
                                                            
                                                             ?>
                                                            <th>No</th><th>SO No</th><th>Prod Code</th><th>Delv Vol</th><th>Today Vol</th><th>Out Vol</th><th>Total Vol Load</th><th>Customer Name</th><th>Proj Location</th><th>Jam <?php echo $jam?></th> <th>Jam <?php echo $jamplus1?></th> <th>Jam <?php echo $jamplus2 ?></th><th>Jam <?php echo $jamplus3 ?></th><th>Jam <?php echo $jamplus4 ?></th>

                                                                <?php
                                                                    if(isset($object) && ($object->group_id==5 || $object->group_id==4 || $object->group_id==6 || $object->group_id==2)){
                                                                 ?>
                                                            <th>Action</th> <?php }else { ?> <th></th> <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                        <?php
                                                    if($status_list == true){
                                                        $i=1;
                                                        $deliv_order_vol = 0;
                                                        $deliv_order_h1 = 0;
                                                        $deliv_order_h = 0;
                                                        $duaempathr = 0;
                                                        $duaempathr_h1 = 0;
                                                        $duaempathr_h = 0;
                                                        $outvol = 0;
                                                        $totaljamplus =0;
                                                        $totaljamplussatu =0;
                                                        $totaljamplusdua =0;
                                                        $totaljamplustiga =0;
                                                        $totaljamplusempat =0;
                                                        
                                                        $outvol_h1 = 0;
                                                        $outvol_h = 0;
                                                        while($row = $hasil->fetch_array(MYSQLI_ASSOC) ){
                                                            date_default_timezone_set("Asia/Jakarta");
                                                            $jam = date('G');
                                                            $menit = date('H:i:s');

                                                           $notif1 = "$jam:30:00";
                                                           $notif2 = "$jam:55:00";
                                                           $notif3 = "$jam:57:00";

                                                            $jamplus1 =  $jam+1;
                                                            $jamplus2 = $jam+2;
                                                            $jamplus3 =  $jam+3;
                                                            $jamplus4 = $jam+4;
                                                          
                                                            if ($jamplus1 == 25 ) {
                                                                $jamplus1 = 1 ;
                                                            }
                                                            if ($jamplus2 == 25 ) {
                                                                $jamplus2 = 1 ;
                                                            }elseif ($jamplus2 == 26) {
                                                                    $jamplus2 = 2 ;
                                                                }
                                                            if ($jamplus3 == 25 ) {
                                                                $jamplus3 = 1 ;
                                                            }elseif ($jamplus3 == 26) {
                                                                    $jamplus3 = 2 ;
                                                            }elseif ($jamplus3 == 27) {
                                                                    $jamplus3 = 3 ;
                                                                }
                                                            if ($jamplus4 == 25 ) {
                                                                $jamplus4 = 1 ;
                                                            }elseif ($jamplus4 == 26) {
                                                                    $jamplus4 = 2 ;
                                                            }elseif ($jamplus4 == 27) {
                                                                    $jamplus4 = 3 ;
                                                            }elseif ($jamplus4 == 28) {
                                                                    $jamplus4 = 4 ;
                                                                }
                                                      
                                                             if ($row[$jam] && $menit >= $notif1) {
                                                              echo  "<script> alert('Waktu  Tinggal 30 Menit lagi Harap Loading')</script>";
                                                               }elseif ($row[$jam] && $menit >= $notif2) {
                                                                     echo  "<script> alert('Waktu Tinggal 25 Menit lagi Harap Loading')</script>";
                                                               }elseif ($row[$jam] && $menit >= $notif3) {
                                                                     echo  "<script> alert('Waktu Tinggal 10 Menit lagi Harap Loading')</script>";
                                                               }


                                                            
                                                            $link = "";
                                                            $hari_ini = date('d/m/Y');
                                                            $deliv_order_vol+=$row['deliv_order_vol'];
                                                            $duaempathr+=$row['1-24hr'];                                                    
                                                            $outvol+=$row['out_volume'];
                                                            $totaljamplus+=$row[$jam];
                                                            $totaljamplussatu+=$row[$jamplus1];
                                                            $totaljamplusdua+=$row[$jamplus2];
                                                            $totaljamplustiga+=$row[$jamplus3];
                                                            $totaljamplusempat+=$row[$jamplus4];
                                                            $dates = $row['schedule_date'];
                                                            $udate = $row['date_upload'];
                                                            $dateu = date("d/m/Y",strtotime($udate));
                                                            //$q = "SELECT DS.*, TB.`username` FROM `delivery_schedule` DS LEFT JOIN `tbl_user` TB ON  DS.`id_user` = TB.`id_user` WHERE DS.`schedule_date` = '$filter'";
                                                            //print_r($dateu);
                                                            ?>
                                                            <?php
                                                            //hapus
                                                if($row['ds_code'] == 'M'){ ?>
                                                        <tr class="danger">
                                                        <td><span class="label label-sm label-danger"><?php echo $i++;?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['so_no'];?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['product_code'];?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['deliv_order_vol'];?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['1-24hr'];?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['out_volume'];?></span></td>
                                                         <td><span class="label label-sm label-danger"><?php echo $row['total_load'];?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['customer_name'];?></span></td>
                                                        <td><span class="label label-sm label-danger"><?php echo $row['project_location'];?></span></td>
                                                      
                                                      
                                                        <td><span class="label label-sm label-danger"><?php echo $row[$jam];?></span></td>
                                                          <td><span class="label label-sm label-danger"><?php echo $row[$jamplus1];?></span></td>
                                                            <td><span class="label label-sm label-danger"><?php echo $row[$jamplus2];?></span></td>
                                                           <td><span class="label label-sm label-danger"><?php echo $row[$jamplus3];?></span></td>
                                                            <td><span class="label label-sm label-danger"><?php echo $row[$jamplus4];?></span></td>

                                                <?php if(isset($object) && ($object->group_id==5 || $object->group_id==4 || $object->group_id==6|| $object->group_id==2)){ ?>
                                                        <td><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                                <?php }else {     ?>
                                                        <td></td>
                                                <?php } ?>
                                            </tr>
                                            <?php }
                                            //dadakan
                                                elseif($dates <= $dateu && $row['ds_code'] != 'M'){ 
                                                    $deliv_order_h+=$row['deliv_order_vol'];
                                                    $duaempathr_h+=$row['1-24hr'];
                                                    $outvol_h+=$row['out_volume'];
                                                ?>

                                            <?php if ($row['out_volume']==0) {?>
                                                 <tr class="success">
                                            <?php } else { ?>
                                                 <tr class="warning">
                                           <?php } ?>

                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $row['so_no'];?></td>
                                                <td><?php echo $row['product_code'];?></td>
                                                <td><?php echo $row['deliv_order_vol'];?></td>
                                                <td><?php echo $row['1-24hr'];?></td>
                                                <td><?php echo $row['out_volume'];?></td>
                                                <td><?php echo $row['total_load'];?></td>
                                                <td><?php echo $row['customer_name'];?></td>
                                                <td><?php echo $row['project_location'];?></td>
                                                <td><?php echo $row[$jam];?></td>
                                                <td><?php echo $row[$jamplus1];?></td>
                                                <td><?php echo $row[$jamplus2];?></td>
                                                <td><?php echo $row[$jamplus3];?></td>
                                                <td><?php echo $row[$jamplus4];?></td>
                                              
                                                <?php if(isset($object) && ($object->group_id==4)){ ?>
                                                <td><a href="carischedule.php?id=<?=$row['id'];?>">Approved</a><br><a href="#" class="delete_ds" data-id="<?php echo $row['so_no']; echo $row['schedule_date'];?>"  >Delete</a> <br><a style="color:;" href="batch_request.php?so_no=<?=$row['so_no'];?>">Send</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                                  <?php }else if(isset($object) && ($object->group_id==2) && ($row[$jam]==0)){ ?>
                                                <td > <a href="carischedule.php?id=<?=$row['id'];?>">Approved</a><br> <a href="#" style="color:red;">Hubungi SPV</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                                  <?php }else if(isset($object) && ($object->group_id==2)){ ?>
                                                <td><a style="color:;" href="batch_request.php?so_no=<?=$row['so_no'];?>">Send</a><br><a  href="carischedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td> 
                                                <?php }else if(isset($object) && ($object->group_id==6) && ($row[$jam]==0)){ ?>
                                                <td > <a href="#" style="color:red;">Hubungi SPV</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                                 <?php }else if(isset($object) && ($object->group_id==6)){ ?>
                                                <td><a style="color:;" href="batch_request.php?so_no=<?=$row['so_no'];?>">Send</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td> 
                                            <?php }else {(isset($object) && ($object->group_id==5)) ?>
                                                <td><a href="#" class="delete_ds" data-id="<?php echo $row['so_no']; echo $row['schedule_date'];?>"  >Delete</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td> 
                                              
                                                <?php } ?>
                                            </tr>
                                            <?php }
                                            //normal
                                                elseif($dates > $dateu && $row['ds_code'] != 'M') {
                                                $deliv_order_h1+=$row['deliv_order_vol'];
                                                $duaempathr_h1+=$row['1-24hr'];
                                                $outvol_h1+=$row['out_volume'];
                                                ?>
                                         <?php if ($row['out_volume']==0) {?>
                                                 <tr>
                                            <?php } else { ?>
                                                 <tr class="warning">
                                           <?php } ?>
                                             <?php if ($row['out_volume']==0) {?>
                                                 <tr>
                                            <?php } else { ?>
                                                 <tr class="warning">
                                           <?php } ?>

                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $row['so_no'];?></td>
                                                <td><?php echo $row['product_code'];?></td>
                                                <td><?php echo $row['deliv_order_vol'];?></td>
                                                <td><?php echo $row['1-24hr'];?></td>
                                                <td><?php echo $row['out_volume'];?></td>
                                                <td><?php echo $row['total_load'];?></td>
                                                <td><?php echo $row['customer_name'];?></td>
                                                <td><?php echo $row['project_location'];?></td>
                                                <td><?php echo $row[$jam];?></td>
                                                <td><?php echo $row[$jamplus1];?></td>
                                                <td><?php echo $row[$jamplus2];?></td>
                                                   <td><?php echo $row[$jamplus3];?></td>
                                                <td><?php echo $row[$jamplus4];?></td>
                                                 <?php if(isset($object) && ($object->group_id==4) ){ ?>
                                                <td><a href="carischedule.php?id=<?=$row['id'];?>">Approved</a><br><a href="#" class="delete_ds" data-id="<?php echo $row['so_no']; echo $row['schedule_date'];?>"  >Delete</a> <br><a style="color:;" href="batch_request.php?so_no=<?=$row['so_no'];?>">Send</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                          
                                                <?php }else if(isset($object) && ($object->group_id==2) && ($row[$jam]==0)){ ?>
                                                <td > <a href="carischedule.php?id=<?=$row['id'];?>">Approved</a><br> <a href="#" style="color:red;">Hubungi SPV</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                            <?php }else if(isset($object) && ($object->group_id==2)){ ?>
                                                <td><a style="color:;" href="batch_request.php?so_no=<?=$row['so_no'];?>">Send</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td> 
                                                 <?php }else if(isset($object) && ($object->group_id==2) && ($row[$jam]==0)){ ?>
                                                <td > <a href="#" style="color:red;">Hubungi SPV</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                            <?php }else if(isset($object) && ($object->group_id==6) && ($row[$jam]==0)){ ?>
                                                <td > <a href="#" style="color:red;">Hubungi SPV</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td>
                                                 <?php }else if(isset($object) && ($object->group_id==6)){ ?>
                                                <td><a style="color:;" href="batch_request.php?so_no=<?=$row['so_no'];?>">Send</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td> 
                                            <?php }else {(isset($object) && ($object->group_id==5)) ?>
                                                <td><a href="#" class="delete_ds" data-id="<?php echo $row['so_no']; echo $row['schedule_date'];?>"  >Delete</a><br><a  href="viewschedule.php?id=<?=$row['id'];?>" style="color:#48D1CC;">View</a></td> 
                                                <?php } ?>
                                            </tr>         
                                            <?php     }?>
                                                <?php
                                                   }
                                            ?>
                                            <tr>
                                                <td colspan="3" class="text-right bolt">Total Delv Order Vol All:</td>
                                                <td colspan="1"><?php echo $deliv_order_vol;?></td>
                                                <td colspan="1"><?php echo $duaempathr;?></td>
                                                <td colspan="1"><?php echo $outvol;?></td>
                                                <td colspan="3"></td>
                                                <td colspan="1"><?php echo $totaljamplus;?></td>
                                                <td colspan="1"><?php echo $totaljamplussatu;?></td>
                                                <td colspan="1"><?php echo $totaljamplusdua;?></td>
                                                <td colspan="1"><?php echo $totaljamplustiga;?></td>
                                                <td colspan="1"><?php echo $totaljamplusempat;?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-right bolt">Total Delv Order Vol H-1 :</td>
                                                <td colspan="1"><?php echo $deliv_order_h1;?></td>
                                                <td colspan="1"><?php echo $duaempathr_h1; ?></td>
                                                <td colspan="1"><?php echo $outvol_h1;?></td>
                                                <td colspan="4"></td>
                                                <td colspan="1"></td>
                                                <td colspan="1"></td>
                                                <td colspan="1"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-right bolt">Total Delv Order Vol H :</td>
                                                <td colspan="1"><?php echo $deliv_order_h;?></td>
                                                <td colspan="1"><?php echo $duaempathr_h;?></td>
                                                <td colspan="1"><?php echo $outvol_h;?></td>
                                                <td colspan="4"></td>
                                                <td colspan="1"></td>
                                                <td colspan="1"></td>
                                                <td colspan="1"></td>
                                            </tr>                                            
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                            </table>
                                </form>
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
                });
        </script>
    </body>
</html>
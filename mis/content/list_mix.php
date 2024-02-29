<?php
session_start();
//kode menu
$_SESSION['menu'] = "bom_list";
if(isset($_SESSION['login'])){
    $object = (object)$_SESSION['login'];
}
include "db.php";
$date = date("d/m/Y");
include_once './inc/constant.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>List Mix Design</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
<!--        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/> -->
        <style type="text/css">
            th,td{
                font-size: 12px;
            }
        </style>
		<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
	    	$(document).ready(function() {
	    		$("div.alert").fadeOut(3000);
	    	});
    	</script>

        
	</head>
	<body>
        <?php
        include"inc/menu.php";
        ?>
        <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom
                            FROM `mix_package_composition` AS A
                            INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no`
                            WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($conns,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){
                    while($row = mysqli_fetch_array($result)){
                        $chartno = $row['chart_no'];
                        if($chartno == '1' ){
        ?>
                <div align="right" style="color:#000099">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
        <?php
                        } elseif($chartno == '2' ) {                
                            ?>
        <div align="right" style="color:#FF0000">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '3' ) {                
                            ?>
        <div align="right" style="color:#077">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '4' ) {                
                            ?>
        <div align="right" style="color:#0f1">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '5' ) {                
                            ?>
        <div align="right" style="color:#f90">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '6' ) {                
                            ?>
        <div align="right" style="color:#f9f">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }elseif($chartno == '7' ) {                
                            ?>
        <div align="right" style="color:#f4f">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }else{
                              ?>
        <div align="right" style="color:#0a9">
            <h4><?php echo $row['name_bom']; ?></h4>
        </div>
        <?php
                          }
                    }
            }
        ?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 colsm-offset-3">
					<h1 class="page-header">List Mix Design</h1>
					
					<?php
                    $status_list = false;
                    if(isset($_GET['filter']) && !empty($_GET['filter'])){
                        extract($_GET);
                        $q = "SELECT * FROM `mix_design` WHERE `product_code` LIKE '$filter%'";
//                        $q="SELECT DISTINCT `mix_design`.`product_code`, `mix_design`.`description`
//                            FROM `mix_design`
//                            RIGHT JOIN `mix_design_composition`
//                            ON `mix_design`.`product_code` = `mix_design_composition`.`product_code` AND
//                              `mix_design`.`product_code` LIKE '$filter%'";
                        
                        $result = mysqli_query($conns,$q);
                        if(!$result) die(mysqli_error());
                        $status_list = true;
                    }
                    elseif(isset($_GET['filter']) && empty($_GET['filter'])){
                        $q = "SELECT * FROM `mix_design` WHERE `product_code`";
                     //   $q="SELECT DISTINCT `mix_design`.`product_code`, `mix_design`.`description`
                     //       FROM `mix_design`
                     //       RIGHT JOIN `mix_design_composition`
                     //       ON `mix_design`.`product_code` = `mix_design_composition`.`product_code`";
                        $result = mysqli_query($conns,$q);
                        if(!$result) die(mysqli_error());
                        $status_list = true;
                    }
					?>
                    
					<div class="table-responsive">
                        <div class="row">
                            <div class="col-lg-2 pull-left">
                                <form role="form" method="GET">
                                  <div class="form-group input-group">
                                    <input type="text" class="form-control" id="filter" name="filter" placeholder="Product Code"/>
                                    <input type="hidden" class="form-control" name="menu" value="mix_list"/>
                                        <span class="input-group-btn">
                                          <button class="btn btn-default" style="padding-top: 9px;padding-bottom: 9px;" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                  </div>
                                </form>
                            </div>

                            <div class="col-lg-2 pull-left">
                                <a href="ajax/export_mix_design_composition.php" class="btn btn-success btn-xs" style="padding-top: 9px;padding-bottom: 9px;" ><i class="glyphicon glyphicon-download-alt"></i> DOWNLOAD ALL MIX DESIGN </i></a>

                                
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
                                
                                <form method="post" action=""  id="data-table" onSubmit="return validate();">
                                    <table class="table table-bordered table-hover">
            			              <thead>
            			                <tr>
            			                  <th>No</th>
            			                  <th>Product Code</th>
            			                  <th>Product Name</th>
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
            			                     <td><?php echo $row['product_code'];?></td>
            			                     <td><?php echo $row['description'];?></td>
                                             <td><a href="bom.php?menu=list_detail&filter=<?php echo $row['product_code'];?>&parent=<?php echo $_GET['filter'];?>">Detail</a></td>
            			                </tr>
            								<?php
            							}
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
            <?php
    include "inc/version.php";
    ?>
		</div>
	</body>
</html>
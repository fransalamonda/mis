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
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 colsm-offset-3">
					<h1 class="page-header">List Mix Design Composition</h1>
					
					<?php
                    $status_list = false;
                    if(isset($_GET['filter']) && !empty($_GET['filter'])){
                        extract($_GET);
                        $q = "SELECT * FROM `mix_design_composition` WHERE `product_code` = '$filter'";
                        $result = mysqli_query($conns,$q);
                        if(!$result) die(mysqli_error());
                        else {
                            $status_list = true;
                            $count = mysqli_num_rows($result);
                        }
                        if($count > 0){
                            ?>
                       <!--     <a class="btn btn-info btn-xs" href="list_mix.php?filter=<?php echo $_GET['parent'];?>"><i class="fa fa-angle-left"></i> Kembali</a> -->
                                <a class="btn btn-info btn-xs" href="http://localhost/mis/bom.php?filter=&menu=mix_list"><i class="fa fa-angle-left"></i> Kembali</a>
                            &nbsp;<br /><br />
                            <?php
                        }
                    }
                    
					?>


                   
                    
					<div class="table-responsive">

                        <form action="downloadmixdesign.php" method="post">

                            <div class="form-group input-group">


                            <input type="hidden" class="form-control" id="filter1" name="$filter1" value="<?php echo $_GET['filter'];?>" required>
                                  
                            <button type="submit" class="btn btn-primary btn-xs" style="padding-top: 9px;padding-bottom: 9px;"><i class="glyphicon glyphicon-download-alt"></i> Download Mix Design</button>
               
                                 
                                              
                            </div>

                           

                        </form>

                        <div class="row">

                            <div class="col-lg-12">                                
                                <form method="post" action="" onSubmit="return validate();">
                                    <table class="table table-bordered table-hover">
            			              <thead>
            			                <tr>
            			                  <th>No</th>
            			                  <th>Product Code</th>
            			                  <th>Mat Group</th>
                                                    <th>Mat Code</th>
                                                    <th>Mat Name</th>
                                                    <th>Mix Qty</th>
                                                    <th>Unit</th>
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
                                            <td><?php echo $row['material_group'];?></td>
                                             <td><?php echo $row['material_code'];?></td>
                                             <td><?php echo $row['material_name'];?></td>
                                             <td><?php echo $row['mix_qty'];?></td>
                                             <td><?php echo $row['unit'];?></td>
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
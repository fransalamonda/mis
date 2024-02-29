<?php
	include "db.php";
	$q = "SELECT * FROM `conversion`";
	$result = mysql_query($q);
	if(!$result){
		die("Cannot select table data");exit();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>List Detail konversi</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
    	$(document).ready(function() {
    		$("div.alert").fadeOut(3000);
    	});
    	</script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-9 colsm-offset-3">
					<h1 class="page-header">List Conversion Data</h1>
					
					<?php
					if(isset($_GET['msg']) && !empty($_GET['msg'])){
						extract($_GET);
						if($msg == '0'){
							?>
							<div class="alert alert-danger">
						      <strong>Oh snap!!</strong> Delete item failed
						    </div>
							<?php
						}
						else{?>
							<div class="alert alert-success">
						      <strong>Well done!</strong> You successfully delete item.
						    </div>
						<?php }
					}
					?>
					<a href="index.php" class="btn btn-success">Home</a>
					<a href="detail_add.php" class="btn btn-primary">Add New</a>
					<a href="list_konversi.php" class="btn btn-default">
					  	<span class="glyphicon glyphicon-refresh"></span> Reload
					</a>
					<div class="table-responsive">
			            <table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>No</th>
			                  <th>Old</th>
			                  <th>New</th>
			                  <th colspan="2">Action</th>
			                </tr>
			              </thead>
			              <tbody>
			              <?php
			              $i=1;
			              	while($row = mysql_fetch_array($result)){
			              		$link = "id=".$row['id']."&code=".md5(rand(1,999));
								?>
							<tr>
			                  <td><?php echo $i++;?></td>
			                  <td><?php echo $row['old']?></td>
			                  <td><?php echo $row['new']?></td>
			                  <td class="text-center"><a href="detail_edit.php?<?php echo $link;?>" title="Edit value" class="glyphicon glyphicon-pencil"></a></td>
			                  <td class="text-center"><a href="detail_delete.php?<?php echo $link;?>" title="Edit value" class="glyphicon glyphicon-remove" onclick="return confirm('Are you sure you want to delete this item?');"></a></td>
			                </tr>
								<?php
							}
			              ?>
			                
			              </tbody>
			            </table>
			          </div>
				</div>
			</div>
		</div>
	</body>
</html>
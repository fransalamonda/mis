<?php
session_start();
include_once './inc/constant.php';
//include 'db.php';
//kode menu
$_SESSION['menu'] = "index";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; 
//header("Location:login.php");
}else{
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
            <title>DES Application -About-</title>
            <link rel="stylesheet" href="css/normalize.css" />
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="css/bootstrap.min.css"/>
            <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
            <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
            <link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
            <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
            <!--<link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>-->	
            <style>
                #msg_popup{display: none;}
            </style>
        </head>
<body>
    <?php include "./inc/menu.php"; ?>
    <div class="container-fluid">
  <div class="container-fluid">
    <div class="content">
       
      <div class="modal-body">
          <h3>DEC Application</h3>
          <p><span class="small"><i>Last revised on May,  2021</i></span></p>
          <br/>
          <address><span class="small">Develop by</span> </address>
          <address><b>Ridwan May Fernandos</b><br/><em>Comp & Inst Division</em><br/><span class="bold">PT Motive Mulia</span><span class="bold"> - Merah Putih Beton</span></address>
          <p>The Application is built in order to help Batcher retrieves data from Batching machine</p>
      </div>
<!--      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>      
    </div>
<!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       
      <div class="modal-body">
          <h3>DEC / MIS Application</h3>
          <p><span class="small"><i>Last revised on May,  2016</i></span></p>
          <br/>
          <address><span class="small">Develop by</span> </address>
          <address><b>Fransalamonda</b><br/><em>Comp & Inst Division</em><br/><span class="bold">PT Motive Mulia</span><span class="bold"> - Merah Putih Beton</span></address>
          <p>The Application is built in order to help Batcher retrieves data from Batching machine</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>-->
</body>
</html>                                		
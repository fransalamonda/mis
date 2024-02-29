<?php
session_start();
//kode menu
$_SESSION['menu'] = "batch_request_adjustment";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
include_once './inc/constant.php';
include "db.php";
?>

<!--
privileges : batcher (need to login)
-->
<!doctype html>
<head>
    <title>Batch Request Adjustment</title>
    <style>
/*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 400px;}*/
        .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 2px;}
        .bar { background-color: #B4F5B4; width:0%; height:2px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; }
        #status{margin-top: 30px;}
        #batch-add-adjustmen{ display:none; }
        #batch-add-no-docket{ display: none;}
    #plant_id{ margin:0px auto; width:400px; }
    form{ margin:20px auto;width:50%; }
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/> 
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/style.css" />
    
</head>
<body class="corporate">
    <?php 
    include "inc/menu.php";
    $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
            if(mysqli_connect_errno()){ die("Failed to connect to MySQL: " . mysqli_connect_error());}
    ?>
    <div class="loading"></div>
    <?php 
            $status_list = false;
            $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";   
            $result = mysqli_query($conns,$query_war);
            if(!$result) die(mysqli_error());
            $status_list = true;
            if($status_list == true){
                while($row = mysqli_fetch_array($result)){ $chartno = $row['chart_no'];
                if($chartno == '1' ){ ?> <div align="right" style="color:#000099"> <h4><?php echo $row['name_bom']; ?></h4> </div> <?php } 
                elseif($chartno == '2' ) {?><div align="right" style="color:#FF0000"> <h4><?php echo $row['name_bom']; ?></h4> </div><?php }
                elseif($chartno == '3' ) {?><div align="right" style="color:#077"><h4><?php echo $row['name_bom']; ?></h4></div><?php }
                elseif($chartno == '4' ) {?><div align="right" style="color:#0f1"><h4><?php echo $row['name_bom']; ?></h4></div><?php }
                elseif($chartno == '5' ) {?><div align="right" style="color:#f90"><h4><?php echo $row['name_bom']; ?></h4></div><?php }
                elseif($chartno == '6' ) {?><div align="right" style="color:#f9f"><h4><?php echo $row['name_bom']; ?></h4></div><?php }
                elseif($chartno == '7' ) {?><div align="right" style="color:#f4f"><h4><?php echo $row['name_bom']; ?></h4></div><?php }
                else{ ?><div align="right" style="color:#0a9"><h4><?php echo $row['name_bom']; ?></h4></div> <?php } } } ?>
                
            <div class="container-fluid">
                <ul class="nav nav-tabs">
                <!-- Untuk Semua Tab.. pastikan a href=”#nama_id” sama dengan nama id di “Tap Pane” dibawah-->
                <?php if(isset($object) && ($object->group_id == 1 ||  $object->group_id == 2 || $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 6 )){ // production admin ?>
                  <li class="active"><a href="#home" data-toggle="tab">Adjustment</a></li> <!-- Untuk Tab pertama berikan li class=”active” agar pertama kali halaman di load tab langsung active-->
                  <li><a href="#profile" data-toggle="tab">Not Docket</a></li>
                  <?php }?>
                  <li><a href="#settings" data-toggle="tab">Self Usage</a></li>
                </ul>

<div class="tab-content">
    <?php if(isset($object) && ($object->group_id == 1 ||  $object->group_id == 2 || $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 6 )){ // production admin ?>
    <div class="tab-pane active" id="home">
    <form action="ajax/batch_request_adjustment_act.php" method="post" enctype="multipart/form-data" id="query" class="form-horizontal " role="form"> 
        <div class="form-group"><h1>Delivery Schedule Adjustment</h1></div>
        <div id="messages" style="display: none;">asasd</div>
        <div class="component">
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Docket No :</label>  
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="docket_no" value="" name="docket_no" placeholder="Docket No" class="form-control input-md">
                        <span class="input-group-btn">
                            <a class="btn btn-default q_so" ><i class="fa fa-search"></i></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput"></label>  
                        <div class="col-md-4">
                            <button type="submit" class="btn  btn-primary">Query&nbsp;<i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </form>
    <div  id="batch-add-adjustmen">
    <form action="act/batch_add_adjustmen.php" method="POST" id="detail" class="form-horizontal" role="form">
        <fieldset>
            <div id="message_batch" style="display: none;" class="alert alert-danger">asasd</div>
            <div class="col-sm-12 colsm-offset-4">
                <div class="panel panel-primary">    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Plant ID :</label>  
                            <div class="col-md-6">
                                <label class="plant_id control-label" for="textinput"></label>
                                <input type="hidden" id="plant_id" value="" name="plant_id"/>
                                <input type="hidden" id="so_no" value="" name="so_no"/>
                                <input type="hidden" id="docket_no" value="" name="docket_no"/> 
                            </div>
                        </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Docket No :</label>  
                            <div class="col-md-6">
                                <label id="docket" for="textinput"></label>
                            </div>
                        </div>
                    </div> 
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Cust :</label>  
                            <div class="col-md-6">
                                <label id="cust_id" for="textinput"></label>
                            </div>
                        </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Proj :</label>  
                            <div class="col-md-6" style="padding-top: 5px;">
                                <label id="proj_no" for="textinput"></label>
                            </div>
                        </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Proj Loc :</label>  
                            <div class="col-md-6">
                                <label id="proj_loc" for="textinput"></label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


             <div class="row">
                <div class="col-md-12">
                <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-5">    
                        <div class="panel panel-primary">
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">FA :</label>  
                                        <div class="col-md-6">
                                           <input id="fa" value="" name="fa" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">CementOPC1:</label>  
                                        <div class="col-md-6">
                                            <input type="text"  id="cementtp1" value="" name="cementtp1" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">CementOPC2 :</label>  
                                        <div class="col-md-6">
                                            <input type="text"  id="cementtp2" value="" name="cementtp2" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">CEMENTVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="cementvas" value="" name="cementvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>

                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG1 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg1" value="" name="agg1" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG2 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg2" value="" name="agg2" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                            
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="control-label col-md-6 " for="textinput">AGG3 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg3" value="" name="agg3" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG1VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg1vas" value="" name="agg1vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG2VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg2vas" value="" name="agg2vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG3VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg3vas" value="" name="agg3vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">MSAND :</label>  
                                    <div class="col-md-6">
                                           <input id="msand" value="" name="msand" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">SAND :</label>  
                                    <div class="col-md-6">
                                           <input id="sand" value="" name="sand" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">SANDVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="sandvas" value="" name="sandvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">WATER :</label>  
                                    <div class="col-md-6">
                                           <input id="water" value="" name="water" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                            
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">STONEDUST:</label>  
                                    <div class="col-md-6">
                                           <input id="stonedust" value="" name="stonedust" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                         <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">STONEDUSTVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="stonedustvas" value="" name="stonedustvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                         
                        </div>
                    </div>    
                </div>
                <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-6">    
                        <div class="panel panel-primary">
            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">RT6P :</label>  
                        <div class="col-md-6">
                            <input id="rt6p" value="" name="rt6p" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
                        </div>
            </div>
            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">VIS 1003 :</label>  
                        <div class="col-md-6">
                            <input id="vis1003" value="" name="vis1003" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                        </div>
            </div>
			<div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">FLBRPF34 :</label>  
                        <div class="col-md-6">
                            <input id="flbrpf34" value="" name="flbrpf34" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                        </div>
            </div>
             <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">VIS 3660 LR :</label>  
                        <div class="col-md-6">
                            <input id="vis3660lr" value="" name="vis3660lr" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
            </div>
            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GEN CB10 :</label>  
                        <div class="col-md-6">
                            <input id="gencb10" value="" name="gencb10" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
            </div>

            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT CON-M :</label>  
                        <div class="col-md-6">
                            <input id="sbtconm" value="" name="sbtconm" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT JM-9 :</label>  
                        <div class="col-md-6">
                            <input id="sbtjm9" value="" name="sbtjm9" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT PCA-8S :</label>  
                        <div class="col-md-6">
                            <input id="sbtpca8s" value="" name="sbtpca8s" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">STEEL DUST :</label>  
                        <div class="col-md-6">
                            <input id="stldust" value="" name="stldust" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>


                        </div>
                    </div>
                </div>    
    <div class="tabbable tabbable-custom boxless tabbable-reversed">
         <div class="col-sm-4 colsm-offset-6">    
             <div class="panel panel-primary">
          
          
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">P83 :</label>  
                        <div class="col-md-6">
                            <input id="p83" value="" name="p83" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SIKAMENT 183 :</label>  
                        <div class="col-md-6">
                            <input id="SIKAMENT183" value="" name="SIKAMENT183" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                        </div>
                </div>
                
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GENR 8212  :</label>  
                        <div class="col-md-6">
                            <input id="genr8212" value="" name="genr8212" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GET 702 :</label>  
                        <div class="col-md-6">
                            <input id="get702" value="" name="get702" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GENB 1714 :</label>  
                        <div class="col-md-6">
                            <input id="genb1714" value="" name="genb1714" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">FLBNF-15 :</label>  
                        <div class="col-md-6">
                            <input id="flbnf15" value="" name="flbnf15" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">FLBPD-19 :</label>  
                        <div class="col-md-6">
                            <input id="flbpd19" value="" name="flbpd19" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                 </div>
              </div>
         </div>
     </div>    
                </div> 
                <div class="component">
                    <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">Seal No:</label>  
                            <div class="col-md-4">
                                <input id="seal_no" value="" name="seal_no" type="text" placeholder="Seal No" class="form-control input-md">
                            </div>
                      </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput"></label>  
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-lg btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </fieldset>
    </form>
    </div>
    </div><!-- Untuk Tab pertama berikan div class=”active” agar pertama kali halaman di load content langsung active-->
    
	<div class="tab-pane" id="profile">
        <div class="container-fluid">
            <form action="act/batch_request_nodocket_act.php" method="post" enctype="multipart/form-data" id="query_not_d" class="form-horizontal " role="form"> 
                <div class="form-group"><h1>Delivery Schedule Adjustment Not Docket</h1></div>
                <div id="msg_docket" style="display: none;"></div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Docket No :</label>  
                        <div class="col-md-4">
                                <input id="docket" value="" name="docket" type="text" placeholder="Docket No" class="form-control input-md">
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">SO No :</label>  
                        <div class="col-md-4">
                            <input id="so" value="" name="so" type="text" placeholder="SO Number" class="form-control input-md">
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Schedule Date :</label>  
                        <div class="col-md-4">
                            <input id="delvdate" value="" name="sched_date" type="text" placeholder="Schedule Date" class="form-control input-md">
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput"></label>  
                        <div class="col-md-4">
                            <button type="submit" class="btn  btn-primary">Query&nbsp;<i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            <div  id="batch-add-no-docket">
                <form action="act/batch_add_nd.php" method="POST" id="detail_not" class="form-horizontal" role="form">
                    <fieldset>
                        <div id="message_batch_not" style="display: none;" class="alert alert-danger">asasd</div>
                        <div class="col-sm-12 colsm-offset-4">
                            <div class="panel panel-primary">    
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Plant ID :</label>  
                                        <div class="col-md-6">
                                            <label class="plant_id control-label" for="textinput"></label>
                                            <input type="hidden" id="plant_id" value="" name="plant_id"/>
                                            <input type="hidden" id="so" value="" name="so_no"/>
                                            <input type="hidden" id="docket" value="" name="docket_no"/> 
                                        </div>
                                    </div>
                                </div>
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Docket No :</label>  
                                        <div class="col-md-6">
                                            <label id="dn" for="textinput"></label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Cust :</label>  
                                        <div class="col-md-6">
                                            <label id="cust" for="textinput"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Proj :</label>  
                                        <div class="col-md-6" style="padding-top: 5px;">
                                            <label id="proj" for="textinput"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Proj Loc :</label>  
                                        <div class="col-md-6">
                                            <label id="loc" for="textinput"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="component">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Product Code :</label>  
                                        <div class="col-md-6">
                                            <label id="pro" for="textinput"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

			<div class="row">
                <div class="col-md-12">
                <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-6">    
                        <div class="panel panel-primary">
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">FA :</label>  
                                        <div class="col-md-6">
                                           <input id="fa" value="" name="fa" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                           <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">CementOPC1:</label>  
                                        <div class="col-md-6">
                                            <input type="text"  id="cementtp1" value="" name="cementtp1" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">CementOPC2 :</label>  
                                        <div class="col-md-6">
                                            <input type="text"  id="cementtp2" value="" name="cementtp2" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">CEMENTVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="cementvas" value="" name="cementvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                            
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG1 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg1" value="" name="agg1" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG2 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg2" value="" name="agg2" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                            
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="control-label col-md-6 " for="textinput">AGG3 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg3" value="" name="agg3" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG1VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg1vas" value="" name="agg1vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG2VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg2vas" value="" name="agg2vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG3VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg3vas" value="" name="agg3vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">MSAND :</label>  
                                    <div class="col-md-6">
                                           <input id="msand" value="" name="msand" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">SAND :</label>  
                                    <div class="col-md-6">
                                           <input id="sand" value="" name="sand" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">SANDVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="sandvas" value="" name="sandvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">WATER :</label>  
                                    <div class="col-md-6">
                                           <input id="water" value="" name="water" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                            
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">STONEDUST:</label>  
                                    <div class="col-md-6">
                                           <input id="stonedust" value="" name="stonedust" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">STONEDUSTVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="stonedustvas" value="" name="stonedustvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>                   
                        </div>
                    </div>    
                </div>
				
				
                <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-6">    
                        <div class="panel panel-primary">
				<div class="form-group">
							<label class="col-md-6 control-label" for="textinput">RT6P :</label>  
							<div class="col-md-6">
								<input id="rt6p" value="" name="rt6p" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
							</div>
				</div>
				<div class="form-group">
							<label class="col-md-6 control-label" for="textinput">VIS 1003 :</label>  
							<div class="col-md-6">
								<input id="vis1003" value="" name="vis1003" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
							</div>
				</div>
                <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GEN CB10 :</label>  
                        <div class="col-md-6">
                            <input id="gencb10" value="" name="gencb10" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                </div>
				<div class="form-group">
							<label class="col-md-6 control-label" for="textinput">FLBRPF34 :</label>  
							<div class="col-md-6">
								<input id="flbrpf34" value="" name="flbrpf34" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
							</div>
				</div>
					<div class="form-group">
								<label class="col-md-6 control-label" for="textinput">VIS 3660 LR :</label>  
								<div class="col-md-6">
									<input id="vis3660lr" value="" name="vis3660lr" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
								</div>
					</div>  

                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT CON-M :</label>  
                        <div class="col-md-6">
                            <input id="sbtconm" value="" name="sbtconm" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT JM-9 :</label>  
                        <div class="col-md-6">
                            <input id="sbtjm9" value="" name="sbtjm9" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT PCA-8S :</label>  
                        <div class="col-md-6">
                            <input id="sbtpca8s" value="" name="sbtpca8s" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">STEEL DUST :</label>  
                        <div class="col-md-6">
                            <input id="stldust" value="" name="stldust" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>

                                     
                    </div>
                        </div>
                    </div>
					
					<div class="tabbable tabbable-custom boxless tabbable-reversed">
					<div class="col-sm-4 colsm-offset-6">    
						<div class="panel panel-primary">
				  
				   
						<div class="form-group">
									<label class="col-md-6 control-label" for="textinput">P83 :</label>  
									<div class="col-md-6">
										<input id="p83" value="" name="p83" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
									</div>
						</div>
						<div class="form-group">
									<label class="col-md-6 control-label" for="textinput">SIKAMENT 183 :</label>  
									<div class="col-md-6">
										<input id="SIKAMENT183" value="" name="SIKAMENT183" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
									</div>
						</div>
						<div class="form-group">
									<label class="col-md-6 control-label" for="textinput">GENR 8212  :</label>  
									<div class="col-md-6">
										<input id="genr8212" value="" name="genr8212" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
									</div>
						</div>
								<div class="form-group">
									<label class="col-md-6 control-label" for="textinput">GET 702 :</label>  
									<div class="col-md-6">
										<input id="get702" value="" name="get702" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-6 control-label" for="textinput">GENB 1714 :</label>  
									<div class="col-md-6">
										<input id="genb1714" value="" name="genb1714" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
									</div>
								</div>
								   <div class="form-group">
									<label class="col-md-6 control-label" for="textinput">FLBNF-15 :</label>  
									<div class="col-md-6">
										<input id="flbnf15" value="" name="flbnf15" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-6 control-label" for="textinput">FLBPD-19 :</label>  
									<div class="col-md-6">
										<input id="flbpd19" value="" name="flbpd19" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
									</div>
								</div>
									</div>
							 
								 </div>    
				</div>
                </div>    
				  
            </div>
                            <div class="component">
                                <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Seal No:</label>  
                                        <div class="col-md-4">
                                            <input id="seal_no" value="" name="seal_no" type="text" placeholder="Seal No" class="form-control input-md">
                                        </div>
                                  </div>
                            </div>
                            <div class="component">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput"></label>  
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-lg btn-primary">Add</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
            </div>
        </div>
     </div>
    <?php }?>
    <div class="tab-pane" id="settings">
    <div class="container-fluid">
            <form action="act/batch_add_self_usage.php" method="post" enctype="multipart/form-data" id="query_self_usage" class="form-horizontal " role="form"> 
                <div class="form-group">
                        <h1>Delivery Schedule Self Usage</h1>
                </div>
                <div id="message_su" style="display: none;">asasd</div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Cust :</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="cust" value="" name="cust" placeholder="Cust Name" class="form-control input-md">
                            </div>    
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Proj  :</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="proj" value="" name="proj" placeholder="Proj Name" class="form-control input-md">
                            </div>    
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Proj Loc  :</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="proj_loc" value="" name="proj_loc" placeholder="Proj Address" class="form-control input-md">
                            </div>    
                        </div>
                    </div>
            <div class="row">
            <div class="col-md-12">
                <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-5">    
                        <div class="panel panel-primary">
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">FA :</label>  
                                        <div class="col-md-6">
                                           <input id="fa" value="" name="fa" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                           <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">CementOPC1:</label>  
                                        <div class="col-md-6">
                                            <input type="text"  id="cementtp1" value="" name="cementtp1" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-6 control-label" for="textinput">CementOPC2 :</label>  
                                        <div class="col-md-6">
                                            <input type="text"  id="cementtp2" value="" name="cementtp2" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                        </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">CEMENTVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="cementvas" value="" name="cementvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                            
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG1 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg1" value="" name="agg1" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG2 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg2" value="" name="agg2" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                            
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="control-label col-md-6 " for="textinput">AGG3 :</label>  
                                    <div class="col-md-6">
                                           <input id="agg3" value="" name="agg3" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG1VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg1vas" value="" name="agg1vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG2VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg2vas" value="" name="agg2vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">AGG3VAS :</label>  
                                    <div class="col-md-6">
                                           <input id="agg3vas" value="" name="agg3vas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">MSAND :</label>  
                                    <div class="col-md-6">
                                           <input id="msand" value="" name="msand" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">SAND :</label>  
                                    <div class="col-md-6">
                                           <input id="sand" value="" name="sand" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">SANDVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="sandvas" value="" name="sandvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">WATER :</label>  
                                    <div class="col-md-6">
                                           <input id="water" value="" name="water" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                            
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">STONEDUST:</label>  
                                    <div class="col-md-6">
                                           <input id="stonedust" value="" name="stonedust" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label class="col-md-6 control-label" for="textinput">STONEDUSTVAS :</label>  
                                    <div class="col-md-6">
                                           <input id="stonedustvas" value="" name="stonedustvas" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                                    </div>
                        </div>
                        
                        </div>
                    </div>    
                </div>
                <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-6">    
                        <div class="panel panel-primary">
            
					<div class="form-group">
								<label class="col-md-6 control-label" for="textinput">RT6P :</label>  
								<div class="col-md-6">
									<input id="rt6p" value="" name="rt6p" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
								</div>
					</div>
					<div class="form-group">
								<label class="col-md-6 control-label" for="textinput">VIS 1003:</label>  
								<div class="col-md-6">
									<input id="vis1003" value="" name="vis1003" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
								</div>
					</div>
					<div class="form-group">
								<label class="col-md-6 control-label" for="textinput">FLB RPF34  :</label>  
								<div class="col-md-6">
									<input id="flbrpf34" value="" name="flbrpf34" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
								</div>
					</div>                   
                     <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">VIS 36601R :</label>  
                        <div class="col-md-6">
                            <input id="vis3660lr" value="" name="vis3660lr" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GEN CB10 :</label>  
                        <div class="col-md-6">
                            <input id="gencb10" value="" name="gencb10" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT CON-M :</label>  
                        <div class="col-md-6">
                            <input id="sbtconm" value="" name="sbtconm" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT JM-9 :</label>  
                        <div class="col-md-6">
                            <input id="sbtjm9" value="" name="sbtjm9" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SBT PCA-8S :</label>  
                        <div class="col-md-6">
                            <input id="sbtpca8s" value="" name="sbtpca8s" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">STEEL DUST :</label>  
                        <div class="col-md-6">
                            <input id="stldust" value="" name="stldust" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                      
                        </div>
                    </div>
                </div>    
                   <div class="tabbable tabbable-custom boxless tabbable-reversed">
                     <div class="col-sm-4 colsm-offset-6">    
                        <div class="panel panel-primary">
          
         
            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">P83 :</label>  
                        <div class="col-md-6">
                            <input id="p83" value="" name="p83" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
                        </div>
            </div>
            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">SIKAMENT 183 :</label>  
                        <div class="col-md-6">
                            <input id="SIKAMENT183" value="" name="SIKAMENT183" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">
                        </div>
            </div>
            
            <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GENR 8212  :</label>  
                        <div class="col-md-6">
                            <input id="genr8212" value="" name="genr8212" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                
                        </div>
            </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GET 702 :</label>  
                        <div class="col-md-6">
                            <input id="get702" value="" name="get702" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">GENB 1714 :</label>  
                        <div class="col-md-6">
                            <input id="genb1714" value="" name="genb1714" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                       <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">FLBNF-15 :</label>  
                        <div class="col-md-6">
                            <input id="flbnf15" value="" name="flbnf15" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label" for="textinput">FLBPD-19 :</label>  
                        <div class="col-md-6">
                            <input id="flbpd19" value="" name="flbpd19" type="text" placeholder="" class="form-control input-md" onkeypress="return isNumberKey(event)">                                                                
                        </div>
                    </div>
                    
            </div>    
            </div>
                    
                    </div>    

 </div>
                    <div class="component">
                        <div class="form-group">
                                <label class="col-md-6 control-label" for="textinput">Seal No:</label>  
                                <div class="col-md-4">
                                    <input id="seal_no" value="" name="seal_no" type="text" placeholder="Seal No" class="form-control input-md">
                                </div>
                          </div>
                    </div>
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput"></label>  
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-lg btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>                                
</div>
    
     <?php
     include "inc/version.php";
     include "ajax/pop_up_query_adjustman.php";
     include "ajax/pop_up_query_cust.php";
    ?>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/adj.js"></script>
    <script type="text/javascript">
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       function validateAddress(){
            var TCode = document.getElementById('address').value;

            if( /[^a-zA-Z0-9\-\/]/.test( TCode ) ) {
                alert('Input is not alphanumeric');
                return false;
            }

            return true;     
        }
    </script>
</body>
</html>
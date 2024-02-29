<?php
session_start();
//kode menu
$_SESSION['menu'] = "batch_request_Adjustment";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
include_once './inc/constant.php';
include "db.php";
?>

<!--
privileges : batcher (no need to login)
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
	    #plant_id{ margin:0px auto; width:400px; }
	    form{ margin:20px auto;width:50%; }
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
<!--    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/> -->
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
            $query_war = "SELECT DISTINCT `chart_no` FROM `mix_package_composition` WHERE `code_trans` = 'Y'";   
            $result = mysql_query($query_war);
            if(!$result) die(mysql_error());
            $status_list = true;
            if($status_list == true){
                    while($row = mysql_fetch_array($result)){
                        $chartno = $row['chart_no'];
                        if($chartno == '1' ){
        ?>
                                <div align="right" style="color:#000099" ><h4>1 / (Norm)</h4></div>
                            <?php
                        } else {                
                            ?>
                                <div align="right" style="color:#FF0000"><h4>2 / (NFA)</h4></div>
                            <?php
                          }
                    }
            }
                            ?>
        
<div class="container-fluid">
        
        <form action="ajax/batch_request_adjustment_act.php" method="post" enctype="multipart/form-data" id="query" class="form-horizontal " role="form"> 
                    <div class="form-group">
                    <h1>Delivery Schedule Adjustment</h1>
                    </div>
	      	<div id="messages" class="">asasd</div>
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
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput"> Batch Volume :</label>  
                            <div class="col-md-8">
                                <label class="control-label" id="today_b_request" for="textinput"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable tabbable-custom boxless tabbable-reversed">
                         <div class="col-sm-6 colsm-offset-5">    
                            <div class="panel panel-primary">
                                <div class="form-group">
                                            <label class="col-md-4 control-label" for="textinput">FA :</label>  
                                            <div class="col-md-4">
                                                <select id="fa" name="fa" class="input-md form-control">
                                                    <option value="0">   0 </option>
                                                    <option value="5">   5  </option>
                                                    <option value="10">  10  </option>
                                                    <option value="15">  15  </option>
                                                    <option value="20">  20  </option>
                                                    <option value="25">  25  </option>
                                                    <option value="30">  30  </option>
                                                    <option value="35">  35  </option>
                                                    <option value="40">  40  </option>
                                                    <option value="45">  45  </option>
                                                    <option value="50">  50  </option>
                                                    <option value="55">  55  </option>
                                                    <option value="60">  60  </option>
                                                    <option value="65">  65  </option>
                                                    <option value="70">  70  </option>
                                                </select>
                                            </div>
                                </div>
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">Cement :</label>  
                                        <div class="col-md-4">
                                            <select id="cement" name="cement" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="10">  10  </option>
                                                <option value="20">  20  </option>
                                                <option value="30">  30  </option>
                                                <option value="40">  40  </option>
                                                <option value="50">  50  </option>
                                                <option value="60">  60  </option>
                                                <option value="70">  70  </option>
                                                <option value="80">  80  </option>
                                                <option value="90">  90  </option>
                                                <option value="100">  100  </option>
                                                <option value="150">  150  </option>
                                                <option value="200">  200  </option>
                                                <option value="250">  250  </option>
                                                <option value="300">  300  </option>
                                                <option value="350">  350  </option>
                                            </select>
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">AGG1 :</label>  
                                        <div class="col-md-4">
                                            <select id="agg1" name="agg1" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="100">  100  </option>
                                                <option value="200">  200  </option>
                                                <option value="300">  300  </option>
                                                <option value="400">  400  </option>
                                                <option value="500">  500  </option>
                                            </select>
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">AGG2 :</label>  
                                        <div class="col-md-4">
                                            <select id="agg2" name="agg2" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="100">  100  </option>
                                                <option value="200">  200  </option>
                                                <option value="300">  300  </option>
                                                <option value="400">  400  </option>
                                                <option value="500">  500  </option>
                                            </select>
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="control-label col-md-4 " for="textinput">AGG3 :</label>  
                                        <div class="col-md-4">
                                            <select id="agg3" name="agg3" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="100">  100  </option>
                                                <option value="200">  200  </option>
                                                <option value="300">  300  </option>
                                                <option value="400">  400  </option>
                                                <option value="500">  500  </option>
                                            </select>
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">MSAND :</label>  
                                        <div class="col-md-4">
                                            <select id="msand" name="msand" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="100">  100  </option>
                                                <option value="200">  200  </option>
                                                <option value="300">  300  </option>
                                                <option value="400">  400  </option>
                                                <option value="500">  500  </option>
                                            </select>
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">SAND :</label>  
                                        <div class="col-md-4">
                                            <select id="sand" name="sand" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="100">  100  </option>
                                                <option value="200">  200  </option>
                                                <option value="300">  300  </option>
                                                <option value="400">  400  </option>
                                                <option value="500">  500  </option>
                                            </select>
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="textinput">WATER :</label>  
                                        <div class="col-md-4">
                                            <select id="water" name="water" class="form-control">
                                                <option value="0"> 0 </option>
                                                <option value="10">  10  </option>
                                                <option value="20">  20  </option>
                                                <option value="30">  30  </option>
                                                <option value="40">  40  </option>
                                                <option value="50">  50  </option>
                                                <option value="60">  60  </option>
                                                <option value="70">  70  </option>
                                                <option value="80">  80  </option>
                                                <option value="90">  90  </option>
                                                <option value="100">  100  </option>
                                            </select>
                                        </div>
                            </div>                    
             <div class="tabbable tabbable-custom boxless tabbable-reversed">
                    <div class="col-sm-6 colsm-offset-6">    
                        <div class="panel panel-primary">
                <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">RT6P :</label>  
                            <div class="col-md-4">
                                <select id="rt6p" name="rt6p" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option>
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
                <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">VIS 1003:</label>  
                            <div class="col-md-4">
                                <select id="vis1003" name="vis1003" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option>
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
 <!--               <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">flbrpf34 :</label>  
                            <div class="col-md-4">
                                <select id="flbrpf34" name="flbrpf34" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option>
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div> -->
               <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">FLBRPF34  :</label>  
                            <div class="col-md-4">
                                <select id="flbrpf34" name="flbrpf34" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option>
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div> 
				<div class="form-group">
                            <label class="col-md-4 control-label" for="textinput"> p83 :</label>  
                            <div class="col-md-4">
                                <select id="p83" name="p83" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option>
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
                <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">SIKAMENT183 :</label>  
                            <div class="col-md-4">
                                <select id="SIKAMENT183" name="SIKAMENT183" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
				 <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">genr8212 :</label>  
                            <div class="col-md-4">
                                <select id="genr8212" name="genr8212" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
				 <div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">get702 :</label>  
                            <div class="col-md-4">
                                <select id="get702" name="get702" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
				<div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">genb1714:</label>  
                            <div class="col-md-4">
                                <select id="genb1714" name="genb1714" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
<!--				<div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">flbnf15:</label>  
                            <div class="col-md-4">
                                <select id="flbnf15" name="flbnf15" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div> -->
				<div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">flbnf15:</label>  
                            <div class="col-md-4">
                                <select id="flbnf15" name="flbnf15" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
                            </div>
                </div>
				<div class="form-group">
                            <label class="col-md-4 control-label" for="textinput">flbpd19:</label>  
                            <div class="col-md-4">
                                <select id="flbpd19" name="flbpd19" class="form-control">
                                    <option value="0"> 0 </option>
                                    <option value="0.5">  0.5  </option>
                                    <option value="1">  1  </option>
                                    <option value="1.5">  1.5  </option>
                                    <option value="2">  2  </option>
                                    <option value="2.5">  2  </option>
                                    <option value="3">  3  </option>
                                    <option value="3.5">  3.5  </option> 
                                    <option value="4">  4  </option>
                                    <option value="4.5"> 4.5  </option>
                                    <option value="5">  5  </option>
                                </select>
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
                        <label class="col-md-1 control-label" for="textinput"></label>  
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-lg btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </fieldset>
    	</form>
    </div>
    
     <?php
     include "inc/version.php";
     include "ajax/pop_up_query_adjustman.php";
    ?>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/jquery-1.10.2.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        (function() {
            var base_url    = location.origin+"/mis/";
            $("form#query").on('click', '.q_so', function(e){
                e.preventDefault(e);
                //var loading = $(".loading");
                loading.show();
                jQuery.ajax({
                    url: base_url+"ajax/batch_request_adjustment_docket.php",
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
                            loading.hide();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            loading_object.hide(); 
                            alert(thrownError);
                        }
                });
            });
            
            $("#test").on('click', '#add_docket', function(e){
                //var docket_no = "";
                var docket_no = $('input[name="docket"]:checked').val()
                $("#docket_no").val(docket_no);
                
            });

            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
 //           var delv_date = $('#delv_date').val();
            var loading = $(".loading");
            
            $('form#query').ajaxForm({
                beforeSend: function() {
                    loading.show();
                },
                complete: function(xhr) {
                    $('div.loading').hide();
                    console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if(obj.status == '1'){
                        $('div#messages').html("<p>"+obj.msg+"</p>");
                        $('div#messages').removeClass("n_error").addClass("n_ok");
                        $('input#plant_id').val(obj.plant_id);
                        $('.plant_id').html(obj.plant_id);
                        $('input#so_no').val(obj.so_no);
                        $('input#docket_no').val(obj.docket_no);

                        $('#docket').html(obj.docket);
                        $('#today_b_request').html(obj.today_b_request);
                        $('#out_volume').html(obj.out_volume);
                        $('#cust_id').html(obj.cust_id+", "+obj.cust_name);
                        $('#cust_name').html(obj.cust_name);
                        $('#proj_no').html(obj.proj_no+", "+obj.proj_address);
                        $('#proj_name').html();
                        $('#proj_loc').html(obj.proj_loc);
                        $('#prod_code').html(obj.product_code);
//                        $('#varian').html(obj.varian)
                        
                        
                        $('div#messages').hide();
                        $('div#batch-add-adjustmen').hide();
                        $('div#messages').fadeIn();
                        $('div#batch-add-adjustmen').slideDown();
                    }
                    else if(obj.status == '0'){
                        $('div#messages').hide();$('div#batch-add-adjustmen').slideUp();
                        $('div#messages').removeClass("n_ok").addClass("n_error");
                        $('div#messages').html("<p>"+obj.msg+"!</p>");
                        $('div#messages').fadeIn();$('div#detail').hide();
                    }
                }
            });
            $('form#detail').ajaxForm({
                beforeSend: function() {
                        loading.show();
                    },
                complete: function(xhr) {
                 console.log(xhr.responseText);
                    $('div.loading').hide();
                    loading.show();
                    var obj = jQuery.parseJSON(xhr.responseText);
                    console.log(obj.row);
                    if(obj.status == '1'){
                        $('div#message_batch').html("<p>"+obj.msg+"</p>");
                        $('div#message_batch').removeClass("n_error").addClass("n_ok");
                        $('div#message_batch').fadeIn();
                        $('input#docket_no').val('');
                        $('#today_b_request').html(obj.today_b_request);
//                        $('#out_volume').html(obj.out_volume);
                        $('div#detail').hide();
//                        $('#kueri').hide();goToMessage();
                    }
                    else if(obj.status == '0'){
                        $('div#message_batch').hide();
                        $('div#message_batch').removeClass("n_ok").addClass("n_error");
                        $('div#message_batch').html("<p>"+obj.msg+"!</p>");
                        $('div#message_batch').fadeIn();goToMessage();
                    }
                    loading.hide();
                }
            });
            
            

            function goToMessage(){
                $("body, html").animate({ 
                    scrollTop: $("#detail").offset().top 
                }, 600);
            }
        })();
    </script>
</body>
</html>
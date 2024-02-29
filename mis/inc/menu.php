
<nav id="myNavbar" class="navbar navbar-default" role="navigation" >
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" data-toggle="modal" data-target="#myModal" href="#">DEC Application</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
<!--                <li class="dropdown <?php if($_SESSION["menu"] == "home"){?>active<?php }?>">
                    <a class="navbar-nav" href="index.php">Home</a>
                </li> -->
                <?php
                    $ds_array = array("upload_delivery_schedule","list_delivery_schedule"); //mengatur css active pada menu batch transaction
                ?>
                <?php if(isset($object) && ($object->group_id == 1 || $object->group_id == 2 || $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 5 || $object->group_id == 6 || $object->group_id == 7)){ // production admin ?>
                <li class="dropdown <?php if(in_array($_SESSION['menu'],$ds_array)){echo"active";}?>">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Delivery Schedule&nbsp;<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php  if(isset($object) && ($object->group_id == 4 || $object->group_id == 5)){ // production admin ?>
                        <li <?php if($_SESSION["menu"] == "upload_delivery_schedule"){?>class="active" <?php }?>><a href="frm_delivery_schedule.php" title="Memasukkan data delivery schedule ke DEC">Upload Data</a></li> 
                           <?php  }?>
                                <?php  if(isset($object) && ($object->group_id == 3)){ // production admin ?>
                       
                            <li <?php if($_SESSION["menu"] == "batch_request_adjustment"){?>class="active" <?php }?>><a href="<?php echo BASE_URL?>bom.php?menu=batch_reques_adjustment_not" title="Batch Reques Adjustment">Batch Reques Adjustment</a></li> 
                            <?php  }?>
                          
                             <?php  if(isset($object) && ($object->group_id == 4 || $object->group_id == 5)){ // production admin ?>
                        <li <?php if($_SESSION["menu"] == "schedulemanual"){?>class="active" <?php }?>><a href="schedulemanual.php" title="Memasukkan data delivery schedule ke DEC">Add Schedule</a></li> 
                         <li <?php if($_SESSION["menu"] == "backschedule"){?>class="active" <?php }?>><a href="backschedule.php" title="Mengembalikan request schedule ">Back Schedule Request</a></li> 
                         
                            <?php  }?>
                               <?php  if(isset($object) && ($object->group_id == 6 || $object->group_id == 4)){ // production admin ?>
                      
                             <li <?php if($_SESSION["menu"] == "batch_request_adjustment"){?>class="active" <?php }?>><a href="./bom.php?menu=batch_reques_adjustment_not" title="Batch Reques Adjustment">Batch Reques Adjustment</a></li> 
                            <?php  }?>

                        <li <?php if($_SESSION["menu"] == "list_delivery_schedule"){?>class="active" <?php }?>><a href="list_delivery_schedule.php" title="List Delivery Schedule">List</a></li> <!--
                        <li <?php if($_SESSION["menu"] == "list_so"){?>class="active" <?php }?>><a href="list_so.php" title="List SO Delivery Schedule">List So</a></li> -->
                       
                    </ul>
                </li>
                <?php }?> <?php
                    //mengatur css active pada menu batch transaction
                    $dr_array = array("batch_request","batch_request_adjustment");
                ?>
               
                <?php
                    //mengatur css active pada menu batch transaction
                    $bt_array = array("reset","split","full_export","download","acceptance","list_data","full_export_akumulasi","list_data_so");
                ?>
                <?php if(isset($object) && ($object->group_id == 1 || $object->group_id == 2 || $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 5 || $object->group_id == 6 || $object->group_id == 7)){ // production admin ?>
                <li class="dropdown <?php if(in_array($_SESSION['menu'],$bt_array)){echo"active";}?>">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Batch Transaction <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        if(isset($object) && ($object->group_id == 2 || $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 6    )){ // production admin
                        ?>
                    <!--    <li><a href="upload_batch_transaction.php" title="Split file into  docket and material issue">Split File</a></li> -->

                        <li <?php if($_SESSION["menu"] == "full_export"){?>class="active" <?php }?>><a href="batch_transaction_export.php" title="Export full batch transaction report">Export Full Normal</a></li> <?php } ?>
                          <?php
                        if(isset($object) && ($object->group_id == 2 || $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 6   )){ // production admin
                        ?>
                        <li <?php if($_SESSION["menu"] == "full_export_adjustment"){?>class="active" <?php }?>><a href="batch_transaction_export_adjustment.php" title="Export full batch transaction report adjustment">Export Full Adjustment</a></li> 
                        <li <?php if($_SESSION["menu"] == "full_export_akumulasi"){?>class="active" <?php }?>><a href="./bom.php?menu=export_akumulasi" title="Export full batch transaction report akumulasi">Export Full Akumulasi</a></li>   
                        <?php } ?>
                         <?php
                        if(isset($object) && ( $object->group_id == 4 || $object->group_id == 6 )){ // production admin
                        ?>
                        <li <?php if($_SESSION["menu"] == "acceptance"){?>class="active" <?php }?>><a href="return_truck.php" title="Batch Transaction Acceptance">Acceptance</a></li>
                        <li <?php if($_SESSION["menu"] == "download"){?>class="active" <?php }?>><a href="./bom.php?menu=exsport_csv" title="Download Docket & Material Issue">Download</a></li>
                         <?php } ?>
                        <?php
                        if(isset($object) && ($object->group_id == 2||$object->group_id == 3||$object->group_id == 4||$object->group_id ==5 ||$object->group_id == 6 ||$object->group_id ==7)){ // production admin
                        ?>
                      <li <?php if($_SESSION["menu"] == "list_data_so"){?>class="active" <?php }?>><a href="batch_transaction_so.php" title="List batch transaction Per SO">List Data SO</a></li>
                        <li <?php if($_SESSION["menu"] == "list_data"){?>class="active" <?php }?>><a href="list_data_batch_trans.php" title="List Batch transaction data">List Data</a></li>
                        <?php } ?>
                           <?php
                        if(isset($object) && ($object->group_id == 1)){ // production admin
                        ?>
                    
                        <li <?php if($_SESSION["menu"] == "list_data"){?>class="active" <?php }?>><a href="list_data_batch_trans.php" title="List Batch transaction data">List Data</a></li>
                        <?php } ?>
                        <?php
                        if(isset($object) && (  $object->group_id == 3 || $object->group_id == 4 || $object->group_id == 6)){ // production admin
                        ?>
                        <li <?php if($_SESSION["menu"] == "reset"){?>class="active" <?php }?>><a href="list_batch_trans.php" title="Reset Batch Transaction">Reset</a></li>                         

                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>                
                <?php
                //mengatur css active pada menu batch transaction
                $bom_array = array("bom_list","upload_bom");
                ?>
                <?php if(isset($object) && ($object->group_id == 1 || $object->group_id == 4  || $object->group_id == 6 || $object->group_id == 2 || $object->group_id == 7)){  ?>
                <li class="dropdown <?php if(in_array($_SESSION['menu'],$bom_array)){echo"active";}?>">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">BOM &nbsp;<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="./bom.php?filter=&menu=mix_list">Mix Design</a></li>
                          <?php
                        if(isset($object) && ($object->group_id==1 || $object->group_id==4 || $object->group_id == 6)){
                        ?>
                           <li><a href="./bom.php?menu=transfer">Transfer BOM</a></li> 
                           <?php
                        }
                        ?>
                         <?php
                        if(isset($object) && ($object->group_id==1 || $object->group_id==4 )){
                        ?>
                          
                        <li><a href="./bom.php">Upload Mix Design</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
               
                <?php } ?>                
                <?php
                if(isset($_SESSION['login'])){
                    if(isset($object) && $object->group_id == 4 || $object->group_id == 1 ){ // administrator
                ?>
                <?php
                //mengatur css active pada menu batch transaction
                $pe_array = array("user_list","user_add","list_bp","list_acceptance","list_bom");
                ?>
                <li class="dropdown <?php if(in_array($_SESSION['menu'],$pe_array)){echo"active";}?>" >
                    <a href="" data-toggle="dropdown" class="dropdown-toggle">Pengaturan <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                       
                        <!--<li><a href="schedule_list.php">Schedule</a></li>-->
                        <?php
                        if(isset($object) && $object->group_id == 4){ // super user
                        ?>
                         <li <?php if($_SESSION["menu"] == "user_list"){?>class="active" <?php }?>><a href="user_list.php"><i class="fa fa-users"></i>&nbsp;Users</a></li>
                        <li <?php if($_SESSION["menu"] == "list_bp"){?>class="active" <?php }?>><a href="list_bp.php" title="List All Machine"><i class="fa fa-rocket"></i>&nbsp;Batching Plant</a></li>
                        <li <?php if($_SESSION["menu"] == "list_acceptance"){?>class="active" <?php }?>><a href="list_acceptance.php" title="Pengaturan Batch Transaction Acceptance Code"><i class="fa fa-gear"></i>&nbsp;Acceptance Code</a></li>
                       <li <?php if($_SESSION["menu"] == "list_bom"){?>class="active" <?php }?>><a href="list_bom.php" title="Pengaturan BOM Code"><i class="fa fa-gear"></i>&nbsp;BOM Code</a></li>
                        <?php
                        }
                        ?>
                          <?php
                        if(isset($object) && ($object->group_id==1 )){
                        ?>
                        <li <?php if($_SESSION["menu"] == "list_bom"){?>class="active" <?php }?>><a href="list_bom.php" title="Pengaturan BOM Code"><i class="fa fa-gear"></i>&nbsp;BOM Code</a></li>   <?php
                        }
                        ?>
                    </ul>
                        <?php
                        }
                        ?>
                </li>
                <?php
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if(isset($_SESSION['login'])){
                ?>
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $object->username; ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li <?php if($_SESSION["menu"] == "about"){?>class="active" <?php }?>><a href="<?php echo BASE_URL?>bom.php?menu=about" title="About">About</a></li>  
<!--                        <li><a href=".\content\about.php"><i class="fa fa-truck"></i>&nbsp;About</a></li>-->
                        <li><a href="change_password.php"><i class="fa fa-lock"></i>&nbsp;Ubah Password</a></li>
                        <li><a href="logout.php" onclick="return confirm('Anda yakin akan keluar dari sistem?')"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></li>
                    </ul>
                </li>
                <?php
                }
                else{
                ?>
                <li><a href="login.php"><i class="fa fa-sign-in"></i>&nbsp;Login</a></li>
                <?php
                }
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>

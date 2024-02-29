<?php
session_start();
//kode menu
$_SESSION['menu'] = "batch_error";
if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
include "./inc/constant.php";
$date = date("d/m/Y");
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if(mysqli_connect_errno())
{ die("Failed to connect to MySQL: " . mysqli_connect_error());}

//set autocommit is
//mysqli_autocommit($con,FALSE);
include 'db.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>Batch Error</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css"/>
    </head>
    <body>
        <?php
        include "inc/menu.php";
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 colsm-offset-3">
                    <h1 class="page-header">Batch Transaction errors</h1>
                        <?php
                        $status_list = false;
                        date_default_timezone_set("Asia/Jakarta");
                        $sevendaysago  = date('Ymd',strtotime("-30 days"));
                        $today         = date('Ymd');                
                        $q = "SELECT DISTINCT BD.`docket_no`
                            FROM `batch_transaction_detail` BD
                            LEFT JOIN `batch_transaction` BT ON BD.`docket_no` = BT.`docket_no`
                            WHERE BT.`docket_no` IS NULL
                            LIMIT 0, 50";
                        //print_r($q);exit();
                        $result = mysqli_query($con,$q);
                        if(!$result) {
                            die(mysqli_error($con));
                        }
                        $num_rows = 0;
                        $num_rows = mysqli_num_rows($result);
                        $status_list = true;
                        ?>
                    <div class="table-responsive">
                        <?php
                        if(isset($num_rows)){
                        ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                if($num_rows > 0){
                                ?>
                                <form method="post" action="batch_error_delete.php" onSubmit="return validate();" >
                                    <table class="table table-bordered table-hover small" id="datamc">
                                        <thead>
                                            <tr class="bold">
                                                <th>
                                                <input type="checkbox" id="selectall">  All
                                                </th> 
                                            </tr>
                                          </thead>
                                          <tbody>
                                              <?php
                                              if($status_list == true){
                                                    $i=1;
                                                    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="checkbox1" name="mark[]" value="<?php echo $row['docket_no'];?>" /></td>
                                                            <td><?php echo $row['docket_no'];?></td>
                                                        </tr>
                                                        <?php
                                                        }
                                              }
                                                      ?>
                                            </tbody>
                                        </table>
                                    <button class="btn btn-sm btn-danger" type="submit" name="submit" value="aa">Delete&nbsp;<i class="fa fa-arrow-right"></i></button>
                                </form>
                                <?php
                                }
                                else{
                                    ?>
                                <div class="text-center text-danger">Data tidak ditemukan</div>
                                        <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
    mysqli_close($con);
    include "inc/version.php";
    include "content/popup_detail.php";
    ?>
        </div>
    <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {$("div.alert").fadeOut(5000);});
    </script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script language="javascript">
        var base_url    = location.origin+"/mis/";
        var loading_object = $(".loading");        
        function validate(){
            var chks = document.getElementsByName('mark[]');
            var hasChecked = false;
            for (var i = 0; i < chks.length; i++)
            {
                if(chks[i].checked){
                    hasChecked = true;
                    break;
                }
            }
            if (hasChecked == false)
            {
                BootstrapDialog.alert("Silahkan pilih data yang akan diproses!");
                return false;
            }
            if(hasChecked == true){
                return confirm("Anda yakin Dihapus?");
            }
            //return true;
        }        
        /*
         * view
         */
    </script>    
    <script>
    $(function() {$( "#filter" ).datepicker({ dateFormat: 'yymmdd' });});
    $(document).ready(function() {
        $('#selectall').click(function(event) {  //on click 
            if(this.checked) { // check select status
                $('.checkbox1').each(function() { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            }else{
                $('.checkbox1').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });         
            }
        });

    });
    </script>
    <script type="text/javascript">
        $('.form_datetime').datetimepicker({
            //language:  'fr',
            weekStart: 1,
            todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
            showMeridian: 1
        });
            $('.form_date').datetimepicker({
            weekStart: 1,
            todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    minView: 2,
                    forceParse: 0
        });
            $('.form_time').datetimepicker({
            language:  'fr',
            weekStart: 1,
            todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 1,
                    minView: 0,
                    maxView: 1,
                    forceParse: 0
        });
    </script>
    </body>
</html>
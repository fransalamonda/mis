<?php
session_start();

//kode menu
$_SESSION['menu'] = "acceptance";

if (isset($_SESSION['login'])) {
    $object = (object)$_SESSION['login'];
}
include "./inc/constant.php";
$date = date("d/m/Y");
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

//set autocommit is
//mysqli_autocommit($con,FALSE);
include 'db.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
    <title>Return Truck</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css" />
</head>

<body>
    <?php
    include "inc/menu.php";
    ?>
    <?php
    $status_list = false;
    $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom
                            FROM `mix_package_composition` AS A
                            INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no`
                            WHERE A.`code_trans` = 'Y'";
    $result = mysqli_query($con, $query_war);
    if (!$result) die(mysqli_error());
    $status_list = true;
    if ($status_list == true) {
        while ($row = mysqli_fetch_array($result)) {
            $chartno = $row['chart_no'];
            if ($chartno == '1') {
    ?>
                <div align="right" style="color:#000099">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } elseif ($chartno == '2') {
            ?>
                <div align="right" style="color:#FF0000">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } elseif ($chartno == '3') {
            ?>
                <div align="right" style="color:#077">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } elseif ($chartno == '4') {
            ?>
                <div align="right" style="color:#0f1">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } elseif ($chartno == '5') {
            ?>
                <div align="right" style="color:#f90">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } elseif ($chartno == '6') {
            ?>
                <div align="right" style="color:#f9f">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } elseif ($chartno == '7') {
            ?>
                <div align="right" style="color:#f4f">
                    <h4><?php echo $row['name_bom']; ?></h4>
                </div>
            <?php
            } else {
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
                <h1 class="page-header">Batch Transaction Acceptance</h1>
                <?php
                $status_list = false;
                date_default_timezone_set("Asia/Jakarta");
                $sevendaysago  = date('Ymd', strtotime("-7 days"));
                $today         = date('Ymd');

                $q = "SELECT A.* FROM `batch_transaction` A "
                    . "LEFT JOIN `batch_transaction2` B ON A.`docket_no` = B.`docket_no` "
                    . "WHERE (UPPER(`flag_code`)='S' OR UPPER(`flag_code`)='M') "
                    . "AND B.`request_no` IS NULL "
                    . "AND `mch_code`='" . PLANT_ID . "'"
                    //. "AND (UPPER(A.`product_code`)!='Adjustment' OR UPPER(A.`product_code`)!='SELFUSAGE') "
                    . "AND A.`product_code`!= 'Adjustment'"
                    . "AND A.`product_code`!= 'SELFUSAGE'"
                    . "AND STR_TO_DATE(A.`delv_date`,'%Y%m%d') BETWEEN '" . $sevendaysago . "' AND '" . $today . "'"
                    . "ORDER BY A.`docket_no` ASC";
                //print_r($q);
                //exit();
                $result = mysqli_query($con, $q);
                if (!$result) {
                    die(mysqli_error($con));
                }
                $num_rows = 0;
                $num_rows = mysqli_num_rows($result);
                $status_list = true;
                ?>
                <div class="table-responsive">
                    <?php
                    if (isset($num_rows)) {
                    ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                if ($num_rows > 0) {
                                ?>
                                    <form method="post" action="return_truck_review.php" onSubmit="return validate();">
                                        <table class="table table-bordered table-hover small" id="datamc">
                                            <thead>
                                                <tr class="bold">
                                                    <th>
                                                        #
                                                        <!--                                                    <input type="checkbox" id="selectall"> -->
                                                    </th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Seal No</th>
                                                    <th>Docket No</th>
                                                    <th>Prod Code</th>
                                                    <th>Delv Vol</th>
                                                    <th>So No</th>
                                                    <th>Unit No</th>
                                                    <th>Driver Name</th>
                                                    <th>Cust Name</th>
                                                    <th>Proj Name</th>
                                                    <th>Flag</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($status_list == true) {
                                                    $i = 1;
                                                    $delv_vol_sum = 0;
                                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                        $link = "";
                                                        $delv_vol_sum += $row['delv_vol'];
                                                ?>
                                                        <tr>
                                                            <td><input type="radio" class="checkbox1" name="mark[]" value="<?php echo $row['docket_no']; ?>" /></td>
                                                            <td>
                                                                <?php
                                                                $ymd = DateTime::createFromFormat('Ymd', $row['delv_date'])->format('Y-m-d');
                                                                echo $ymd;
                                                                ?>
                                                            </td>
                                                            <td><?php echo $row['delv_time']; ?></td>
                                                            <td><?php echo $row['seal_no']; ?></td>
                                                            <td><?php echo $row['docket_no']; ?></td>
                                                            <td><?php echo $row['product_code']; ?></td>
                                                            <td class="text-center"><?php echo $row['delv_vol']; ?></td>
                                                            <td><?php echo $row['so_no']; ?></td>
                                                            <td><?php echo $row['unit_no']; ?></td>
                                                            <td><?php echo $row['driver_name']; ?></td>
                                                            <td><?php echo $row['cust_name']; ?></td>
                                                            <td><?php echo $row['proj_name']; ?></td>
                                                            <td><?php echo $row['flag_code']; ?></td>
                                                            <td><a href="" data-docket="<?php echo $row['docket_no']; ?>" data-prod="<?php echo $row['product_code']; ?>" class="tmstart btn btn-xs btn-danger">TM Start</a></td>
                                                            <td><a href="" data-docket="<?php echo $row['docket_no']; ?>" data-prod="<?php echo $row['product_code']; ?>" class="view btn btn-xs btn-warning">View</a></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td colspan="5" class="text-right">Total Delv Vol :</td>
                                                        <td colspan="8"><?php echo $delv_vol_sum; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                        <button class="btn btn-sm btn-danger" type="submit" name="submit" value="aa">Proses&nbsp;<i class="fa fa-arrow-right"></i></button>
                                    </form>
                                <?php
                                } else {
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
        $(document).ready(function() {
            $("div.alert").fadeOut(5000);
        });
    </script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui-1.10.4.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script language="javascript">
        var base_url = location.origin + "/mis/";
        var loading_object = $(".loading");

        function validate() {
            var chks = document.getElementsByName('mark[]');
            var hasChecked = false;
            for (var i = 0; i < chks.length; i++) {
                if (chks[i].checked) {
                    hasChecked = true;
                    break;
                }
            }
            if (hasChecked == false) {
                BootstrapDialog.alert("Silahkan pilih data yang akan diproses!");
                return false;
            }
            if (hasChecked == true) {
                return confirm("Anda yakin Proses?");
            }
            //return true;
        }

        $("table#datamc").on('click', 'a.tmstart', function(e) {
            e.preventDefault(e);
            $("#msg").hide();
            $("#msg_popup").hide();
            var prod = $(this).data("prod");
            var docket = $(this).data("docket");
            var data = "prod=" + prod + "&docket=" + docket;
            alert(data);
            loading_object.show();
            jQuery.ajax({
                type: "POST", // HTTP method POST or GET
                url: base_url + "ajax/Batch_detail_update_loading.php", //Where to make Ajax calls
                dataType: "text", // Data type, HTML, json etc.
                data: data, //Form variables
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    if (obj.status === 1) {
                        $('#test').modal('show');
                        $("#popup_table").html(obj.msg);
                        $("#mix_wrapper").html(obj.mix_str);
                    } else if (obj.status === 0) {
                        show_alert_ms($("#msg"), 0, obj.msg);
                        //                goToMessage();
                    }
                    loading_object.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    loading_object.hide();
                    alert(thrownError);
                }
            });
        });
        /*
         * view
         */
        $("table#datamc").on('click', 'a.view', function(e) {
            e.preventDefault(e);
            $("#msg").hide();
            $("#msg_popup").hide();
            var prod = $(this).data("prod");
            var docket = $(this).data("docket");
            var data = "prod=" + prod + "&docket=" + docket;
            loading_object.show();
            jQuery.ajax({
                type: "POST", // HTTP method POST or GET
                url: base_url + "ajax/Batch_detail_retrieve.php", //Where to make Ajax calls
                dataType: "text", // Data type, HTML, json etc.
                data: data, //Form variables
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    if (obj.status === 1) {
                        $('#test').modal('show');
                        $("#popup_table").html(obj.msg);
                        $("#mix_wrapper").html(obj.mix_str);
                    } else if (obj.status === 0) {
                        show_alert_ms($("#msg"), 0, obj.msg);
                        //                goToMessage();
                    }
                    loading_object.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    loading_object.hide();
                    alert(thrownError);
                }
            });
        });
    </script>
    <script>
        $(function() {
            $("#filter").datepicker({
                dateFormat: 'yymmdd'
            });

        });
        $(document).ready(function() {
            $('#selectall').click(function(event) { //on click 
                if (this.checked) { // check select status
                    $('.checkbox1').each(function() { //loop through each checkbox
                        this.checked = true; //select all checkboxes with class "checkbox1"               
                    });
                } else {
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
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });
        $('.form_date').datetimepicker({
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
        $('.form_time').datetimepicker({
            language: 'fr',
            weekStart: 1,
            todayBtn: 1,
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
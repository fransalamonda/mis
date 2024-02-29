<?php
session_start();
//kode menu
$_SESSION['menu'] = "batch_request";
if (isset($_SESSION['login'])) {
    $object = (object)$_SESSION['login'];
}
if ($object->group_id != 6 && $object->group_id != 4 && $object->group_id != 2 && $object->group_id != 3) {
    header("Location:index.php");
}
include_once './inc/constant.php';
include "db.php";
require_once 'vendor/autoload.php';



function load_driver()
{
    include "db.php";
    $output = '';
    $result = mysqli_query($conns, "SELECT * FROM `t_truckmixer` ORDER BY `no_pol` ");
    if (mysqli_num_rows($result) > 0) {
        $output .= '';
        while ($row = mysqli_fetch_array($result)) {
            $output .= '
                    <option value="' . $row["driver_id"] . '" class="form-control input-md">
                        ' . $row["driver_name"] . '
                    </option>';
        }

        echo $output;
    } else {

        echo 'DATA NOT FOUND!';
    }
}

function api_logkar_truck()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dev-public-api.logkar.com/cargo/transport/truck',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                "request_code":"d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd",
                "shipper_code":"PCT",
                "truck_type":16
                }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: 153f0a7da9667448dcb3e0df8aab9fac6a2b622bbb3f8b2ef81c6e633ba8e28c',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $responseData = json_decode($response, true);
    $str_truck = '';
    if (isset($responseData['data'])) {
        if (is_array($responseData['data'])) {
            // Handle the array data as needed
            //print_r($responseData['data']);
            foreach ($responseData['data'] as $p) {

                $str_truck .= "<option value='" . $p['truck_id'] . "#" . $p['police_no'] . "'>";
                $str_truck .= $p['police_no'] . "-" . $p['code'] . "</option>";
            }
            echo $str_truck;
        }
        // else {
        //     // Access the 'data' key as a string
        //     $dataValue = $responseData['data'];
        //     echo $dataValue;
        // }
    } else {
        echo $str_truck .= "<option value=''>not connections logkar</option>";
    }
}

function api_logkar_driver()
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dev-public-api.logkar.com/cargo/transport/driver',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                                "request_code":"d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd",
                                "shipper_code":"PCT"
                                }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: 153f0a7da9667448dcb3e0df8aab9fac6a2b622bbb3f8b2ef81c6e633ba8e28c',
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    curl_close($curl);
    $responseData = json_decode($response, true);
    $str_driver = '';
    if (isset($responseData['data'])) {
        if (is_array($responseData['data'])) {
            // Handle the array data as needed
            //print_r($responseData['data']);   
            foreach ($responseData['data'] as $p) {

                $str_driver .= "<option value='" . $p['driver_id'] . "#" . $p['driver_name'] . "#" . $p['driver_phone'] . "'>";
                $str_driver .= $p['driver_name'] . "-" . $p['driver_phone'] . "</option>";
            }
            echo $str_driver;
        }
        // else {
        //     // Access the 'data' key as a string
        //     $dataValue = $responseData['data'];
        //     echo $dataValue;
        // }
    } else {
        echo $str_driver .= "<option value=''>not connections logkar</option>";
    }
    // echo $response['data'];
}
//api_logkar();

?>


<!doctype html>

<head>
    <title>Batch Request</title>
    <style>
        /*        form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px ;width: 400px;}*/
        .progress {
            position: relative;
            width: 400px;
            border: 1px solid #ddd;
            padding: 1px;
            border-radius: 3px;
            height: 2px;
        }

        .bar {
            background-color: #B4F5B4;
            width: 0%;
            height: 2px;
            border-radius: 3px;
        }

        .percent {
            position: absolute;
            display: inline-block;
            top: 3px;
            left: 48%;
        }

        #status {
            margin-top: 30px;
        }

        #batch-add {
            display: none;
        }

        #plant_id {
            margin: 0px auto;
            width: 400px;
        }

        form {
            margin: 20px auto;
            width: 50%;
        }
    </style>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="jquery-ui-1.10.4.custom/development-bundle/themes/base/jquery.ui.all.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css" />

</head>

<body>
    <?php
    include "inc/menu.php";
    $con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    ?>
    <div class="loading"></div>
    <div>
        <?php
        $status_list = false;
        $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";
        $result = mysqli_query($conns, $query_war);
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
                <?php } elseif ($chartno == '2') { ?>
                    <div align="right" style="color:#FF0000">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
                <?php } elseif ($chartno == '3') { ?>
                    <div align="right" style="color:#077">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
                <?php } elseif ($chartno == '4') { ?>
                    <div align="right" style="color:#0f1">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
                <?php } elseif ($chartno == '5') { ?>
                    <div align="right" style="color:#f90">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
                <?php } elseif ($chartno == '6') { ?>
                    <div align="right" style="color:#f9f">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
                <?php } elseif ($chartno == '7') { ?>
                    <div align="right" style="color:#f4f">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
                <?php } else { ?>
                    <div align="right" style="color:#0a9">
                        <h4><?php echo $row['name_bom']; ?></h4>
                    </div>
        <?php }
            }
        } ?>
        <div class="component">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4">

                    <div class="card-header">
                        <div class="col-md-12">
                            <div class="card-title batch_re"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="card-title batch_co"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form action="act/batch_request_act.php" method="post" enctype="multipart/form-data" id="query" class="form-horizontal" role="form">
            <div class="page-header">
                <div class="form-group">
                    <h1>Delivery Schedule Query</h1>
                </div>
            </div>
            <div id="messages" class="alert alert-danger">asasd</div>
            <fieldset>
                <div class="form-group">
                    <center>
                        <for="textinput" style="color:#f00;font-size: 35px;"><b>AWAS LOADING OVER DI PANGGIL LOH !!!!</b>
                    </center>
                </div>
                <?php
                $so_no = @$_GET['so_no'];
                $sql = "SELECT*FROM delivery_schedule WHERE so_no='$so_no'";

                $result = mysqli_query($conns, $sql);
                $row = mysqli_fetch_array($result);
                # code...

                ?>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">SO No :</label>
                        <div class="col-md-4">
                            <input id="so_no" name="so_no" type="text" placeholder="SO Number" class="form-control input-md" autocomplete="off" value="<?= $row['so_no']; ?>" readonly>
                        </div>
                    </div>
                </div>
                <?php  ?>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Schedule Date :</label>
                        <div class="col-md-4">
                            <input id="delvdate" name="sched_date" type="text" placeholder="Pilih Tanggal" readonly="" value="<?php echo date('d/m/Y'); ?>" class="form-control input-md">
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
            </fieldset>
        </form>
    </div>

    <div id="batch-add">
        <form action="ajax/batch_add.php" method="POST" id="detail" class="form-horizontal" role="form">
            <fieldset>
                <div id="message_batch" style="display: none;" class="alert alert-danger">asasd</div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Plant ID :</label>
                        <div class="col-md-4">
                            <label class="plant_id control-label" for="textinput"></label>
                            <input type="hidden" id="plant_id" value="" name="plant_id" />
                            <input type="hidden" id="so_no" value="" name="so_no" />
                            <input type="hidden" id="s_date" value="" name="s_date" />
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Schedule Date :</label>
                        <div class="col-md-4">
                            <label class="control-label" id="sche_date" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Cust :</label>
                        <div class="col-md-8">
                            <label id="cust_id" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Proj :</label>
                        <div class="col-md-8" style="padding-top: 5px;">
                            <label id="proj_no" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Proj Loc :</label>
                        <div class="col-md-8">
                            <label id="proj_loc" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Product Code:</label>
                        <div class="col-md-8">
                            <label id="prod_code" for="textinput"></label>
                        </div>
                    </div>
                </div>

                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" style="color:red;font-size: 35px;" for="textinput">Vol Awal/Vol Po :</label>
                        <div class="col-md-4">
                            <label class="control-label" style="color:red;font-size: 35px;" id="delv_volume" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" style="color:Blue;font-size: 30px;" for="textinput">Total Vol Terload :</label>
                        <div class="col-md-4">
                            <label class="control-label" style="color:Blue;font-size: 30px;" id="volume_total" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput" style="color:red;font-size: 45px;"><b>Sisa Vol Act:</b></label>
                        <div class="col-md-4">
                            <label class="control-label" style="color:red;font-size: 50px;" id="sisa_erp" for="textinput"></label>
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput" style="color:Blue;font-size: 30px;">Vol Hari Ini :</label>
                        <div class="col-md-4">
                            <label class="control-label" style="color:Blue;font-size: 30px;" id="today_b_request" for="textinput"></label>
                        </div>
                    </div>
                </div>

                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Vol Terkirim Hari ini :</label>
                        <div class="col-md-4">
                            <label class="control-label" id="out_volume" for="textinput"></label>
                        </div>
                    </div>
                </div>

                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Request Volume :</label>
                        <div class="col-md-4">
                            <input id="qty" value="" name="qty" type="text" placeholder="Request Volume" class="form-control input-md" onkeypress="return isNumberKey(event)">
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Seal No:</label>
                        <div class="col-md-4">
                            <input id="qty" value="" name="seal_no" type="text" placeholder="Seal No" class="form-control input-md">
                        </div>
                    </div>
                </div>
                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Truck</label>
                        <div class="col-md-4">
                            <select name="no_truck" id="no_truck" class="form-control input-md">
                                <option value="">...Truck Dari Logkar...</option>
                                <?php echo api_logkar_truck(); ?>

                            </select>


                        </div>
                    </div>
                </div>

                <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Driver</label>
                        <div class="col-md-4">
                            <select name="no_driver" id="no_driver" class="form-control input-md">
                                <option value="">...Driver Dari Logkar...</option>
                                <?php echo api_logkar_driver(); ?>

                            </select>


                        </div>
                    </div>
                </div>
                <!-- <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Driver TM:</label>
                        <div class="col-md-4">

                            <select name="no_pol" id="no_pol" class="form-control input-md">
                                <option value="">...Pilih Driver...</option>
                                <?php echo load_driver(); ?>

                            </select>

                        </div>
                    </div>
                </div> -->


                <!-- <div class="component">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">Unit TM:</label>
                        <div class="col-md-4" id="driver_name">
                            

    </div>
    </div>
    </div> -->

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
    <?php
    include "inc/version.php";
    include "content/popup_log_error.php";
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
            $("#delvdate").datepicker({
                dateFormat: 'dd/mm/yy'
            });
            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
            var delv_date = $('#delv_date').val();
            var loading = $(".loading");
            $('form#query').ajaxForm({
                beforeSend: function() {
                    loading.show();
                    $('#table').removeAttr("value");
                    $('div#message').fadeOut();
                    $('div#message_batch').fadeOut();
                    $('form#export').hide();
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                    $('div.loading').show();
                },
                success: function() {
                    var percentVal = '100%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                complete: function(xhr) {
                    $('div.loading').hide();
                    console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if (obj.status == '1') {
                        $('div#messages').html("<p>" + obj.msg + "</p>");
                        $('div#messages').removeClass("n_error").addClass("n_ok");
                        $('#sche_date').html(obj.sche_date);
                        $('#s_date').val(obj.sche_date);
                        $('#delv_volume').html(obj.delv_vol);
                        $('#today_b_request').html(obj.today_b_request);
                        $('#volume_total').html(obj.volume_total);
                        $('#sisa_erp').html(obj.sisa_erp);
                        $('#out_volume').html(obj.out_volume);
                        $('#cust_id').html(obj.cust_id + ", " + obj.cust_name);
                        //$('#cust_name').html(obj.cust_name);
                        $('#proj_no').html(obj.proj_no + ", " + obj.proj_address);
                        //$('#proj_name').html();
                        $('#proj_loc').html(obj.proj_loc);
                        $('#prod_code').html(obj.product_code);
                        //                        $('#varian').html(obj.varian)
                        $('.plant_id').html(obj.plant_id);
                        $('input#plant_id').val(obj.plant_id);
                        $('input#so_no').val(obj.so_no);
                        $('div#messages').hide();
                        $('div#batch-add').hide();
                        $('div#messages').fadeIn();
                        $('div#batch-add').slideDown();
                        $('#kueri').hide();
                    } else if (obj.status == '0') {
                        $('div#messages').hide();
                        $('div#batch-add').slideUp();
                        $('div#messages').removeClass("n_ok").addClass("n_error");
                        $('div#messages').html("<p>" + obj.msg + "!</p>");
                        $('div#messages').fadeIn();
                        $('div#detail').hide();
                    }
                }
            });

            //ADD QTY BATCH
            $('form#detail').ajaxForm({
                beforeSend: function() {
                    loading.show();
                    $('div#message_batch').fadeOut();
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                    console.log('progress');
                    $('div.loading').show();
                },
                success: function() {
                    var percentVal = '100%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                complete: function(xhr) {
                    //console.log(xhr.responseText);
                    $('div.loading').hide();
                    var obj = jQuery.parseJSON(xhr.responseText);
                    //                    console.log(obj.row);
                    if (obj.status == '1') {
                        $('div#message_batch').html("<p>" + obj.msg + "</p>");
                        $('div#message_batch').removeClass("n_error").addClass("n_ok");
                        $('div#message_batch').fadeIn();
                        $('input#qty').val('');
                        $('input#qty').val('');
                        $('#delv_volume').html(obj.delv_vol);
                        $('#today_b_request').html(obj.today_b_request);
                        $('#out_volume').html(obj.out_volume);
                        $('div#detail').hide();
                        $('#kueri').hide();
                        log_api();
                        goToMessage();
                    } else if (obj.status == '0') {
                        $('div#message_batch').hide();
                        $('div#message_batch').removeClass("n_ok").addClass("n_error");
                        $('div#message_batch').html("<p>" + obj.msg + "!</p>");
                        $('div#message_batch').fadeIn();
                        goToMessage();
                    }
                }
            });



            function goToMessage() {
                $("body, html").animate({
                    scrollTop: $("#detail").offset().top
                }, 600);
            }

        })();
    </script>
    <script type="text/javascript">
        var base_url = location.origin + "/mis/";
        var loading_object = $(".loading");

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode != 46 && charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;

            return true;
        }

        function validateAddress() {
            var TCode = document.getElementById('address').value;

            if (/[^a-zA-Z0-9\-\/]/.test(TCode)) {
                alert('Input is not alphanumeric');
                return false;
            }

            return true;
        }

        function goKlik() {
            $("button.logre").click(function(e) {
                $('#log_br').modal('show');
            });
        };

        function log_api(e) {
            //e.preventDefault(e);
            $("#msg").hide();

            var prod = '';
            var docket = '';
            var data = "prod=" + prod + "&docket=" + docket;
            loading_object.show();
            jQuery.ajax({
                type: "POST", // HTTP method POST or GET
                url: base_url + "ajax/Log_list_batch_request.php", //Where to make Ajax calls
                dataType: "text", // Data type, HTML, json etc.
                data: data, //Form variables
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    if (obj.status === 1) {
                        //$('#test').modal('show');
                        $(".batch_re").html(obj.str_re);
                        $(".batch_co").html(obj.str_co);
                        $("#t_log_request > tbody").html(obj.str_table_r);
                        // $("#popup_table").html(obj.str_table_r);
                        goKlik();
                    } else if (obj.status === 0) {
                        show_alert_ms($("#msg"), 0, obj.msg);
                    }
                    loading_object.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    loading_object.hide();
                    alert(thrownError);
                }
            });


        };
        log_api();
        setInterval(log_api, 3 * 60 * 1000);
    </script>

    <script>
        $(document).ready(function() {
            //     $('#no_pol').change(function() {
            //         var id_pol = $(this).val();
            //         $.ajax({
            //             url: "ajax/fetch_driver.php",
            //             method: "POST",
            //             data: {
            //                 no_pol: id_pol
            //             },
            //             dataType: "text",
            //             success: function(data) {
            //                 $('#driver_name').html(data)
            //             }
            //         });
            //     });

        });
    </script>
</body>

</html>
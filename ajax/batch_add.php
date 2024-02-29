<?php
session_start();
include '../inc/constant.php';
include "../db.php";
require_once '../vendor/autoload.php';
date_default_timezone_set("Asia/Jakarta");
if (IS_AJAX) {
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_SESSION['login'])) {
                $object = (object)$_SESSION['login'];
            }
            $Lg = $object->id_user;

            if (isset($_POST['so_no']) && !empty($_POST['so_no']) && isset($_POST['qty']) && !empty($_POST['qty']) && isset($_POST['s_date']) && !empty($_POST['s_date']) && isset($_POST['seal_no']) && !empty($_POST['seal_no'])) {
                extract($_POST);
                $today      = date('d/m/Y');
                $kn         = date('Y-m-d H:i:s');
                $driverid = '';
                $drivernm = '';
                $driverphone = '';
                if ($_POST['no_driver'] != "") {
                    $tmp = explode('#', $_POST['no_driver']);
                    $driverid = $tmp[0];
                    $drivernm = $tmp[1];
                    $driverphone = $tmp[2];
                } else {
                    $driverid = '';
                    $drivernm = '';
                    $driverphone = '';
                }
                $police_no = '';
                $truck_id = '';
                if ($_POST['no_truck'] != "") {
                    $tmp_t = explode('#', $_POST['no_truck']);
                    $truck_id = $tmp_t[0];
                    $police_no = $tmp_t[1];
                } else {
                    $police_no = "";
                    $truck_id = "";
                }


                //cek seal no
                $sn = "SELECT * FROM `batch_request` WHERE `seal_no` ='" . $seal_no . "' AND flag_code ='P'";
                $r_sn = mysqli_query($conns, $sn);
                if (mysqli_num_rows($r_sn) > 0) {
                    throw new Exception("Seal No : " . $seal_no . " Sudah Ada");
                }

                //get SO ORDER volume
                $ds = "SELECT A.* FROM `delivery_schedule` A WHERE `so_no` ='" . $so_no . "' AND `schedule_date` = '" . $s_date . "' AND ds_code = 'M'";
                $ds_r = mysqli_query($conns, $ds);
                if (mysqli_num_rows($ds_r) > 0) {
                    throw new Exception("SO : " . $so_no . " Sudah Di delete");
                }

                //get SO ORDER volume
                $q = "SELECT A.* FROM `delivery_schedule` A WHERE `so_no` ='" . $so_no . "' AND `schedule_date` = '" . $s_date . "'";
                $result = mysqli_query($conns, $q);
                if (!$result) {
                    throw new Exception("ERROR sql: " . mysqli_error());
                }
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $temp_vol       = 0;
                    $so_vol         = 0;
                    $product_code   = "";
                    $cust_code      = "";
                    $cust_name      = "";
                    //CEK TOTAL BARANG KELUAR, diambil dari tabel BATCH_REQUEST yang flag_code nya selain 'D'
                    $volume_out     = 0;
                    $kueri = "SELECT SUM(`batch_vol`) AS volume_out  FROM `batch_request` WHERE `so_no`='" . $_POST['so_no'] . "' AND `product_code` = '" . $row['product_code'] . "' AND `flag_code` != 'D' AND `batch_date` = '" . $s_date . "'";
                    $hasil = mysqli_query($conns, $kueri);
                    if (!$hasil) {
                        throw new Exception("ERROR sql: " . mysqli_error());
                    }
                    if (mysqli_num_rows($hasil) > 0) {
                        $r = mysqli_fetch_array($hasil);
                        $volume_out = $r['volume_out'];
                    }

                    $so_vol         = $row['qty_so'];
                    $product_code   = $row['product_code'];
                    $cust_code     = $row['customer_id'];
                    $cust_name     = $row['customer_name'];
                    $proj_code     = $row['project_id'];
                    $proj_name     = $row['project_location'];
                    $proj_address     = $row['project_address'];
                    $proj_tel     = $row['project_tel'];
                    $today_vol      = $row['1-24hr'];
                    // $driver_id =  $_POST['no_pol'];
                    // $platno =     $_POST['no_pol2'];
                    $driver_id =  $driverid;
                    $platno    =     $police_no;
                    $driver_nm =  $drivernm;

                    /*
                         * 150519 : perubahan toleransi untuk SO02
                         */
                    $toleransi = SO_PERCENTAGE;
                    if (substr($_POST['so_no'], 0, 4) == "SO02") {
                        $toleransi = 0;
                    } elseif (substr($_POST['so_no'], 0, 4) == "SO07") {
                        $toleransi = 0;
                    } elseif (substr($_POST['so_no'], 0, 4) == "SO08") {
                        $toleransi = 0;
                    }

                    //cek total
                    if ($qty + $volume_out <= $so_vol + ($so_vol * $toleransi)) {

                        $ku = "SELECT MAX(CAST(request_no AS UNSIGNED)) request_no FROM batch_request ORDER BY CAST(request_no AS UNSIGNED)";
                        $hasil_max = mysqli_query($conns, $ku);
                        $count = mysqli_num_rows($hasil_max);
                        //echo $count;exit();
                        if ($count == 0) {
                            $request_no = 1;
                        } else {
                            $data = mysqli_fetch_array($hasil_max);
                            $request_no = $data['request_no'] + 1;
                        }
                        $curdate = date('d/m/Y');

                        //cek versi
                        $query_war = "SELECT DISTINCT A.`chart_no`, B.name_bom FROM `mix_package_composition` AS A INNER JOIN `tbl_code_bom` AS B ON B.`code_bom` = A.`chart_no` WHERE A.`code_trans` = 'Y'";
                        $data_var = mysqli_query($conns, $query_war);
                        if (!$data_var) die(mysqli_error());
                        if (mysqli_num_rows($data_var) > 0) {
                            $VAR_data = mysqli_fetch_array($data_var);
                            $chart = $VAR_data['chart_no'];
                            $name_bom = $VAR_data['name_bom'];
                        }
                        //MODIFIKASI 140718
                        //$remain_vol = $so_vol - $qty;
                        $remain_vol = $today_vol - $qty;
                        if ($remain_vol < 0) $remain_vol = 0;

                        $insert = "INSERT INTO `batch_request` "
                            . "(`request_no`,`so_no`,`mch_code`,`product_code`,`batch_date`,`vh_no`,`unit_no`,`driver_id`,`driver_name`,`user_login`,`cust_code`,`cust_name`,`proj_code`,`proj_name`,`proj_address`,`proj_phone_no`,`batch_vol`,`remain_vol`,`total_so_vol`,`flag_code`,`cre_by`,`cre_date`,`seal_no`) "
                            . "VALUES ('" . $request_no . "','" . $so_no . "','" . $plant_id . "','" . $product_code . "','" . $s_date . "',' ','" . $platno . "','" . $driver_id . "', '" . $driver_nm . "',' ','" . $cust_code . "','" . $cust_name . "','" . $proj_code . "','" . $proj_name . "','" . $proj_address . "','" . $proj_tel . "','" . $qty . "','" . $remain_vol . "','" . $so_vol . "','I',' ',NOW(),'" . $seal_no . "')";
                        //echo $insert;exit();
                        if (!mysqli_query($conns, $insert)) {
                            throw new Exception('Gagal Menambahkan Batch Request, Error : ' . mysqli_error());
                        }

                        //insert login & versi
                        $insert_requst = "INSERT INTO `request` "
                            . "(request_no,seal_no,code_bom,name_bom,id_user) "
                            . "VALUES ('" . $request_no . "','" . $seal_no . "','" . $chart . "','" . $name_bom . "','" . $Lg . "')";
                        if (!mysqli_query($conns, $insert_requst)) {
                            throw new Exception('Gagal Menambahkan Batch Request, Error : ' . mysqli_error());
                        } else {
                            //UPDATE 1-24hr field on delivery_schedule_temp table
                            $temp_up = $qty + $volume_out;
                            $q_update = "UPDATE `delivery_schedule` SET `out_volume` = '$temp_up' WHERE `so_no` = '$so_no' AND `schedule_date` = '$s_date'";
                            if (!mysqli_query($conns, $q_update)) {
                                throw new Exception("ERROR:Cannot Update Temp Volume on delivery schedule, MySql : " . mysqli_error());
                            } else {
                                $kueri = "SELECT * FROM `delivery_schedule` WHERE `so_no` = '$so_no' AND `schedule_date`= '$s_date'";
                                $result = mysqli_query($conns, $kueri);
                                $data = mysqli_fetch_array($result);


                                if ($_POST['no_truck'] != '' or $_POST['no_driver'] != '') {
                                    $ttl = 1;
                                    $totalkirim = 1;
                                    //akumulasi SO
                                    $select = "SELECT COUNT(*) AS ttlkirim
                                    FROM `batch_request` B
                                    WHERE B.so_no = '" . $so_no . "' AND B.flag_code = 'P';";
                                    $hasil_max = mysqli_query($conns, $select);
                                    $data_kirim = mysqli_fetch_array($hasil_max);
                                    if ($data_kirim['ttlkirim'] == 0) {
                                        $ttl = $data_kirim['ttlkirim'] + 1;
                                        $totalkirim = str_pad($ttl, 3, '0', STR_PAD_LEFT);
                                    } else {
                                        $ttl = 1;
                                        $totalkirim = str_pad($ttl, 3, '0', STR_PAD_LEFT);
                                    }


                                    //GET DO SUMMARY
                                    $curl_summary = curl_init();
                                    curl_setopt_array($curl_summary, array(
                                        CURLOPT_URL => 'https://dev-api.logkar.com/integration-api/orders/do/get',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => '{
                                        "request_code": "d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd",
                                        "do_no": "' . $so_no . '"
                                    }',
                                        CURLOPT_HTTPHEADER => array(
                                            'Authorization: 153f0a7da9667448dcb3e0df8aab9fac6a2b622bbb3f8b2ef81c6e633ba8e28c',
                                            'Content-Type: application/json'
                                        ),
                                    ));

                                    $response_summ = curl_exec($curl_summary);
                                    if (curl_errno($curl_summary)) {
                                        $insert_log = "INSERT INTO `log_batch_request` "
                                            . "(`request_no`,`so_no`,`cre_date`,`status`,respon) "
                                            . "VALUES ('" . $request_no . "','" . $so_no . "',NOW(),'0','HTTP error1: " . curl_error($curl) . "')";

                                        if (!mysqli_query($conns, $insert_log)) {
                                            throw new Exception('Gagal Menambahkan log, Error : ' . mysqli_error());
                                        }
                                        //echo $response;
                                    }

                                    curl_close($curl_summary);
                                    $responseData = json_decode($response_summ, true);



                                    $cargo_name = "";
                                    $created_at = '';
                                    $address = '';
                                    $detail_address = "";
                                    $latitude = "";
                                    $longitude = "";
                                    $name = "";
                                    $phone_d = "";
                                    $do_id = "";
                                    $do_no = "";
                                    $do_parent = "";
                                    $end_date = "";
                                    $good_code = '';
                                    $good_name = '';
                                    $good_qty =  '';
                                    $good_unit = '';
                                    $good_weight =  '';

                                    $logkar_no = '';

                                    $origin_address = '';
                                    $origin_detail_address =  '';
                                    $origin_latitude =  '';
                                    $origin_longitude =  '';
                                    $origin_pic_phonee = '';

                                    $ref_no = '';
                                    $status =  '';
                                    $suj =  '';
                                    $suj_amount =  '';
                                    $transporter_name = '';

                                    //$shipper_code = "";


                                    if ($responseData['status'] === 'OK') {


                                        // Process the successful response
                                        //echo $response;
                                        //ADD DATA API VINCDENT
                                        $cargo_name =  $responseData['data'][0]['cargo_name'];
                                        $created_at =  $responseData['data'][0]['created_at'];
                                        $address        =  $responseData['data'][0]['destination']['address'];
                                        $detail_address =  $responseData['data'][0]['destination']['detail_address'];
                                        $latitude =  $responseData['data'][0]['destination']['latitude'];
                                        $longitude =  $responseData['data'][0]['destination']['longitude'];
                                        $name =  $responseData['data'][0]['destination']['name'];
                                        $phone_d =  $responseData['data'][0]['destination']['pic_phone'];

                                        $do_id =  $responseData['data'][0]['do_id'];
                                        $do_parent =  $responseData['data'][0]['do_parent'];
                                        $end_date =  $responseData['data'][0]['end_date'];


                                        $good_code =  $responseData['data'][0]['goods_detail'][0]['code'];
                                        $good_name =  $responseData['data'][0]['goods_detail'][0]['goods_name'];
                                        $good_qty =  $responseData['data'][0]['goods_detail'][0]['order_qty'];
                                        $good_unit =  $responseData['data'][0]['goods_detail'][0]['unit'];
                                        $good_weight =  $responseData['data'][0]['goods_detail'][0]['unit_weight_kg'];

                                        $logkar_no =  $responseData['data'][0]['logkar_no'];

                                        $origin_address =  $responseData['data'][0]['origin']['address'];
                                        $origin_detail_address =  $responseData['data'][0]['origin']['detail_address'];
                                        $origin_latitude =  $responseData['data'][0]['origin']['latitude'];
                                        $origin_longitude =  $responseData['data'][0]['origin']['longitude'];
                                        $origin_name =  $responseData['data'][0]['origin']['name'];
                                        $origin_pic_phonee =  $responseData['data'][0]['origin']['pic_phone'];

                                        $ref_no =  $responseData['data'][0]['ref_no'];
                                        $start_date =  $responseData['data'][0]['start_date'];
                                        $status =  $responseData['data'][0]['status'];
                                        $suj =  $responseData['data'][0]['suj'];
                                        $suj_amount =  $responseData['data'][0]['suj_amount'];
                                        $transporter_name =  $responseData['data'][0]['transporter_name'];




                                        $curl = curl_init();
                                        curl_setopt_array($curl, array(
                                            CURLOPT_URL => 'https://dev-public-api.logkar.com/cargo/scheduling/add',
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 0,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => '{
                                                    "do_no": "' . $so_no . '-' . $seal_no . '",
                                                    "do_id": ' . $do_id . ',
                                                    "parent": "' . $so_no . '",
                                                    "truck_capacity": 7,
                                                    "driver_phone": "' . $driverphone . '",
                                                    "police_no": "' . $police_no . '",
                                                    "shipper_code": "PCT",
                                                    "goods": [
                                                        {
                                                            "code": "' . $product_code . '",
                                                            "qty": ' . $qty . '
                                                        }
                                                    ],
                                                 
                                                    "request_code": "d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd"
                                                    }',
                                            CURLOPT_HTTPHEADER => array(
                                                'Authorization: 153f0a7da9667448dcb3e0df8aab9fac6a2b622bbb3f8b2ef81c6e633ba8e28c',
                                                'Content-Type: application/json'
                                            ),
                                        ));

                                        // print_r('{
                                        //     "do_no": "' . $so_no . '",
                                        //     "do_id": ' . $do_id . ',
                                        //     "parent": "' . $so_no . '-' . $totalkirim . '",
                                        //     "truck_capacity": ' . $qty . ',
                                        //     "driver_phone": "' . $driverphone . '",
                                        //     "police_no": "' . $police_no . '",
                                        //     "shipper_code": "' . $cust_code . '",
                                        //     "goods": [
                                        //         {
                                        //             "code": "' . $product_code . '",
                                        //             "qty": ' . $good_qty . '
                                        //         }
                                        //     ],
                                        //     "destination": {
                                        //         "name": "' . $name . '",
                                        //         "address": "' . $address . '",
                                        //         "detail_address": "' . $detail_address . '",
                                        //         "phone_number": "' . $phone_d . '",
                                        //         "code": "' . $proj_code . '",
                                        //         "longitude": "' . $latitude . '",
                                        //         "latitude": "' . $longitude . '"
                                        //     },
                                        //     "origin": {
                                        //         "name": "' . $origin_name . '",
                                        //         "address": "' . $origin_address . '",
                                        //         "detail_address": "' . $origin_detail_address . '",
                                        //         "phone_number": "' . $origin_pic_phonee . '",
                                        //         "code": "' . $proj_code . '",
                                        //         "longitude": "' . $origin_longitude . '",
                                        //         "latitude": "' . $origin_latitude . '"
                                        //     },
                                        //     "request_code": "d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd"
                                        // }');
                                        // print_r('{
                                        //     "do_no": "' . $so_no . '-' . $seal_no . '",
                                        //     "do_id": ' . $do_id . ',
                                        //     "parent": "' . $so_no . '",
                                        //     "truck_capacity": 7,
                                        //     "driver_phone": "' . $driverphone . '",
                                        //     "police_no": "' . $police_no . ',
                                        //     "shipper_code": "PCT",
                                        //     "goods": [
                                        //         {
                                        //             "code": "' . $product_code . '",
                                        //             "qty": ' . $qty . '
                                        //         }
                                        //     ],

                                        //     "request_code": "d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd"
                                        //     }');

                                        // exit();

                                        if (curl_errno($curl)) {
                                            //echo 'Curl error: ' . curl_error($curl);
                                            $insert_log = "INSERT INTO `log_batch_request` "
                                                . "(`request_no`,`so_no`,`cre_date`,`status`,respon) "
                                                . "VALUES ('" . $request_no . "','" . $so_no . "',NOW(),'0','HTTP error4: " . curl_error($curl) . "')";
                                            if (!mysqli_query($conns, $insert_log)) {
                                                throw new Exception('Gagal Menambahkan log, Error : ' . mysqli_error());
                                            }
                                            //echo $response;
                                        }
                                        $responseASSIGN = curl_exec($curl);

                                        curl_close($curl);
                                        $responseData_A = json_decode($responseASSIGN, true);
                                        // print_r($responseData_A);
                                        // exit();
                                        //Update DO Summary
                                        $curl_u = curl_init();
                                        curl_setopt_array($curl_u, array(
                                            CURLOPT_URL => 'https://dev-public-api.logkar.com/cargo/loading/status',
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 0,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',


                                            CURLOPT_POSTFIELDS => '{
                                                        "request_code": "d4cad279e27e1e8fc638cfbc26b4544e5a6121abe24fc40dc9eb54d008993acd",
                                                        "do_parent": "' . $so_no . '",
                                                        "do_no": "' . $so_no . '-' . $seal_no . '",
                                                        "truck_code": "' . $police_no . '",
                                                        "status": 1
                                                    }',

                                            CURLOPT_HTTPHEADER => array(
                                                'Authorization: 153f0a7da9667448dcb3e0df8aab9fac6a2b622bbb3f8b2ef81c6e633ba8e28c',
                                                'Content-Type: application/json'
                                            ),
                                        ));

                                        if ($responseData_A['status'] != 'OK') {

                                            $insert_log = "INSERT INTO `log_batch_request` "
                                                . "(`request_no`,`so_no`,`cre_date`,`status`,respon) "
                                                . "VALUES ('" . $request_no . "','" . $so_no . "',NOW(),'0','ASSIGN DO BY CARGO " . $responseData_A['status'] . "-" . $responseData_A['data'] . "-" . $responseData_A['code'] . "')";
                                            if (!mysqli_query($conns, $insert_log)) {
                                                throw new Exception('Gagal Menambahkan log, Error : ' . mysqli_error());
                                            }
                                        } else {
                                            $insert_log = "INSERT INTO `log_batch_request` "
                                                . "(`request_no`,`so_no`,`cre_date`,`status`,respon) "
                                                . "VALUES ('" . $request_no . "','" . $so_no . "',NOW(),'1','Sukses ASSIGN DO BY CARGO')";
                                            if (!mysqli_query($conns, $insert_log)) {
                                                throw new Exception('Gagal Menambahkan log, Error : ' . mysqli_error());
                                            }
                                        }
                                        //echo $response;
                                    } else {
                                        // Check HTTP status code for errors
                                        $insert_log = "INSERT INTO `log_batch_request` "
                                            . "(`request_no`,`so_no`,`cre_date`,`status`,respon) "
                                            . "VALUES ('" . $request_no . "','" . $so_no . "',NOW(),'0','" . $responseData['status'] . "-" . $responseData['code'] . "')";
                                        if (!mysqli_query($conns, $insert_log)) {
                                            throw new Exception('Gagal Menambahkan log, Error : ' . mysqli_error());
                                        }
                                    }



                                    //echo $response;
                                } else {
                                    $insert_log = "INSERT INTO `log_batch_request` "
                                        . "(`request_no`,`so_no`,`cre_date`,`status`,respon) "
                                        . "VALUES ('" . $request_no . "','" . $so_no . "',NOW(),'0','HTTP error: Driver Dan Truck Kosong ')";
                                    if (!mysqli_query($conns, $insert_log)) {
                                        throw new Exception('Gagal Menambahkan log, Error : ' . mysqli_error());
                                    }
                                }




                                $output = array(
                                    'status'            =>  1,
                                    'msg'               =>  'Berhasil Menambahkan Batch Request, No SO : ' . $so_no . ', tanggal :' . $s_date . ', Qty :' . $qty,
                                    'out_volume'        =>  $data['out_volume'],
                                    'today_b_request'   =>  $data['1-24hr'],
                                    //'test_api'   =>  $response,

                                );
                                exit(json_encode($output));
                            }
                        }
                    } else {
                        $max    =   $so_vol + ($so_vol * $toleransi);
                        throw new Exception("Qty melebihi Order Volume, MAX Order adalah " . $max);
                    }
                } else {
                    throw new Exception("SO : " . $so_no . " tidak ditemukan");
                }
            } else {
                throw new Exception("Empty Field");
            }
        } else {
            exit("Method is denied");
        }
    } catch (Exception $exc) {
        $output = array(
            "status"    => 0,
            "msg"       =>  $exc->getMessage()
        );
        exit(json_encode($output));
    }
} else {
    exit("Access Denied");
}

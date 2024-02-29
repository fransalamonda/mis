<?php
session_start();
include '../inc/constant.php';
if (!IS_AJAX) {
    die("Access Denied");
}
if (isset($_SESSION['login'])) {
    $object = (object)$_SESSION['login'];
}
/*
 * CHECKING SESSION
 */
try {

    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if (!$mysqli)        throw new Exception($mysqli->error);

    $sql_log = "SELECT   COUNT(L.so_no) AS so_jml FROM `log_batch_request` L WHERE L.`status`='0'";
    $log_data = $mysqli->query($sql_log);
    $log_req = 0;
    $no_urut = 1;
    while ($log_row = $log_data->fetch_array(MYSQLI_ASSOC)) {
        $log_req = $log_row['so_jml'];
    }
    $no_urut++;
    $sql_batch = "SELECT  COUNT(B.so_no) AS do_jml FROM `log_batch_transaction` B WHERE B.`status`='0'";
    $log_batch = $mysqli->query($sql_batch);
    $log_bat = 0;
    $no_urut_b = 1;
    while ($bat_row = $log_batch->fetch_array(MYSQLI_ASSOC)) {
        $log_bat = $bat_row['do_jml'];
    }
    $no_urut_b++;
    if ($log_req == 0) {
        $str_re = "";
    } else {
        $str_re = "<button type='button' class='btn btn-outline-primary waves-effect waves-light logre'>" . $log_req . " Batch Request Error  to Logkar</button>";
    }
    if ($log_bat == 0) {
        $str_co   = "";
    } else {
        $str_co   = "<button type='' class='btn btn-outline-primary waves-effect waves-light'>" . $log_bat . " Batch Transaction Error to Logkar</button>";
    }
    //lisr Batch request error
    //     $sql_batch_e = "SELECT  L.*, R.* FROM `log_batch_request` L 
    // LEFT JOIN `batch_request` R ON L.request_no = R.request_no
    // WHERE L.`status`='0'";
    $sql_batch_e = "SELECT  L.*, R.* FROM `log_batch_request` L 
LEFT JOIN `batch_request` R ON L.request_no = R.request_no
WHERE R.`flag_code`='D'";
    $log_batch_e = $mysqli->query($sql_batch_e);
    $no_urut = 1;
    $str_table_r = "";
    while ($row_e = $log_batch_e->fetch_array(MYSQLI_ASSOC)) {
        $str_table_r .= "<tr>";
        $str_table_r .= "<td>" . $row_e['so_no'] . " </td>";
        $str_table_r .= "<td>" . $row_e['cre_date'] . " </td>";
        $str_table_r .= "<td>" . $row_e['cust_code'] . " " . $row_e['cust_name'] . " </td>";
        $str_table_r .= "<td>" . $row_e['proj_name'] . " </td>";
        $str_table_r .= "<td>" . $row_e['proj_address'] . " </td>";
        $str_table_r .= "<td>" . $row_e['unit_no'] . " </td>";
        $str_table_r .= "<td>" . $row_e['driver_name'] . " </td>";
        $str_table_r .= "<td>" . $row_e['respon'] . " </td>";
        $str_table_r .= "<td><a class=' btn btn-xs btn-success'>Kirim Ulang</a></td>";
        $str_table_r .= "<tr>";
    }
    $no_urut++;

    $output = array(
        "status"    =>  1,
        "str_re"       =>  $str_re,
        "str_co"   =>  $str_co,
        "str_table_r"   => $str_table_r
    );
    exit(json_encode($output));
} catch (Exception $exc) {
    $output = array(
        "status"    =>  0,
        "msg"       =>  $exc->getMessage()
    );
    exit(json_encode($output));
}

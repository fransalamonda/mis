<?php
include './inc/constant.php';
include './inc/function.php';
ini_set('max_execution_time',500); //set max execution time

date_default_timezone_set("Asia/Jakarta");
$current_date_time = date("Ymdhis");

//LOG FILE
$log_file = "log/".date("Ymd").".log";


$time = date("h:i:s");
$log_msg = "\n+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
$log_msg .= "\n".$time." -- #1.Connecting To FTP : ".FTP_SERVER;
write_log($log_file,$log_msg);




//$ftp_conn = ftp_connect(FTP_SERVER) or die("Could not connect to ".FTP_SERVER);
$ftp_conn = ftp_connect(FTP_SERVER);
//$login = ftp_login($ftp_conn, "", $ftp_userpass);
if($ftp_conn){
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Connected ";
    write_log($log_file,$log_msg);
}
else{
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Connection failed";
    write_log($log_file,$log_msg);
    
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>Koneksi ke FTP server gagal</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}

// try to login

if (@ftp_login($ftp_conn, base64_decode(FTP_USER), base64_decode(FTP_PASS))) {
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Logged in as ".FTP_USER;
    write_log($log_file,$log_msg);
} else {
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Login failed ";
    write_log($log_file,$log_msg);
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>Login ke FTP server gagal</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}

//connect to mysql
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
//    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>Gagal Koneksi Ke MySQL Server</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}

/*
 * LOGIC
 * 1.check the available datas, charactized by `S` OR `M` value in `flag_code` field of `batch_transaction` table, if no rows then exit
 * 2.if the data is found, grouped into 2 query function : contains `S` flag_code and `M` flag_code
 * 
 */

/*
 * LOGIKA pertama
 */
//get all available data based on delv_date

$string_delv_date = "SELECT `delv_date` FROM `batch_transaction` "
        . "WHERE `mch_code`='".PLANT_ID."' AND (UPPER(`flag_code`) = 'S' OR UPPER(`flag_code`) = 'M') "
        . "GROUP BY `delv_date`"
        . "ORDER BY `delv_date` ";
$result_delv_date = $mysqli->query($string_delv_date);
if(!$result_delv_date){
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
    write_log($log_file,$log_msg);
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}

//jika tidak ada data yang ditemukan, maka tidak akan memproses data
if($result_delv_date->num_rows == 0){
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- No Records Found";
    write_log($log_file,$log_msg);
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>Data Kosong</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}

/*
 * LOGIKA Kedua
 */

/*
 * =========================
 * PROSES flag_code 'S'
 * =========================
 */
$string_delv_date_s = "SELECT `delv_date` FROM `batch_transaction` "
        . "WHERE `mch_code`='".PLANT_ID."' AND UPPER(`flag_code`) = 'S'"
        . "GROUP BY `delv_date`"
        . "ORDER BY `delv_date` ";
$result_delv_date_s = $mysqli->query($string_delv_date_s);
if(!$result_delv_date){
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
    write_log($log_file,$log_msg);
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}
//menyimpan list file csv
$list_docket_file = array();
$list_mat_is_file = array();

$loop_docket = 1;
$count_data = 0;
while($row = $result_delv_date_s->fetch_array(MYSQLI_ASSOC)){
    //Query which become output
    $string_selected = "SELECT `seal_no`,`so_no`,'1' as sequence,`mch_code`,`product_code`,`delv_vol`,`unit_no`,`driver_id` "
        . "FROM `batch_transaction` "
        . "WHERE `delv_date` = '".$row['delv_date']."' AND `mch_code`='".PLANT_ID."' AND UPPER(`flag_code`) = 'S'";
    $result_selected = $mysqli->query($string_selected);
    if(!$result_selected){
        $time = date("h:i:s");
        $log_msg = "";
        $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
        write_log($log_file,$log_msg);
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
    
    $count_data += $result_selected->num_rows;
    
    $list_docket = array();
    while ($row_selected = $result_selected->fetch_array(MYSQLI_ASSOC)){
        //get list docket
        $list_docket[] = $row_selected;
    }
    
    //query just to get docket_no
    $string_docket = "SELECT `docket_no`,`so_no`,'1' as sequence,`mch_code`,`product_code`,`delv_vol`,`unit_no`,`driver_id` "
        . "FROM `batch_transaction` "
        . "WHERE `delv_date` = '".$row['delv_date']."' AND `mch_code`='".PLANT_ID."' AND (UPPER(`flag_code`) = 'S')";
    $result_docket = $mysqli->query($string_docket);
    if(!$result_docket){
        $time = date("h:i:s");
        $log_msg = "";
        $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
        write_log($log_file,$log_msg);
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
    
    $loop = 1;
    while($row_docket = $result_docket->fetch_array(MYSQL_ASSOC)):
        //get material issue 
        $string_mat_is = "SELECT ' ' request_no,a.`so_no`,'1' as so_line_no,b.`seal_no`,b.`Product_Code`,a.`material_code`,a.`target_qty`,a.`actual_qty`,a.`moisture`"
                . "FROM `batch_transaction_detail` AS a "
                . "INNER JOIN `batch_transaction` AS b ON  a.`docket_no`=b.`docket_no` WHERE a.`docket_no` ='".$row_docket['docket_no']."'";
        $result_mat_is = $mysqli->query($string_mat_is);
        if(!$result_mat_is){
            $time = date("h:i:s");
            $log_msg = "";
            $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
            write_log($log_file,$log_msg);
            $output = array(
                'status'    =>0,
                'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
            );
            exit(json_encode($output));
        }
        
        //field name hanya di append ke csv file pada looping pertama
        
        //get columns name
        $field_mat_is = $result_mat_is->fetch_fields();
        $list_field_mat_is = array();
        foreach ($field_mat_is as $val) {
            array_push($list_field_mat_is, $val->name);
        }
        
        //data list material issue
        $list_mat_is = array();
        while($row_mat_is = $result_mat_is->fetch_array(MYSQL_ASSOC)):
            $list_mat_is[] = $row_mat_is;
        endwhile;
        
        
        $mat_is_file_name = $row['delv_date']."_".$current_date_time."_".PLANT_ID.'_S_Mat_is.csv';
        
        //append field / column name
        //only at the first loop
        if($loop == 1){
            //Export Material Issue file
            $f_mat_is = fopen(TEMP_DIR.$mat_is_file_name, 'w');
            fputcsv($f_mat_is, $list_field_mat_is, ",");
        }
        else{
            $f_mat_is = fopen(TEMP_DIR.$mat_is_file_name, 'a+');
        }
        $loop++;
        foreach ($list_mat_is as $list_mat_is) { 
            fputcsv($f_mat_is, $list_mat_is, ",");
        }
        
        
    endwhile;
    //push file path to array
    array_push($list_mat_is_file, $mat_is_file_name);
    fclose($f_mat_is);
    
    //Getting field name
    $field_info = $result_selected->fetch_fields();
    $list_field_docket = array();
    foreach ($field_info as $val) {
        array_push($list_field_docket, $val->name);
    }
    
    //export Docket file 
    $docket_file_name = $row['delv_date']."_".$current_date_time."_".PLANT_ID.'_S_Docket.csv';
    if($loop_docket == 1){
        $fp = fopen(TEMP_DIR.$docket_file_name, 'w');
    }
    else{
        $fp = fopen(TEMP_DIR.$docket_file_name, 'a+');
    }
    
    //append field / column name
    fputcsv($fp, $list_field_docket, ",");
    
    //append docket records to DOCKET file
    foreach ($list_docket as $list_data) { 
        fputcsv($fp, $list_data, ",");
    }
    array_push($list_docket_file, $docket_file_name);
    fclose($fp);
    $loop_docket++;
    
    
    //UPDATE flag batch_transaction
    $string_update_flag = "UPDATE `batch_transaction` SET `flag_code`='P' "
            . "WHERE `delv_date` = '".$row['delv_date']."' "
            . "AND `mch_code`='".PLANT_ID."' "
            . "AND UPPER(`flag_code`) = 'S'";
    $result_update_flag = $mysqli->query($string_update_flag);
    if(!$result_update_flag){
        $time = date("h:i:s");
        $log_msg = "";
        $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
        write_log($log_file,$log_msg);
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }

}// endwhile
foreach ($list_docket_file as $file) {
    $remote_file = '/dec/'.$file;
    if (ftp_put($ftp_conn, $remote_file, TEMP_DIR.$file, FTP_ASCII)) {
//        echo "successfully uploaded $file\n";
    } else {
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Gagal Upload Ke FTP Server</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
}
foreach ($list_mat_is_file as $file) {
    $remote_file = '/dec/'.$file;
    if (ftp_put($ftp_conn, $remote_file, TEMP_DIR.$file, FTP_ASCII)) {
//        echo "successfully uploaded $file\n";
    } else {
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>Gagal Upload Ke FTP Server</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
}










/*
 * ==============================
 * PROSES flag_code 'M' -> Manual
 * ==============================
 */
$string_delv_date_m = "SELECT `delv_date` FROM `batch_transaction` "
        . "WHERE `mch_code`='".PLANT_ID."' AND UPPER(`flag_code`) = 'M'"
        . "GROUP BY `delv_date`"
        . "ORDER BY `delv_date` ";
$result_delv_date_m = $mysqli->query($string_delv_date_m);
if(!$result_delv_date){
    $time = date("h:i:s");
    $log_msg = "";
    $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
    write_log($log_file,$log_msg);
    $output = array(
        'status'    =>0,
        'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
    );
    exit(json_encode($output));
}



//menyimpan list file csv
$list_docket_file = array();
$list_mat_is_file = array();

$loop_docket = 1;
$count_data = 0;
while($row = $result_delv_date_m->fetch_array(MYSQLI_ASSOC)){
    //Query which become output
    $string_selected = "SELECT `seal_no`,`so_no`,'1' as sequence,`mch_code`,`product_code`,`delv_vol`,`unit_no`,`driver_id` "
        . "FROM `batch_transaction` "
        . "WHERE `delv_date` = '".$row['delv_date']."' AND `mch_code`='".PLANT_ID."' AND UPPER(`flag_code`) = 'M'";
    $result_selected = $mysqli->query($string_selected);
    if(!$result_selected){
        $time = date("h:i:s");
        $log_msg = "";
        $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
        write_log($log_file,$log_msg);
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
    
    $count_data += $result_selected->num_rows;
    
    $list_docket = array();
    while ($row_selected = $result_selected->fetch_array(MYSQLI_ASSOC)){
        //get list docket
        $list_docket[] = $row_selected;
    }
    
    //query just to get docket_no
    $string_docket = "SELECT `docket_no`,`so_no`,'1' as sequence,`mch_code`,`product_code`,`delv_vol`,`unit_no`,`driver_id` "
        . "FROM `batch_transaction` "
        . "WHERE `delv_date` = '".$row['delv_date']."' AND `mch_code`='".PLANT_ID."' AND (UPPER(`flag_code`) = 'M')";
    $result_docket = $mysqli->query($string_docket);
    if(!$result_docket){
        $time = date("h:i:s");
        $log_msg = "";
        $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
        write_log($log_file,$log_msg);
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
    
    $loop = 1;
    while($row_docket = $result_docket->fetch_array(MYSQL_ASSOC)):
        //get material issue 
        $string_mat_is = "SELECT ' ' request_no,a.`so_no`,'1' as so_line_no,b.`seal_no`,b.`Product_Code`,a.`material_code`,a.`target_qty`,a.`actual_qty`,a.`moisture`"
                . "FROM `batch_transaction_detail` AS a "
                . "INNER JOIN `batch_transaction` AS b ON  a.`docket_no`=b.`docket_no` WHERE a.`docket_no` ='".$row_docket['docket_no']."'";
        $result_mat_is = $mysqli->query($string_mat_is);
        if(!$result_mat_is){
            $time = date("h:i:s");
            $log_msg = "";
            $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
            write_log($log_file,$log_msg);
            $output = array(
                'status'    =>0,
                'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
            );
            exit(json_encode($output));
        }
        
        //field name hanya di append ke csv file pada looping pertama
        
        //get columns name
        $field_mat_is = $result_mat_is->fetch_fields();
        $list_field_mat_is = array();
        foreach ($field_mat_is as $val) {
            array_push($list_field_mat_is, $val->name);
        }
        
        //data list material issue
        $list_mat_is = array();
        while($row_mat_is = $result_mat_is->fetch_array(MYSQL_ASSOC)):
            $list_mat_is[] = $row_mat_is;
        endwhile;
        
        
        $mat_is_file_name = $row['delv_date']."_".$current_date_time."_".PLANT_ID.'_M_Mat_is.csv';
        
        //append field / column name
        //only at the first loop
        if($loop == 1){
            //Export Material Issue file
            $f_mat_is = fopen(TEMP_DIR.$mat_is_file_name, 'w');
            fputcsv($f_mat_is, $list_field_mat_is, ",");
        }
        else{
            $f_mat_is = fopen(TEMP_DIR.$mat_is_file_name, 'a+');
        }
        $loop++;
        foreach ($list_mat_is as $list_mat_is) { 
            fputcsv($f_mat_is, $list_mat_is, ",");
        }
        
        
    endwhile;
    //push file path to array
    array_push($list_mat_is_file, $mat_is_file_name);
    fclose($f_mat_is);
    
    //Getting field name
    $field_info = $result_selected->fetch_fields();
    $list_field_docket = array();
    foreach ($field_info as $val) {
        array_push($list_field_docket, $val->name);
    }
    
    //export Docket file 
    $docket_file_name = $row['delv_date']."_".$current_date_time."_".PLANT_ID.'_M_Docket.csv';
    if($loop_docket == 1){
        $fp = fopen(TEMP_DIR.$docket_file_name, 'w');
    }
    else{
        $fp = fopen(TEMP_DIR.$docket_file_name, 'a+');
    }
    
    //append field / column name
    fputcsv($fp, $list_field_docket, ",");
    
    //append docket records to DOCKET file
    foreach ($list_docket as $list_data) { 
        fputcsv($fp, $list_data, ",");
    }
    array_push($list_docket_file, $docket_file_name);
    fclose($fp);
    $loop_docket++;
    
    
    //UPDATE flag batch_transaction

    $string_update_flag2 = "UPDATE `batch_transaction` SET `flag_code`='T' "
            . "WHERE `delv_date` = '".$row['delv_date']."' "
            . "AND `mch_code`='".PLANT_ID."' "
            . "AND UPPER(`flag_code`) = 'M'";
    $result_update_flag2 = $mysqli->query($string_update_flag2);
    if(!$result_update_flag2){
        $time = date("h:i:s");
        $log_msg = "";
        $log_msg .= "\n".$time." -- Mysql Error ".mysqli_error($mysqli);
        write_log($log_file,$log_msg);
        
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Mysql Error</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
}//endwhile
foreach ($list_docket_file as $file) {
    $remote_file = '/dec/'.$file;
    if (ftp_put($ftp_conn, $remote_file, TEMP_DIR.$file, FTP_ASCII)) {
//        echo "successfully uploaded $file\n";
    } else {
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>-- Gagal Upload Ke FTP Server</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
}


foreach ($list_mat_is_file as $file) {
    $remote_file = '/dec/'.$file;
    if (ftp_put($ftp_conn, $remote_file, TEMP_DIR.$file, FTP_ASCII)) {
//        echo "successfully uploaded $file\n";
    } else {
        $output = array(
            'status'    =>0,
            'msg'       =>  "<span class='text-danger small'>Gagal Upload Ke FTP Server</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
        );
        exit(json_encode($output));
    }
}


// upload a file
$time = date("h:i:s");
$log_msg = "";
$log_msg .= "\n".$time." -- Process has Finished, ".$count_data." was processed";
write_log($log_file,$log_msg);

ftp_close($ftp_conn);

$output = array(
    'status'    => 0,
    'msg'       =>  "<span class='text-primary small'>Process Pengambilan Selesai</span>"."<span class=\"pull-right text-muted small\"><em>".  date("h:i A")."</em></span>"
);
exit(json_encode($output));
<?php
/*
 *Code By Abhishek R. Kaushik
 * Downloaded from http://devzone.co.in
 */
session_start();
include "db.php";

if(isset($_SESSION['login'])){ $object = (object)$_SESSION['login']; }
$Lg = $object->id_user;

$upload_dir = "uploads/ds/";
if (isset($_FILES["myfile"])) {
    if($_FILES["myfile"]["error"] > 0) { echo "Error: " . $_FILES["file"]["error"] . "<br>";}
    else{
        $today      =   date('d');
        $thismonth  =   date('m');
        $thisyear   =   date('Y');
        $thishour   =   date('H');//jam sekarang
//        If($thishour >= '21:00' && $thishour <= '07:00'){
//            $kodesch= 'S';
//        }else{
//            $kodesch= 'F';
//        }
//        $pieces = explode('/',$schedule_date);
//        print_r($kodesch);exit();
        $allowedExts = array("csv");
        $temp = explode(".", $_FILES["myfile"]["name"]);
        $extension = end($temp);
        if(!in_array($extension,$allowedExts)){ echo json_encode(array('status' => '0', 'msg' => 'Error : data type not allowed'));exit(); }
        $nama_file = date("Ymdhms")."_DS.csv"; //Nama file yang akan di Upload
        if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir . $nama_file)){
            $filename=$upload_dir.$nama_file;
            $handle_comb = fopen("$filename", "r");
            $handle  = fopen("$filename", "r");
            $handle1 = fopen("$filename", "r");
            $handle2 = fopen("$filename", "r");
            date_default_timezone_set("Asia/Jakarta");
            $hariini    = date("Y-m-d H:i:s");
            $pukul      = date("H");
            $tanggalupload =date("d/m/Y");
            $i=0;
            $updated_so="";
            $count_updated_so=0;
            $so_no="";
            $skip=1;
            //VALIDASI 1
            //CEK DUPLIKASI SO NO - DELV DATE
            //CHECK DUPLICATION in INPUT FILE
            $skip=1;
            $combination=array();
            while (($data1 = fgetcsv($handle1, 10000, ",")) !== FALSE){
                if($skip >= 5){
                    if(strlen($data1[3]) !== 10){
                        $output = array(
                            'status'    =>  0,
                            'msg'       =>  "Format Tanggal Salah pada baris ke ".$skip.", silahkan hubungi CO Jatiasih"
                        );
                    echo json_encode($output);exit();   
                    }
                    if(strlen($data1[3]) == 10){
                        $pecah = explode("/",$data1[3]);
                        if(strlen($pecah[0]) != 2 || strlen($pecah[1]) != 2 || strlen($pecah[2]) != 4){
                            $output = array(
                                'status'    =>  0,
                                'msg'       =>  "Format Tanggal Salah pada baris ke ".$skip.", silahkan hubungi CO Jatiasih"
                            );
                            echo json_encode($output);exit();   
                        }
                    }
                    //CEK 1-24Hr <= so vol + 25%*so vol
                    if((int)$data1[14] > (int)$data1[13]+((int)$data1[13]*0.25)){
                        $output = array(
                            'status'    =>  0,
                            'msg'       =>  'Data tidak valid, pada baris ke-'.$skip.', Nilai <b>'.$data1[14].'</b> pada Kolom <b>1-24Hr</b>  lebih besar dari nilai kolom <b>Order Volume</b> +  nilai kolom <b>Order Volume</b> * 25%'
                        );
                        echo json_encode($output);exit();
                    }
                    if(!in_array($data1[0].'-'.$data1[3],$combination)){
                        array_push($combination,$data1[0].'-'.$data1[3]);
                    }
                    else {
                        echo json_encode(array('status' => '0', 'msg' => 'Duplikasi SO No : '.$data1[0].' dan Delivery Date : '.$data1[3].', ditemukan pada baris ke-'.$skip));exit();
                    }
                }
                $skip++;
            }
            $skip=1;
            $empty_temp = 0;
            $list_so="";
            while (($data = fgetcsv($handle2, 10000, ",")) !== FALSE){
                if($skip >= 5){
                    if($data[14] != "" || !empty($data[14])){
                        $pkl=(date("d/m/Y"));
                        $dscode='F';
//                        if( $lkp == $pkl){
//                            $dscode='S'; }
//                            else{ 
//                                $dscode='F'; }
                        $string = "SELECT * FROM `delivery_schedule` WHERE `so_no`='".$data[0]."' AND `schedule_date` = '".$data[3]."'";
                        $q = mysqli_query($conns,$string);
                        $count = mysqli_num_rows($q);
                        if($count == 0){
                            //print_r($dscode);//exit();
                            $string = "INSERT INTO `delivery_schedule` VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."','".$data[12]."','".$data[13]."','".$data[14]."','".$data[15]."','".$data[16]."','".$data[17]."','".$data[18]."','".$data[19]."','".$data[20]."','".$data[21]."','".$data[22]."','".$data[23]."','".$data[24]."','".$data[25]."','".$data[26]."','".$data[27]."','".$data[28]."','".$data[29]."','".$data[30]."','".$data[31]."','".$data[32]."','".$data[33]."','".$data[34]."','".$data[35]."','".$data[36]."','".$data[37]."','".$data[38]."','".$data[39]."','".$data[40]."',' ','','0','".$dscode."','".$hariini."','".$Lg."','')";
//                            print_r($string);exit();
                            if(mysqli_query($conns,$string)) $i++;
                            else {
                                echo json_encode(array('status' => '0', 'msg' => 'Error #11 Mysql: '.mysqli_error($conns)));exit();
                            }
                        }
                        else{
                            $column_1_24hr = 0;
                            $data_ds = mysqli_fetch_array($q);
                            $column_1_24hr = $data_ds['1-24hr'];
                            if($column_1_24hr <= $data[14]){
                                $string_delete = "DELETE FROM `delivery_schedule` WHERE `so_no`='".$data[0]."' AND `schedule_date` = '".$data[3]."'";
                                $status_delete = mysqli_query($conns,$string_delete);
                                if(!$status_delete){
                                    $output = array(
                                        'status'    =>  0,
                                        'msg'       =>  'Gagal Menghapus data, MYSQL Error : '.mysqli_error()
                                    );
                                    echo json_encode($output);exit();
                                }
                                $string_insert = "INSERT INTO `delivery_schedule` VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."','".$data[12]."','".$data[13]."','".$data[14]."','".$data[15]."','".$data[16]."','".$data[17]."','".$data[18]."','".$data[19]."','".$data[20]."','".$data[21]."','".$data[22]."','".$data[23]."','".$data[24]."','".$data[25]."','".$data[26]."','".$data[27]."','".$data[28]."','".$data[29]."','".$data[30]."','".$data[31]."','".$data[32]."','".$data[33]."','".$data[34]."','".$data[35]."','".$data[36]."','".$data[37]."','".$data[38]."','".$data[39]."','".$data[40]."',' ','','0','".$dscode."','".$hariini."','".$Lg."','')";
                                $status_insert = mysqli_query($conns,$string_insert);
                                if(!$status_insert){
                                   $output = array(
                                       'status'    =>  0,
                                       'msg'       =>  'Gagal Insert data, MYSQL Error : '.mysqli_error()
                                   );
                                   echo json_encode($output);exit();
                                }
                                else{
                                    $count_updated_so++;
                                    $updated_so.=$data[0]." ";
                                }
                            }
                        }
                    }
                    else{
                            $empty_temp++;
                            $list_so.="".$skip.", ";
                    }
                }
                $skip++;
            }
            if($i == 0){
                if($count_updated_so > 0){
                    if($empty_temp > 0){
                        $output = array(
                            'status'    =>  '1',
                            'msg'       =>  '<b>SUKSES! </b>Sebanyak '.$count_updated_so." data berhasil diperbarui<br> dengan List <b>SO No </b>: ".$updated_so."<br> Sebanyak ".$empty_temp." Data dengan Nilai kosong pada Kolom <b>1-24Hr</b> ditemukan pada baris : ".$list_so
                        );
                        echo json_encode($output);exit();
                    }
                    else{
                        $output = array(
                            'status'    =>  '1',
                            'msg'       =>  '<b>SUKSES! </b> Sebanyak '.$count_updated_so." data berhasil diperbarui, dengan SO No : ".$updated_so
                        );
    					echo json_encode($output);exit();
                    }	    
                }
                else {
                    if($empty_temp > 0){
                        $output = array(
                            'status'    => '2', 
                            'msg'       => '<b>Tidak ada data yang ditambahkan</b><br/>Ditemukan Sebanyak '.$empty_temp." Record yang memiliki nilai kosong pada kolom <b>1-24Hr</b>, yaitu pada Baris ke: ".$list_so);
                        echo json_encode($output);exit();
                    }
                    else{
                        $output = array(
                            'status'    => '2', 
                            'msg'       => '<b>Tidak ada data yang ditambahkan</b>');
                        echo json_encode($output);exit();
                    }	    
                }
            }
            else{
                if($count_updated_so > 0){
                    if($empty_temp > 0){
                        $output = array(
                            'msg'       => 'Sebanyak '.$i.' Data baru berhasil ditambahkan, '.$count_updated_so.' Data berhasil diperbarui <br> ditemukan sebanyak '.$empty_temp." Record yang memiliki nilai kosong pada kolom <b>1-24Hr</b>, yaitu pada Baris ke: ".$list_so, 
                            'status'    => 1
                         );
                        echo json_encode($output);
                    }
                    else{
                         $output = array(
                            'msg'       => 'Sebanyak '.$i.' Data baru berhasil ditambahkan dan '.$count_updated_so.' Data berhasil diperbarui ', 
                            'status'    => 1
                         );
                        echo json_encode($output);
                    }
                }
                else{
                    if($empty_temp > 0){
                        $output = array(
                            'msg'       => 'Sebanyak '.$i.' Data baru berhasil ditambahkan<br> dan ditemukan sebanyak '.$empty_temp." Record yang memiliki nilai kosong pada kolom <b>1-24Hr</b>, yaitu pada Baris ke: ".$list_so, 
                            'status'    => 1
                        );
                        echo json_encode($output);
                    }
                    else{
                        $output = array(
                            'msg'       => 'Sebanyak '.$i.' Data baru berhasil ditambahkan', 
                            'status'    => 1
                        );
                        echo json_encode($output);
                    }
                }
            }
        }
    }
}
else{
	echo json_encode(array('status'	=> '0','msg' => 'Anda Harus memilih satu file untuk di-upload!', 'c' => 3, 'd' => 4, 'e' => 5));
}
mysqli_close($conns);
?>
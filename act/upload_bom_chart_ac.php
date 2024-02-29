<?php
//error_reporting(0);
function close_con($con) {
    mysql_close($con);
}
function trans_begin($con) {
    $sql = "SET autocommit = 0";
    return mysql_query($sql,$con);
}
function trans_end($con) {
    $sql = "SET autocommit = 1";
    return mysql_query($sql,$con);
}
function trans_commit($con) {
    trans_end($con);
    $sql = "COMMIT";
    return mysql_query($sql,$con);
}
function trans_rollback($con) {
    trans_end($con);
    $sql = "ROLLBACK";
    return mysql_query($sql,$con);
}
include "../inc/constant.php";
ini_set('max_execution_time',500);
$upload_dir = "../uploads/mixdesign/";
$con = @mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(json_encode(array("status"=>0,"msg"=>  mysql_error())));
mysql_select_db(DB_NAME, $con);
try {
    if(!isset($_POST["chart_no"]) || !isset($_POST["chart_no"])){
        throw new Exception('Anda');
    }
    if(!isset($_FILES["myfile"]) || !isset($_FILES["myfile2"])){
        throw new Exception('Anda harus input-kan 2 file (BMA & BMB)');
    }
    if ($_FILES["myfile"]["error"] > 0 || $_FILES["myfile2"]["error"] > 0) {
        throw new Exception("Error : #1 ".$_FILES["myfile"]["error"]);
    }
    
    $allowedExts    = array("csv");
    $temp           = explode(".", $_FILES["myfile"]["name"]);
    $temp2          = explode(".", $_FILES["myfile2"]["name"]);
    $extension      = end($temp);
    $extension2     = end($temp2);

    if(!in_array($extension,$allowedExts) || !in_array($extension2,$allowedExts)){
        throw new Exception("Hanya tipe data .csv yang dapat digunakan!");
    }
    $nama_file = date("Ymdhms")."_BMA.csv"; //Nama file yang akan di Upload
    $nama_file2 = date("Ymdhms")."_BMB.csv"; //Nama file yang akan di Upload
    
    //jika berhasil mengupload 2 file
    if(!move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir.$nama_file) || !move_uploaded_file($_FILES["myfile2"]["tmp_name"], $upload_dir . $nama_file2)){
        throw new Exception("Error #55 : Files cannot be uploaded to the directory ".$upload_dir);
    }
    $filename   = $upload_dir.$nama_file;
    $filename2  = $upload_dir.$nama_file2;
    $handle1    = fopen($filename, "r");
    $handle3    = fopen($filename, "r");
    $handle2    = fopen("$filename2", "r");
    $handle4    = fopen("$filename2", "r");
    $i          = 0;
    //cek machine code product code
    $mach_code      =   array();
    $product_code   =   array();
    $skip   =   0;//0 row skip once, 1 no skip
    while (($data = fgetcsv($handle1, 1000, ",")) !== FALSE)
    {
        if($skip > 0){
            //PLANT ID Checking
            if(PLANT_ID != $data[10]){
                throw new Exception("PLANT ID tidak cocok dengan konfigurasi");
            }
            if(!in_array($data[0].'-'.$data[10],$mach_code)){
                array_push($mach_code,$data[0].'-'.$data[10]);
            }
            else{
                throw new Exception("Mix Design Data has duplication machine code and product code");
            }
        }
        $skip++;
    }
                /*print_r($mach_code);exit();*/
    $difference = 0;
    $skip2=0;//0 skip one line, 1 no skip
    $difference_list = "";
    while (($data2 = fgetcsv($handle2, 1000, ",")) !== FALSE)
    {
        if($skip2 > 0){
            if(!in_array($data2[0].'-'.$data2[32],$mach_code)){
                $difference++;
                $difference_list.='<br/>'.$data2[0]."-".$data2[32]." baris ke-".($skip2+1)." BMB";
            }
        }
        $skip2++;
    }
    //

    //IF detail doesn't match with main table'
    if($difference > 0){
        throw new Exception("Error #32: Data is not valid, there\'s difference machine code and product code combination, ".$difference_list);
    }
    //MIX DESIGN - s
    $skip = 0;
    $count_updated=0;$updated_list="";
    while (($data = fgetcsv($handle3, 10000, ",")) !== FALSE){
        if($skip > 0){
            $string = "SELECT * FROM `mix_package` WHERE `product_code`='".$data[0]."' AND `mch_code` = '".$data[10]."'";
            //echo $string."<br>";exit();
            $q = mysql_query($string);
            if(!$q){
                throw new Exception('Error : #21'.mysql_error($con));
            }
            
            //TRANSACTION BEGIN
            trans_begin($con);
            if(!trans_begin($con)){throw new Exception("MYSQL : ERROR ".  mysql_error($con));}            
            $count = mysql_num_rows($q);
            if($count == 0){
                $a1 = "INSERT INTO `mix_package` "
                        . "(`product_code`,`mch_code`, `slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date`) "
                        . "VALUES ('".$data[0]."','".$data[10]."','".$data[18]."','NORMAL','".$data[34]."','".$data[34]."',' ','I','0','DEC',NOW(),'DEC',NOW())";
                //echo "<br>".$a1;
                $a = mysql_query($a1,$con);
                if($a){
                    $i++;
                }
                else{
                    trans_rollback($con);
                    throw new Exception('Error : #48'.mysql_error());
                }
            }
            else{
                $row = mysql_fetch_array($q);
                $query_delete = "DELETE FROM `mix_package` WHERE `mch_code` = '".$data[10]."' AND `product_code`='".$data[0]."'";
       //         $query_delete = "DELETE FROM `mix_package`";
                $status_delete = mysql_query($query_delete);
                //tes
                $packet_delete = "DELETE FROM `mix_package_composition` WHERE `mch_code` = '".$data[10]."' AND `product_code`='".$data[0]."'";
                $status_packet = mysql_query($packet_delete);
                $a1 = "INSERT INTO `mix_package` "
                        . "(`product_code`,`mch_code`, `slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date`) "
                        . "VALUES ('".$data[0]."','".$data[10]."','".$data[18]."','NORMAL','".$data[34]."','".$data[34]."',' ','I','0','DEC',NOW(),'DEC',NOW())";
                $status_insert = mysql_query($a1,$con);
                if(!$status_insert){
                    trans_rollback($con);
                    throw new Exception('Error : #49'.mysql_error());
                }
                else {
                        $i++;$count_updated++;$updated_list.=$data[0]."_".$data[10]."<br/>";}; 
                        //hitung jumlah Mix Design yang terupload
            }
        }
        $skip++;
    }//end while
    //MIX DESIGN - e
   
    //MIX DESIGN COMPOSITION - s
    $j      = 0;
    $mch    = "";
    $skip   = 0;
    while(($data2 = fgetcsv($handle4,2000,","))!==FALSE){
            if($skip > 0){                
                //get last chart_no in table mix_package_composition particullar composition
                $string = "SELECT MAX(`chart_no`) chart_no FROM `mix_package_composition` WHERE `product_code`='".$data2[0]."' AND `mch_code` = '".$data2[32]."' AND `material_code` = '".$data2[2]."'";
                $q = mysql_query($string,$con);
                if(!$q){
                    trans_rollback($con);
                    throw new Exception("Error #11 : ".mysql_error());
                }
                $chartno=1;
                while($data_chart = mysql_fetch_array($q)){
                    $chartno = $data_chart['chart_no']+1;
                }

                $material_group = "";
                if($data2[2] == "0000-100030") $material_group = "Water"; //WATER
                if($data2[2] == "0000-100011") $material_group = "Cement"; //CEMENT
                if($data2[2] == "0000-000015") $material_group = "Cement"; //FA
                if($data2[2] == "0000-100001") $material_group = "Aggregate"; //SAND
                if($data2[2] == "0000-100002") $material_group = "Aggregate"; //MSAND
                if($data2[2] == "0000-100005") $material_group = "Aggregate"; //AGG1
                if($data2[2] == "0002-100001") $material_group = "Admixture"; //P121R
                if($data2[2] == "0000-100006") $material_group = "Aggregate"; //AGG2
                if($data2[2] == "0000-100007") $material_group = "Aggregate"; //AGG3
                if(substr($data2[2],0,4) == "0002") $material_group = "Admixture";
                /*if($data2[2] == "0002-100201") $material_group = "Admixture"; //S523N
                if($data2[2] == "0002-100401") $material_group = "Admixture"; //S523N
                if($data2[2] == "0002-100402") $material_group = "Admixture"; //S523N
                if($data2[2] == "0002-100404") $material_group = "Admixture"; //S523N*/
                $q_compos = "INSERT INTO `mix_package_composition` "
                        . "(`mch_code`,`product_code`, `material_group`,`seq_no`, `material_code`,`material_name`, `mix_qty`,`mix_qty_adj`, `unit`,`flag_code`,`chart_no`) "
                        . "VALUES ('".$data2[32]."','".$data2[0]."', '".$material_group."',' ','".$data2[2]."','".$data2[55]."','".$data2[5]."','0','KG','I','".$chartno."')";
                $result = mysql_query($q_compos,$con);
                if(!$result){
                    trans_rollback($con);
                    throw new Exception("Error MYSQL : ".  mysql_error($con));
                }
                else{
                    $j++;//hitung jumlah composition yg terupload
                }
            }
            $skip++;
    }
    //MIX DESIGN COMPOSITION - e
    if($i > 0){
        if($count_updated > 0){
            echo json_encode(array('status'	=>	1,'duplicate'	=>	$mch,'msg'=>'data uploaded, Mix design: '.$i.' data, Material : '.$j.' data, Data updated : '.$updated_list));
        }
        else echo json_encode(array('status'    =>	1,'duplicate'	=>	$mch,'msg'=>'data uploaded, Mix design: '.$i.' data, Material : '.$j.' data'));
    }
    else{
        echo json_encode(array('status'	=>	2,'msg'=>'There\'s no updated or inserted data'));
    }
    trans_commit($con);
    close_con($con);
} catch (Exception $exc) {
    $output = array(
        'msg'       => $exc->getMessage(), 
        'status'    => 0
    );
    close_con($con);
    exit(json_encode($output));
}
